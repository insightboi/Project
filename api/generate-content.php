<?php
// API endpoint for content generation
require_once '../config.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$projectId = $data['project_id'] ?? 0;
$section = $data['section'] ?? '';

if (!$projectId || !$section) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit();
}

// Verify project ownership
$project = getProject($projectId, $_SESSION['user_id']);
if (!$project) {
    echo json_encode(['success' => false, 'message' => 'Project not found']);
    exit();
}

// Get AI prompt template
global $conn;
$stmt = $conn->prepare("SELECT template_text FROM ai_prompts WHERE module_type = ? AND is_active = 1 LIMIT 1");
$stmt->bind_param("s", $section);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'AI prompt template not found']);
    exit();
}

$template = $result->fetch_assoc()['template_text'];

// Replace placeholders with project data
$prompt = str_replace([
    '{idea}', '{title}', '{genre}', '{target_audience}', '{language}',
    '{logline}', '{characters}', '{story}'
], [
    $project['idea'], $project['title'], $project['genre'], $project['target_audience'], $project['language'],
    $project['logline'] ?? 'Not generated yet', $project['main_characters'] ?? 'Not generated yet', 
    $project['idea']
], $template);

// Call AI API
$aiResult = callOpenAI($prompt);

if ($aiResult['success']) {
    // Update project with generated content
    $content = $aiResult['content'];
    
    switch ($section) {
        case 'story':
            $stmt = $conn->prepare("UPDATE projects SET logline = ?, theme = ?, emotional_tone = ?, unique_hook = ?, status = 'generated' WHERE id = ?");
            // Parse AI response to extract different parts (simplified for demo)
            $parts = explode("\n\n", $content);
            $logline = $parts[0] ?? '';
            $theme = $parts[1] ?? '';
            $tone = $parts[2] ?? '';
            $hook = $parts[3] ?? '';
            $stmt->bind_param("ssssi", $logline, $theme, $tone, $hook, $projectId);
            break;
            
        case 'characters':
            $stmt = $conn->prepare("UPDATE projects SET main_characters = ?, status = 'generated' WHERE id = ?");
            $stmt->bind_param("si", $content, $projectId);
            break;
            
        case 'production':
            $stmt = $conn->prepare("UPDATE projects SET budget_estimate = ?, locations = ?, cast_crew_requirements = ?, shooting_schedule = ?, status = 'generated' WHERE id = ?");
            // Simplified parsing
            $budget = "Medium ($50K - $100K)";
            $locations = json_encode(["Urban city", "Suburban house", "Office building"]);
            $crew = json_encode(["Director", "Cinematographer", "Sound Engineer", "Editor"]);
            $schedule = "4 weeks shooting, 2 weeks pre-production, 1 week post-production";
            $stmt->bind_param("ssssi", $budget, $locations, $crew, $schedule, $projectId);
            break;
            
        case 'pitch_deck':
            $stmt = $conn->prepare("UPDATE projects SET one_line_pitch = ?, market_appeal = ?, visual_style_reference = ?, status = 'generated' WHERE id = ?");
            $parts = explode("\n\n", $content);
            $pitch = $parts[0] ?? '';
            $market = $parts[1] ?? '';
            $visual = $parts[2] ?? '';
            $stmt->bind_param("sssi", $pitch, $market, $visual, $projectId);
            break;
    }
    
    if ($stmt->execute()) {
        // Log usage
        $stmt = $conn->prepare("INSERT INTO usage_analytics (user_id, project_id, action, tokens_used) VALUES (?, ?, 'generate_content', 1000)");
        $stmt->bind_param("ii", $_SESSION['user_id'], $projectId);
        $stmt->execute();
        
        echo json_encode([
            'success' => true, 
            'content' => $content,
            'message' => ucfirst(str_replace('_', ' ', $section)) . ' generated successfully'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save generated content']);
    }
} else {
    echo json_encode(['success' => false, 'message' => $aiResult['message']]);
}
?>
