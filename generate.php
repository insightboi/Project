<?php
require_once 'config.php';
requireLogin();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create-project.php');
    exit();
}

// Get and validate form data
$title = sanitizeInput($_POST['title'] ?? '');
$idea = sanitizeInput($_POST['idea'] ?? '');
$genre = sanitizeInput($_POST['genre'] ?? '');
$target_audience = sanitizeInput($_POST['target_audience'] ?? '');
$language = sanitizeInput($_POST['language'] ?? 'English');

// Validation
if (empty($title) || empty($idea)) {
    $_SESSION['error'] = 'Title and idea are required fields.';
    header('Location: create-project.php');
    exit();
}

try {
    // Create project in database
    $projectData = [
        'title' => $title,
        'idea' => $idea,
        'genre' => $genre,
        'target_audience' => $target_audience,
        'language' => $language,
        'status' => 'draft'
    ];
    
    $projectId = createProject($_SESSION['user_id'], $projectData);
    
    if (!$projectId) {
        throw new Exception('Failed to create project');
    }
    
    // Generate AI content
    $aiGeneratedContent = generateScreenplayContent($title, $idea, $genre, $target_audience);
    
    // Update project with AI-generated content
    $updateData = [
        'logline' => $aiGeneratedContent['logline'] ?? '',
        'theme' => $aiGeneratedContent['theme'] ?? '',
        'emotional_tone' => $aiGeneratedContent['emotional_tone'] ?? '',
        'unique_hook' => $aiGeneratedContent['unique_hook'] ?? '',
        'act1_breakdown' => $aiGeneratedContent['act1'] ?? '',
        'act2_breakdown' => $aiGeneratedContent['act2'] ?? '',
        'act3_breakdown' => $aiGeneratedContent['act3'] ?? '',
        'main_characters' => json_encode($aiGeneratedContent['main_characters'] ?? []),
        'supporting_characters' => json_encode($aiGeneratedContent['supporting_characters'] ?? []),
        'status' => 'generated'
    ];
    
    $updated = updateProject($projectId, $updateData);
    
    if (!$updated) {
        throw new Exception('Failed to update project with generated content');
    }
    
    // Redirect to review page
    header('Location: project.php?id=' . $projectId);
    exit();
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Error generating screenplay: ' . $e->getMessage();
    header('Location: create-project.php');
    exit();
}

/**
 * Generate screenplay content using AI
 */
function generateScreenplayContent($title, $idea, $genre, $target_audience) {
    // In a real implementation, this would call OpenAI API
    // For now, returning structured mock data
    
    $prompt = "Create a screenplay structure for a film titled '$title' with the following idea: '$idea'. Genre: $genre. Target audience: $target_audience.";
    
    // Mock AI response - replace with actual OpenAI API call
    return [
        'logline' => "A compelling logline for '$title' that captures the essence of the story.",
        'theme' => "The central theme explores the human condition through the lens of $genre.",
        'emotional_tone' => ucfirst($genre) . ' with moments of tension and resolution',
        'unique_hook' => "What makes this story unique is its fresh take on the $genre genre.",
        'act1' => "Act 1 introduces the protagonist and their world, establishing the inciting incident that sets the story in motion.",
        'act2' => "Act 2 develops the conflict through rising action, introducing obstacles and character development.",
        'act3' => "Act 3 brings the story to its climax and resolution, delivering emotional payoff.",
        'main_characters' => [
            [
                'name' => 'Protagonist',
                'description' => 'The main character who drives the story forward',
                'arc' => 'Transforms from ordinary to extraordinary'
            ]
        ],
        'supporting_characters' => [
            [
                'name' => 'Mentor',
                'description' => 'Guides the protagonist on their journey'
            ]
        ]
    ];
}
?>
