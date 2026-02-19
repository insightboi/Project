<?php
require_once 'config.php';
requireLogin();

// Get user's projects
$projects = getUserProjects($_SESSION['user_id']);

// Handle project deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $projectId = (int)$_GET['delete'];
    $project = getProject($projectId, $_SESSION['user_id']);
    
    if ($project) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $projectId, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            header('Location: dashboard.php?deleted=1');
            exit();
        }
    }
}

// Handle search and filter
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';
$filteredProjects = $projects;

if ($search) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($search) {
        return stripos($project['title'], $search) !== false || 
               stripos($project['idea'], $search) !== false;
    });
}

if ($filter) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($filter) {
        return $project['status'] === $filter;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Smart Film Makers</title>
    
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
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
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
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">My Projects</h1>
                <p class="text-muted">Manage your film projects and scripts</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="create-project.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>Create New Project
                </a>
            </div>
        </div>

        <!-- Success Message -->
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                Project deleted successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo count($projects); ?></h4>
                                <p class="small mb-0">Total Projects</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-film fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo count(array_filter($projects, fn($p) => $p['status'] === 'generated')); ?></h4>
                                <p class="small mb-0">Generated</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo count(array_filter($projects, fn($p) => $p['status'] === 'draft')); ?></h4>
                                <p class="small mb-0">Drafts</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-edit fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo count(array_filter($projects, fn($p) => $p['status'] === 'exported')); ?></h4>
                                <p class="small mb-0">Exported</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-download fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-outline">
                            <input type="text" id="search-projects" class="form-control" 
                                   value="<?php echo htmlspecialchars($search); ?>" 
                                   placeholder="Search projects...">
                            <label class="form-label" for="search-projects">Search</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="filter-projects">
                            <option value="">All Status</option>
                            <option value="draft" <?php echo $filter === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="generated" <?php echo $filter === 'generated' ? 'selected' : ''; ?>>Generated</option>
                            <option value="exported" <?php echo $filter === 'exported' ? 'selected' : ''; ?>>Exported</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary w-100" onclick="clearFilters()">
                            <i class="fas fa-times me-2"></i>Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <?php if (!empty($filteredProjects)): ?>
            <div class="row g-4">
                <?php foreach ($filteredProjects as $project): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 project-card" data-status="<?php echo $project['status']; ?>">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="project-status status-<?php echo $project['status']; ?>">
                                        <?php echo ucfirst($project['status']); ?>
                                    </span>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="project.php?id=<?php echo $project['id']; ?>">
                                                    <i class="fas fa-eye me-2"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="edit-project.php?id=<?php echo $project['id']; ?>">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="duplicateProject(<?php echo $project['id']; ?>)">
                                                    <i class="fas fa-copy me-2"></i>Duplicate
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" 
                                                   href="dashboard.php?delete=<?php echo $project['id']; ?>"
                                                   onclick="return confirm('Are you sure you want to delete this project?')">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($project['title']); ?></h5>
                                <p class="card-text text-muted">
                                    <?php echo substr(htmlspecialchars($project['idea']), 0, 100); ?>...
                                </p>
                                
                                <div class="mb-3">
                                    <?php if ($project['genre']): ?>
                                        <span class="badge bg-primary me-2"><?php echo htmlspecialchars($project['genre']); ?></span>
                                    <?php endif; ?>
                                    <?php if ($project['language']): ?>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($project['language']); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo formatDate($project['created_at']); ?>
                                    </small>
                                    <div>
                                        <?php if ($project['status'] === 'generated' || $project['status'] === 'exported'): ?>
                                            <div class="btn-group" role="group">
                                                <a href="project.php?id=<?php echo $project['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-success" 
                                                        onclick="showExportOptions(<?php echo $project['id']; ?>)">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <a href="project.php?id=<?php echo $project['id']; ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-magic me-1"></i>Generate
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-film fa-4x text-muted"></i>
                </div>
                <h3 class="mb-3">No Projects Found</h3>
                <p class="text-muted mb-4">
                    <?php if ($search || $filter): ?>
                        Try adjusting your search or filter criteria.
                    <?php else: ?>
                        Start your creative journey by creating your first film project.
                    <?php endif; ?>
                </p>
                <a href="create-project.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>Create Your First Project
                </a>
            </div>
        <?php endif; ?>
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
                        <button class="btn btn-outline-primary" onclick="exportProject(currentProjectId, 'pdf')">
                            <i class="fas fa-file-pdf me-2"></i>Export as PDF
                        </button>
                        <button class="btn btn-outline-success" onclick="exportProject(currentProjectId, 'docx')">
                            <i class="fas fa-file-word me-2"></i>Export as DOCX
                        </button>
                        <button class="btn btn-outline-info" onclick="exportProject(currentProjectId, 'txt')">
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
    
    <script>
        let currentProjectId = null;
        
        function showExportOptions(projectId) {
            currentProjectId = projectId;
            const modal = new bootstrap.Modal(document.getElementById('exportModal'));
            modal.show();
        }
        
        function duplicateProject(projectId) {
            if (confirm('Create a copy of this project?')) {
                window.location.href = 'duplicate-project.php?id=' + projectId;
            }
        }
        
        function clearFilters() {
            document.getElementById('search-projects').value = '';
            document.getElementById('filter-projects').value = '';
            window.location.href = 'dashboard.php';
        }
        
        // Search and filter functionality
        document.getElementById('search-projects').addEventListener('input', function() {
            updateFilters();
        });
        
        document.getElementById('filter-projects').addEventListener('change', function() {
            updateFilters();
        });
        
        function updateFilters() {
            const search = document.getElementById('search-projects').value;
            const filter = document.getElementById('filter-projects').value;
            
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (filter) params.append('filter', filter);
            
            const url = 'dashboard.php' + (params.toString() ? '?' + params.toString() : '');
            window.location.href = url;
        }
    </script>
</body>
</html>
