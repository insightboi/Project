<?php
require_once 'config.php';
requireLogin();

// Get user's projects for export
$projects = getUserProjects($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Projects - Smart Film Makers</title>
    
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
                            <h1 class="display-6 fw-bold mb-3">Export Your Projects</h1>
                            <p class="lead text-muted">Download your film projects in multiple formats</p>
                        </div>

                        <!-- Export Form -->
                        <div class="mb-4">
                            <label class="form-label">Select Project</label>
                            <select class="form-select mb-3" id="projectSelect">
                                <option value="">Choose a project to export...</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?php echo $project['id']; ?>">
                                        <?php echo htmlspecialchars($project['title']); ?> 
                                        (<?php echo ucfirst($project['status']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <label class="form-label">Export Format</label>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="pdf" value="pdf" checked>
                                        <label class="form-check-label" for="pdf">
                                            <i class="fas fa-file-pdf text-danger me-2"></i>PDF
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="docx" value="docx">
                                        <label class="form-check-label" for="docx">
                                            <i class="fas fa-file-word text-primary me-2"></i>DOCX
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="txt" value="txt">
                                        <label class="form-check-label" for="txt">
                                            <i class="fas fa-file-alt text-info me-2"></i>TXT
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button class="btn btn-primary btn-lg" onclick="exportProject()">
                                    <i class="fas fa-download me-2"></i>Export Project
                                </button>
                            </div>
                        </div>

                        <!-- Export Features -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-2x text-danger mb-3"></i>
                                        <h6>PDF Format</h6>
                                        <p class="small text-muted">Professional print-ready format</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-word fa-2x text-primary mb-3"></i>
                                        <h6>DOCX Format</h6>
                                        <p class="small text-muted">Editable Word document</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-alt fa-2x text-info mb-3"></i>
                                        <h6>TXT Format</h6>
                                        <p class="small text-muted">Plain text compatibility</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Export Tips -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-lightbulb me-2"></i>Export Tips
                            </h6>
                            <ul class="mb-0">
                                <li>PDF is best for sharing and printing</li>
                                <li>DOCX allows further editing in Word</li>
                                <li>TXT works with any text editor</li>
                                <li>All formats include complete project data</li>
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
        function exportProject() {
            const projectId = document.getElementById('projectSelect').value;
            const format = document.querySelector('input[name="format"]:checked').value;
            
            if (!projectId) {
                alert('Please select a project first');
                return;
            }
            
            window.open(`api/export.php?project_id=${projectId}&format=${format}`, '_blank');
        }
    </script>
</body>
</html>
