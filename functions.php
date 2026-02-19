<?php
// Database connection function
function connectDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

// User authentication functions
function registerUser($name, $email, $password) {
    global $conn;
    
    // Validate input
    if (empty($name) || empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'All fields are required'];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email format'];
    }
    
    if (strlen($password) < 8) {
        return ['success' => false, 'message' => 'Password must be at least 8 characters'];
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return ['success' => false, 'message' => 'Email already exists'];
    }
    
    // Hash password and insert user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $name, $email, $hashed_password);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Registration successful'];
    } else {
        return ['success' => false, 'message' => 'Registration failed'];
    }
}

function loginUser($email, $password) {
    global $conn;
    
    if (empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Email and password are required'];
    }
    
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        return ['success' => true, 'message' => 'Login successful'];
    } else {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
}

// Admin authentication functions
function loginAdmin($email, $password) {
    global $conn;
    
    if (empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Email and password are required'];
    }
    
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM admin_users WHERE email = ? AND is_active = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    $admin = $result->fetch_assoc();
    
    if (password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_role'] = $admin['role'];
        return ['success' => true, 'message' => 'Login successful'];
    } else {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Project management functions
function createProject($userId, $title, $idea, $genre, $targetAudience, $language) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO projects (user_id, title, idea, genre, target_audience, language, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'draft', NOW())");
    $stmt->bind_param("isssss", $userId, $title, $idea, $genre, $targetAudience, $language);
    
    if ($stmt->execute()) {
        return ['success' => true, 'project_id' => $conn->insert_id];
    } else {
        return ['success' => false, 'message' => 'Failed to create project'];
    }
}

function getUserProjects($userId) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $projects = [];
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
    
    return $projects;
}

function getProject($projectId, $userId = null) {
    global $conn;
    
    if ($userId) {
        $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $projectId, $userId);
    } else {
        $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->bind_param("i", $projectId);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return null;
    }
    
    return $result->fetch_assoc();
}

// AI Integration functions
function callOpenAI($prompt, $maxTokens = 2000) {
    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a professional screenwriter and film production expert. Generate high-quality, creative content for film projects.'
            ],
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ],
        'max_tokens' => $maxTokens,
        'temperature' => 0.7
    ];
    
    $ch = curl_init(OPENAI_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENAI_API_KEY
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        return ['success' => false, 'message' => 'AI API error: ' . $response];
    }
    
    $result = json_decode($response, true);
    return [
        'success' => true,
        'content' => $result['choices'][0]['message']['content']
    ];
}

// Utility functions
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

// Export functions
function exportToPDF($content, $filename) {
    // This would require a PDF library like TCPDF or DomPDF
    // For now, return a placeholder
    return ['success' => false, 'message' => 'PDF export not implemented yet'];
}

function exportToDOCX($content, $filename) {
    // This would require a PHPWord library
    // For now, return a placeholder
    return ['success' => false, 'message' => 'DOCX export not implemented yet'];
}

function exportToTXT($content, $filename) {
    $filepath = EXPORT_PATH . $filename . '.txt';
    if (file_put_contents($filepath, $content)) {
        return ['success' => true, 'filepath' => $filepath];
    } else {
        return ['success' => false, 'message' => 'Failed to create text file'];
    }
}
?>
