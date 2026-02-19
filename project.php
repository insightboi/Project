<?php
require_once 'config.php';
requireLogin();

$projectId = $_GET['id'] ?? 0;
$project = getProject($projectId, $_SESSION['user_id']);

if (!$project) {
    header('Location: dashboard.php');
    exit();
}

$success = $_GET['created'] ?? '';
$regenerated = $_GET['regenerated'] ?? '';

// Handle content generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_section'])) {
    $section = $_POST['generate_section'];
    
    // Get the appropriate AI prompt template
    global $conn;
    $stmt = $conn->prepare("SELECT template_text FROM ai_prompts WHERE module_type = ? AND is_active = 1 LIMIT 1");
    $stmt->bind_param("s", $section);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
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
                    $stmt = $conn->prepare("UPDATE projects SET logline = ?, theme = ?, emotional_tone = ?, unique_hook = ? WHERE id = ?");
                    // Parse AI response to extract different parts (simplified for demo)
                    $parts = explode("\n\n", $content);
                    $logline = $parts[0] ?? '';
                    $theme = $parts[1] ?? '';
                    $tone = $parts[2] ?? '';
                    $hook = $parts[3] ?? '';
                    $stmt->bind_param("ssssi", $logline, $theme, $tone, $hook, $projectId);
                    break;
                    
                case 'characters':
                    $stmt = $conn->prepare("UPDATE projects SET main_characters = ? WHERE id = ?");
                    $stmt->bind_param("si", $content, $projectId);
                    break;
                    
                case 'production':
                    $stmt = $conn->prepare("UPDATE projects SET budget_estimate = ?, locations = ?, cast_crew_requirements = ?, shooting_schedule = ? WHERE id = ?");
                    // Simplified parsing
                    $budget = "Medium ($50K - $100K)";
                    $locations = json_encode(["Urban city", "Suburban house", "Office building"]);
                    $crew = json_encode(["Director", "Cinematographer", "Sound Engineer", "Editor"]);
                    $schedule = "4 weeks shooting, 2 weeks pre-production, 1 week post-production";
                    $stmt->bind_param("ssssi", $budget, $locations, $crew, $schedule, $projectId);
                    break;
                    
                case 'pitch_deck':
                    $stmt = $conn->prepare("UPDATE projects SET one_line_pitch = ?, market_appeal = ?, visual_style_reference = ? WHERE id = ?");
                    $parts = explode("\n\n", $content);
                    $pitch = $parts[0] ?? '';
                    $market = $parts[1] ?? '';
                    $visual = $parts[2] ?? '';
                    $stmt->bind_param("sssi", $pitch, $market, $visual, $projectId);
                    break;
            }
            
            if ($stmt->execute()) {
                header('Location: project.php?id=' . $projectId . '&regenerated=' . $section);
                exit();
            }
        }
    }
}

// Refresh project data
$project = getProject($projectId, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> - Smart Film Makers</title>
    
    <!-- MDBootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                🎬 <span class="fw-bold">Smart Film Makers</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">
                                <i class="fas fa-user me-2"></i>Profile
                            </a></li>
                            <li><a class="dropdown-item" href="settings.php">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Project Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="display-6 fw-bold mb-2"><?php echo htmlspecialchars($project['title']); ?></h1>
                <p class="text-muted mb-3">
                    <i class="fas fa-calendar me-2"></i>Created <?php echo formatDate($project['created_at']); ?>
                    <?php if ($project['genre']): ?>
                        <span class="ms-3"><i class="fas fa-film me-2"></i><?php echo htmlspecialchars($project['genre']); ?></span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="btn-group" role="group">
                    <a href="edit-project.php?id=<?php echo $project['id']; ?>" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <button class="btn btn-outline-success" onclick="showExportOptions(<?php echo $project['id']; ?>)">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Success Messages -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                Project created successfully! Start generating your film content below.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($regenerated): ?>
            <div class="alert alert-info alert-dismissible fade show">
                <?php echo ucfirst(str_replace('_', ' ', $regenerated)); ?> content has been regenerated!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="projectTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="story-tab" data-bs-toggle="tab" data-bs-target="#story" type="button">
                    <i class="fas fa-book me-2"></i>Story Foundation
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="characters-tab" data-bs-toggle="tab" data-bs-target="#characters" type="button">
                    <i class="fas fa-users me-2"></i>Characters
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="screenplay-tab" data-bs-toggle="tab" data-bs-target="#screenplay" type="button">
                    <i class="fas fa-file-alt me-2"></i>Screenplay
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="production-tab" data-bs-toggle="tab" data-bs-target="#production" type="button">
                    <i class="fas fa-clipboard-list me-2"></i>Production Plan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pitch-tab" data-bs-toggle="tab" data-bs-target="#pitch" type="button">
                    <i class="fas fa-presentation me-2"></i>Pitch Deck
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="projectTabContent">
            <!-- Story Foundation Tab -->
            <div class="tab-pane fade show active" id="story" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Story Foundation</h5>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="generate_section" value="story">
                                <button type="submit" class="btn btn-primary btn-sm" 
                                        onclick="return confirm('This will regenerate the story foundation. Continue?')">
                                    <i class="fas fa-magic me-2"></i>Generate/Regenerate
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($project['logline']): ?>
                            <div class="mb-4">
                                <h6>Logline</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($project['logline'])); ?></p>
                            </div>
                            
                            <div class="mb-4">
                                <h6>Theme</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($project['theme'])); ?></p>
                            </div>
                            
                            <div class="mb-4">
                                <h6>Emotional Tone</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($project['emotional_tone'])); ?></p>
                            </div>
                            
                            <div class="mb-4">
                                <h6>Unique Hook</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($project['unique_hook'])); ?></p>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <h5>Story Foundation Not Generated</h5>
                                <p class="text-muted">Click "Generate" to create your story foundation including logline, theme, and unique hook.</p>
                                <form method="POST">
                                    <input type="hidden" name="generate_section" value="story">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-magic me-2"></i>Generate Story Foundation
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Characters Tab -->
            <div class="tab-pane fade" id="characters" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Character Profiles</h5>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="generate_section" value="characters">
                                <button type="submit" class="btn btn-primary btn-sm"
                                        onclick="return confirm('This will regenerate character profiles. Continue?')">
                                    <i class="fas fa-magic me-2"></i>Generate/Regenerate
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($project['main_characters']): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Main Characters</h6>
                                    <div class="character-list">
                                        <?php echo nl2br(htmlspecialchars($project['main_characters'])); ?>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5>Characters Not Generated</h5>
                                <p class="text-muted">Click "Generate" to create detailed character profiles for your story.</p>
                                <form method="POST">
                                    <input type="hidden" name="generate_section" value="characters">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-magic me-2"></i>Generate Characters
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Screenplay Tab -->
            <div class="tab-pane fade" id="screenplay" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Screenplay Draft</h5>
                            <button class="btn btn-secondary btn-sm" disabled>
                                <i class="fas fa-tools me-2"></i>Coming Soon
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5>Screenplay Generation</h5>
                            <p class="text-muted">Full screenplay generation will be available in the next update. This will include scene-by-scene breakdowns with proper screenplay formatting.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Production Plan Tab -->
            <div class="tab-pane fade" id="production" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Production Plan</h5>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="generate_section" value="production">
                                <button type="submit" class="btn btn-primary btn-sm"
                                        onclick="return confirm('This will regenerate the production plan. Continue?')">
                                    <i class="fas fa-magic me-2"></i>Generate/Regenerate
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($project['budget_estimate']): ?>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <h6>Budget Estimate</h6>
                                    <p class="text-muted"><?php echo htmlspecialchars($project['budget_estimate']); ?></p>
                                </div>
                                
                                <?php if ($project['locations']): ?>
                                    <div class="col-md-6 mb-4">
                                        <h6>Key Locations</h6>
                                        <ul class="text-muted">
                                            <?php 
                                            $locations = json_decode($project['locations'], true);
                                            if (is_array($locations)) {
                                                foreach ($locations as $location) {
                                                    echo '<li>' . htmlspecialchars($location) . '</li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($project['cast_crew_requirements']): ?>
                                    <div class="col-md-6 mb-4">
                                        <h6>Crew Requirements</h6>
                                        <ul class="text-muted">
                                            <?php 
                                            $crew = json_decode($project['cast_crew_requirements'], true);
                                            if (is_array($crew)) {
                                                foreach ($crew as $member) {
                                                    echo '<li>' . htmlspecialchars($member) . '</li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($project['shooting_schedule']): ?>
                                    <div class="col-md-6 mb-4">
                                        <h6>Shooting Schedule</h6>
                                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($project['shooting_schedule'])); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <h5>Production Plan Not Generated</h5>
                                <p class="text-muted">Click "Generate" to create a comprehensive production plan including budget, locations, and schedule.</p>
                                <form method="POST">
                                    <input type="hidden" name="generate_section" value="production">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-magic me-2"></i>Generate Production Plan
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Pitch Deck Tab -->
            <div class="tab-pane fade" id="pitch" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Pitch Deck Summary</h5>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="generate_section" value="pitch_deck">
                                <button type="submit" class="btn btn-primary btn-sm"
                                        onclick="return confirm('This will regenerate the pitch deck. Continue?')">
                                    <i class="fas fa-magic me-2"></i>Generate/Regenerate
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($project['one_line_pitch']): ?>
                            <div class="mb-4">
                                <h6>One-Line Pitch</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($project['one_line_pitch'])); ?></p>
                            </div>
                            
                            <div class="mb-4">
                                <h6>Market Appeal</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($project['market_appeal'])); ?></p>
                            </div>
                            
                            <div class="mb-4">
                                <h6>Visual Style Reference</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($project['visual_style_reference'])); ?></p>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-presentation fa-3x text-muted mb-3"></i>
                                <h5>Pitch Deck Not Generated</h5>
                                <p class="text-muted">Click "Generate" to create a compelling pitch deck summary for your film.</p>
                                <form method="POST">
                                    <input type="hidden" name="generate_section" value="pitch_deck">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-magic me-2"></i>Generate Pitch Deck
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Choose export format:</p>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="exportProject(<?php echo $project['id']; ?>, 'pdf')">
                            <i class="fas fa-file-pdf me-2"></i>Export as PDF
                        </button>
                        <button class="btn btn-outline-success" onclick="exportProject(<?php echo $project['id']; ?>, 'docx')">
                            <i class="fas fa-file-word me-2"></i>Export as DOCX
                        </button>
                        <button class="btn btn-outline-info" onclick="exportProject(<?php echo $project['id']; ?>, 'txt')">
                            <i class="fas fa-file-alt me-2"></i>Export as TXT
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Smart Film Makers. All rights reserved.</p>
        </div>
    </footer>

    <!-- MDBootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    
    <style>
        .character-list {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            white-space: pre-line;
        }
    </style>
</body>
</html>
