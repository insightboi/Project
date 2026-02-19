<?php
require_once 'config.php';
requireLogin();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $idea = sanitizeInput($_POST['idea']);
    $genre = sanitizeInput($_POST['genre']);
    $targetAudience = sanitizeInput($_POST['target_audience']);
    $language = sanitizeInput($_POST['language']);
    
    // Validation
    if (empty($title)) {
        $errors[] = 'Project title is required';
    }
    
    if (empty($idea)) {
        $errors[] = 'Film idea is required';
    }
    
    if (strlen($idea) < 50) {
        $errors[] = 'Please provide a more detailed idea (at least 50 characters)';
    }
    
    if (empty($errors)) {
        $result = createProject($_SESSION['user_id'], $title, $idea, $genre, $targetAudience, $language);
        
        if ($result['success']) {
            header('Location: project.php?id=' . $result['project_id'] . '&created=1');
            exit();
        } else {
            $errors[] = $result['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project - Smart Film Makers</title>
    
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
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold mb-3">Create New Film Project</h1>
                    <p class="lead text-muted">Share your vision and let AI bring it to life</p>
                </div>

                <!-- Progress Steps -->
                <div class="mb-5">
                    <div class="row">
                        <div class="col-4 text-center">
                            <div class="step-indicator active">
                                <div class="step-circle">1</div>
                                <p class="step-text">Idea Input</p>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="step-indicator">
                                <div class="step-circle">2</div>
                                <p class="step-text">AI Generation</p>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="step-indicator">
                                <div class="step-circle">3</div>
                                <p class="step-text">Review & Export</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="generate.php" class="needs-validation" novalidate>
                            <!-- Project Title -->
                            <div class="form-outline mb-4">
                                <input type="text" id="title" name="title" class="form-control" 
                                       value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" 
                                       required>
                                <label class="form-label" for="title">Project Title</label>
                                <div class="form-text">Give your film a working title</div>
                                <div class="invalid-feedback">
                                    Please provide a project title.
                                </div>
                            </div>

                            <!-- Film Idea -->
                            <div class="form-outline mb-4">
                                <textarea id="idea" name="idea" class="form-control" rows="6" 
                                          data-word-counter required><?php echo isset($_POST['idea']) ? htmlspecialchars($_POST['idea']) : ''; ?></textarea>
                                <label class="form-label" for="idea">Your Film Idea</label>
                                <div class="form-text">
                                    Describe your story concept, main characters, plot, and what makes it unique. 
                                    The more detail you provide, the better the AI can understand your vision.
                                </div>
                                <div class="invalid-feedback">
                                    Please provide your film idea (minimum 50 characters).
                                </div>
                            </div>

                            <!-- Genre -->
                            <div class="form-outline mb-4">
                                <select id="genre" name="genre" class="form-select">
                                    <option value="">Select Genre</option>
                                    <option value="Action" <?php echo (isset($_POST['genre']) && $_POST['genre'] === 'Action') ? 'selected' : ''; ?>>Action</option>
                                    <option value="Comedy" <?php echo (isset($_POST['genre']) && $_POST['genre'] === 'Comedy') ? 'selected' : ''; ?>>Comedy</option>
                                    <option value="Drama" <?php echo (isset($_POST['genre']) && $_POST['genre'] === 'Drama') ? 'selected' : ''; ?>>Drama</option>
                                    <option value="Horror" <?php echo (isset($_POST['genre']) && $_POST['genre'] === 'Horror') ? 'selected' : ''; ?>>Horror</option>
                                    <option value="Romance" <?php echo (isset($_POST['genre']) && $_POST['genre'] === 'Romance') ? 'selected' : ''; ?>>Romance</option>
                                    <option value="Sci-Fi" <?php echo (isset($_POST['genre']) && $_POST['genre'] === 'Sci-Fi') ? 'selected' : ''; ?>>Sci-Fi</option>
                                    <option value="Thriller" <?php echo (isset($_POST['genre']) && $_POST['genre'] === 'Thriller') ? 'selected' : ''; ?>>Thriller</option>
                                    <option value="Mystery" <?php echo (isset($_POST['genre']) && $_POST['genre'] === 'Mystery') ? 'selected' : ''; ?>>Mystery</option>
                                    <option value="Fantasy" <?php echo (isset($_POST['genre']) && $_POST['genre'] === 'Fantasy') ? 'selected' : ''; ?>>Fantasy</option>
                                    <option value="Documentary" <?php echo (isset($_POST['genre']) && $_POST['genre'] === 'Documentary') ? 'selected' : ''; ?>>Documentary</option>
                                    <option value="Animation" <?php echo (isset($_POST['genre']) && $_POST['genre'] === 'Animation') ? 'selected' : ''; ?>>Animation</option>
                                </select>
                                <label class="form-label">Genre</label>
                                <div class="form-text">Choose the primary genre of your film</div>
                            </div>

                            <!-- Target Audience -->
                            <div class="form-outline mb-4">
                                <select id="target_audience" name="target_audience" class="form-select">
                                    <option value="">Select Target Audience</option>
                                    <option value="General Audience" <?php echo (isset($_POST['target_audience']) && $_POST['target_audience'] === 'General Audience') ? 'selected' : ''; ?>>General Audience</option>
                                    <option value="Family" <?php echo (isset($_POST['target_audience']) && $_POST['target_audience'] === 'Family') ? 'selected' : ''; ?>>Family</option>
                                    <option value="Teens" <?php echo (isset($_POST['target_audience']) && $_POST['target_audience'] === 'Teens') ? 'selected' : ''; ?>>Teens</option>
                                    <option value="Young Adults" <?php echo (isset($_POST['target_audience']) && $_POST['target_audience'] === 'Young Adults') ? 'selected' : ''; ?>>Young Adults</option>
                                    <option value="Adults" <?php echo (isset($_POST['target_audience']) && $_POST['target_audience'] === 'Adults') ? 'selected' : ''; ?>>Adults</option>
                                    <option value="Children" <?php echo (isset($_POST['target_audience']) && $_POST['target_audience'] === 'Children') ? 'selected' : ''; ?>>Children</option>
                                    <option value="Art House" <?php echo (isset($_POST['target_audience']) && $_POST['target_audience'] === 'Art House') ? 'selected' : ''; ?>>Art House</option>
                                </select>
                                <label class="form-label">Target Audience</label>
                                <div class="form-text">Who is this film made for?</div>
                            </div>

                            <!-- Language -->
                            <div class="form-outline mb-4">
                                <select id="language" name="language" class="form-select">
                                    <option value="English" <?php echo (isset($_POST['language']) && $_POST['language'] === 'English') ? 'selected' : ''; ?>>English</option>
                                    <option value="Spanish" <?php echo (isset($_POST['language']) && $_POST['language'] === 'Spanish') ? 'selected' : ''; ?>>Spanish</option>
                                    <option value="French" <?php echo (isset($_POST['language']) && $_POST['language'] === 'French') ? 'selected' : ''; ?>>French</option>
                                    <option value="German" <?php echo (isset($_POST['language']) && $_POST['language'] === 'German') ? 'selected' : ''; ?>>German</option>
                                    <option value="Italian" <?php echo (isset($_POST['language']) && $_POST['language'] === 'Italian') ? 'selected' : ''; ?>>Italian</option>
                                    <option value="Portuguese" <?php echo (isset($_POST['language']) && $_POST['language'] === 'Portuguese') ? 'selected' : ''; ?>>Portuguese</option>
                                    <option value="Chinese" <?php echo (isset($_POST['language']) && $_POST['language'] === 'Chinese') ? 'selected' : ''; ?>>Chinese</option>
                                    <option value="Japanese" <?php echo (isset($_POST['language']) && $_POST['language'] === 'Japanese') ? 'selected' : ''; ?>>Japanese</option>
                                    <option value="Korean" <?php echo (isset($_POST['language']) && $_POST['language'] === 'Korean') ? 'selected' : ''; ?>>Korean</option>
                                    <option value="Hindi" <?php echo (isset($_POST['language']) && $_POST['language'] === 'Hindi') ? 'selected' : ''; ?>>Hindi</option>
                                </select>
                                <label class="form-label">Language</label>
                                <div class="form-text">Primary language of the film</div>
                            </div>

                            <!-- AI Tips -->
                            <div class="alert alert-info mb-4">
                                <h6 class="alert-heading">
                                    <i class="fas fa-lightbulb me-2"></i>AI Tips
                                </h6>
                                <ul class="mb-0">
                                    <li>Include character names and their relationships</li>
                                    <li>Describe the setting and time period</li>
                                    <li>Mention key plot points or conflicts</li>
                                    <li>Share the emotional tone you want to achieve</li>
                                </ul>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="dashboard.php" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg btn-loading">
                                    <i class="fas fa-magic me-2"></i>Generate Film Blueprint
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Example Ideas -->
                <div class="mt-5">
                    <h4 class="mb-3">Need Inspiration?</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Sci-Fi Thriller</h6>
                                    <p class="card-text small text-muted">
                                        In 2050, a detective discovers that AI assistants are secretly controlling human decisions. 
                                        She must expose the truth before becoming the next victim.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Family Comedy</h6>
                                    <p class="card-text small text-muted">
                                        A clumsy dad accidentally becomes a superhero after a lab experiment goes wrong. 
                                        He must balance saving the world with parenting his three kids.
                                    </p>
                                </div>
                            </div>
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
    
    <style>
        .step-indicator {
            position: relative;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: bold;
            border: 2px solid #e9ecef;
        }
        
        .step-indicator.active .step-circle {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .step-text {
            font-size: 0.875rem;
            margin: 0;
            color: #6c757d;
        }
        
        .step-indicator.active .step-text {
            color: var(--primary-color);
            font-weight: 600;
        }
    </style>
</body>
</html>
