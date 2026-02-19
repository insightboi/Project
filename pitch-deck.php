<?php
require_once 'config.php';
requireLogin();

// Get user's projects for pitch deck creation
$projects = getUserProjects($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pitch Deck Creation - Smart Film Makers</title>
    
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
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="display-6 fw-bold mb-3">AI Pitch Deck Creation</h1>
                            <p class="lead text-muted">Generate professional pitch decks to showcase your film</p>
                        </div>

                        <!-- Pitch Deck Form -->
                        <div class="mb-4">
                            <label class="form-label">Select Project</label>
                            <select class="form-select mb-3" id="projectSelect">
                                <option value="">Choose a project to create pitch deck for...</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?php echo $project['id']; ?>">
                                        <?php echo htmlspecialchars($project['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <div class="text-center">
                                <button class="btn btn-primary btn-lg" onclick="generatePitchDeck()">
                                    <i class="fas fa-presentation me-2"></i>Generate Pitch Deck
                                </button>
                            </div>
                        </div>

                        <!-- Pitch Deck Components -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-bullhorn fa-2x text-primary mb-3"></i>
                                        <h6>One-Line Pitch</h6>
                                        <p class="small text-muted">Compelling elevator pitch</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-line fa-2x text-success mb-3"></i>
                                        <h6>Market Analysis</h6>
                                        <p class="small text-muted">Target audience insights</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-palette fa-2x text-warning mb-3"></i>
                                        <h6>Visual Style</h6>
                                        <p class="small text-muted">Aesthetic references</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-dollar-sign fa-2x text-info mb-3"></i>
                                        <h6>Financial Projections</h6>
                                        <p class="small text-muted">Box office potential</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pitch Deck Tips -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-lightbulb me-2"></i>Pitch Deck Tips
                            </h6>
                            <ul class="mb-0">
                                <li>Focus on the unique selling proposition</li>
                                <li>Know your target market demographics</li>
                                <li>Include visual style references</li>
                                <li>Keep it concise and compelling</li>
                            </ul>
                        </div>

                        <!-- Back to Dashboard -->
                        <div class="text-center mt-4">
                            <a href="dashboard.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                        </div>
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
    
    <script>
        function generatePitchDeck() {
            const projectId = document.getElementById('projectSelect').value;
            
            if (!projectId) {
                alert('Please select a project first');
                return;
            }
            
            window.location.href = `project.php?id=${projectId}&generate=pitch_deck`;
        }
    </script>
</body>
</html>
