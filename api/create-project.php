<?php
// API endpoint for creating projects
require_once '../config.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$title = sanitizeInput($data['title'] ?? '');
$idea = sanitizeInput($data['idea'] ?? '');
$genre = sanitizeInput($data['genre'] ?? '');
$targetAudience = sanitizeInput($data['target_audience'] ?? '');
$language = sanitizeInput($data['language'] ?? 'English');

// Validation
if (empty($title)) {
    echo json_encode(['success' => false, 'message' => 'Project title is required']);
    exit();
}

if (empty($idea)) {
    echo json_encode(['success' => false, 'message' => 'Film idea is required']);
    exit();
}

if (strlen($idea) < 50) {
    echo json_encode(['success' => false, 'message' => 'Please provide a more detailed idea (at least 50 characters)']);
    exit();
}

// Create project
$result = createProject($_SESSION['user_id'], $title, $idea, $genre, $targetAudience, $language);

if ($result['success']) {
    // Log usage
    global $conn;
    $stmt = $conn->prepare("INSERT INTO usage_analytics (user_id, project_id, action, tokens_used) VALUES (?, ?, 'create_project', 100)");
    $stmt->bind_param("ii", $_SESSION['user_id'], $result['project_id']);
    $stmt->execute();
    
    echo json_encode([
        'success' => true,
        'project_id' => $result['project_id'],
        'message' => 'Project created successfully'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => $result['message']]);
}
?>
