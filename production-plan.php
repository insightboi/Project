<?php
require_once 'config.php';
requireLogin();

// Get user's projects for production planning
$projects = getUserProjects($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Planning - Smart Film Makers</title>
    
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
                            <h1 class="display-6 fw-bold mb-3">AI Production Planning</h1>
                            <p class="lead text-muted">Generate comprehensive production plans with budgets, locations, and schedules</p>
                        </div>

                        <!-- Production Planning Form -->
                        <div class="mb-4">
                            <label class="form-label">Select Project</label>
                            <select class="form-select mb-3" id="projectSelect">
                                <option value="">Choose a project to create production plan for...</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?php echo $project['id']; ?>">
                                        <?php echo htmlspecialchars($project['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <div class="text-center">
                                <button class="btn btn-primary btn-lg" onclick="generateProductionPlan()">
                                    <i class="fas fa-clipboard-list me-2"></i>Generate Production Plan
                                </button>
                            </div>
                        </div>

                        <!-- Production Planning Features -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-dollar-sign fa-2x text-primary mb-3"></i>
                                        <h6>Budget Estimation</h6>
                                        <p class="small text-muted">Detailed budget breakdown by category</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-map-marked-alt fa-2x text-success mb-3"></i>
                                        <h6>Location Scouting</h6>
                                        <p class="small text-muted">Find perfect filming locations</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-users-cog fa-2x text-info mb-3"></i>
                                        <h6>Crew Requirements</h6>
                                        <p class="small text-muted">Define cast and crew needs</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-calendar-alt fa-2x text-warning mb-3"></i>
                                        <h6>Shooting Schedule</h6>
                                        <p class="small text-muted">Timeline and milestones</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Production Tips -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-lightbulb me-2"></i>Production Planning Tips
                            </h6>
                            <ul class="mb-0">
                                <li>Consider your budget constraints early</li>
                                <li>Research locations thoroughly</li>
                                <li>Plan for weather contingencies</li>
                                <li>Build buffer time into schedule</li>
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
        function generateProductionPlan() {
            const projectId = document.getElementById('projectSelect').value;
            
            if (!projectId) {
                alert('Please select a project first');
                return;
            }
            
            window.location.href = `project.php?id=${projectId}&generate=production`;
        }
    </script>
</body>
</html>
