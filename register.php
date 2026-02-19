<?php
require_once 'config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    
    if (empty($errors)) {
        $result = registerUser($name, $email, $password);
        
        if ($result['success']) {
            $success = $result['message'];
            // Redirect to login after 2 seconds
            header('refresh:2;url=login.php');
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
    <title>Register - Smart Film Makers</title>
    
    <!-- MDBootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                🎬 Smart Film Makers
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Home</a>
                <a class="nav-link" href="login.php">Login</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="display-6 text-gradient">Create Account</h2>
                            <p class="text-muted">Join thousands of filmmakers using AI</p>
                        </div>

                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

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

                        <form method="POST" class="needs-validation" novalidate>
                            <div class="form-outline mb-4">
                                <input type="text" id="name" name="name" class="form-control" 
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                       required>
                                <label class="form-label" for="name">Full Name</label>
                                <div class="invalid-feedback">
                                    Please provide your name.
                                </div>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="email" id="email" name="email" class="form-control" 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                       required>
                                <label class="form-label" for="email">Email Address</label>
                                <div class="invalid-feedback">
                                    Please provide a valid email.
                                </div>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" id="password" name="password" class="form-control" 
                                       required minlength="8">
                                <label class="form-label" for="password">Password</label>
                                <div class="form-text">Minimum 8 characters</div>
                                <div class="invalid-feedback">
                                    Password must be at least 8 characters.
                                </div>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" id="confirm_password" name="confirm_password" 
                                       class="form-control" required>
                                <label class="form-label" for="confirm_password">Confirm Password</label>
                                <div class="invalid-feedback">
                                    Please confirm your password.
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                </label>
                                <div class="invalid-feedback">
                                    You must agree to the terms.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                Create Account
                            </button>

                            <div class="text-center">
                                <p class="mb-0">Already have an account? 
                                    <a href="login.php" class="text-decoration-none">Login here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Acceptance of Terms</h6>
                    <p>By creating an account with Smart Film Makers, you agree to these terms and conditions.</p>
                    
                    <h6>2. Account Responsibilities</h6>
                    <p>You are responsible for maintaining the confidentiality of your account credentials.</p>
                    
                    <h6>3. Content Ownership</h6>
                    <p>You retain ownership of all content you create using our platform.</p>
                    
                    <h6>4. AI Usage</h6>
                    <p>Our AI-generated content is provided as-is and should be reviewed before use.</p>
                    
                    <h6>5. Privacy</h6>
                    <p>We respect your privacy and protect your data according to our privacy policy.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
</body>
</html>
