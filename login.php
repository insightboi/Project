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
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    
    // Validation
    if (empty($email)) {
        $errors[] = 'Email is required';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    
    if (empty($errors)) {
        $result = loginUser($email, $password);
        
        if ($result['success']) {
            $success = $result['message'];
            header('Location: dashboard.php');
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
    <title>Login - Smart Film Makers</title>
    
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
                <a class="nav-link" href="register.php">Register</a>
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
                            <h2 class="display-6 text-gradient">Welcome Back</h2>
                            <p class="text-muted">Login to your film making journey</p>
                        </div>

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
                                <input type="email" id="email" name="email" class="form-control" 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                       required>
                                <label class="form-label" for="email">Email Address</label>
                                <div class="invalid-feedback">
                                    Please provide your email.
                                </div>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" id="password" name="password" class="form-control" required>
                                <label class="form-label" for="password">Password</label>
                                <div class="invalid-feedback">
                                    Please provide your password.
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="forgot-password.php" class="text-decoration-none">Forgot Password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                Login
                            </button>

                            <div class="text-center">
                                <p class="mb-0">Don't have an account? 
                                    <a href="register.php" class="text-decoration-none">Register here</a>
                                </p>
                            </div>
                        </form>

                        <!-- Social Login Options -->
                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center mx-3 mb-0">OR</p>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" type="button">
                                <i class="fab fa-google me-2"></i>Continue with Google
                            </button>
                            <button class="btn btn-outline-dark" type="button">
                                <i class="fab fa-github me-2"></i>Continue with GitHub
                            </button>
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
</body>
</html>
