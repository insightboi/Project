<?php
require_once 'config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$token = $_GET['token'] ?? '';
$errors = [];
$success = '';

// Verify token
if (empty($token)) {
    $errors[] = 'Invalid reset link';
} else {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $errors[] = 'Invalid or expired reset link';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
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
        // Update password and clear token
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashed_password, $token);
        
        if ($stmt->execute()) {
            $success = 'Password has been reset successfully. You can now login with your new password.';
            // Redirect to login after 3 seconds
            header('refresh:3;url=login.php');
        } else {
            $errors[] = 'Failed to reset password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Smart Film Makers</title>
    
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
                            <div class="mb-3">
                                <i class="fas fa-key fa-3x text-primary"></i>
                            </div>
                            <h2 class="display-6 text-gradient">Reset Password</h2>
                            <p class="text-muted">Enter your new password</p>
                        </div>

                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <div class="text-center mt-4">
                                <a href="login.php" class="btn btn-primary">Go to Login</a>
                            </div>
                        <?php else: ?>
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

                            <?php if (empty($errors)): ?>
                                <form method="POST" class="needs-validation" novalidate>
                                    <div class="form-outline mb-4">
                                        <input type="password" id="password" name="password" class="form-control" 
                                               required minlength="8">
                                        <label class="form-label" for="password">New Password</label>
                                        <div class="form-text">Minimum 8 characters</div>
                                        <div class="invalid-feedback">
                                            Password must be at least 8 characters.
                                        </div>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="confirm_password" name="confirm_password" 
                                               class="form-control" required>
                                        <label class="form-label" for="confirm_password">Confirm New Password</label>
                                        <div class="invalid-feedback">
                                            Please confirm your new password.
                                        </div>
                                    </div>

                                    <!-- Password Strength Indicator -->
                                    <div class="mb-4">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" id="password-strength" role="progressbar" 
                                                 style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="strength-text">Password strength</small>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                        Reset Password
                                    </button>

                                    <div class="text-center">
                                        <p class="mb-0">Remember your password? 
                                            <a href="login.php" class="text-decoration-none">Login here</a>
                                        </p>
                                    </div>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
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
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let strengthText = '';
            
            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]+/)) strength += 25;
            if (password.match(/[A-Z]+/)) strength += 25;
            if (password.match(/[0-9]+/)) strength += 25;
            
            const strengthBar = document.getElementById('password-strength');
            const strengthTextEl = document.getElementById('strength-text');
            
            strengthBar.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText = 'Weak password';
            } else if (strength <= 50) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText = 'Fair password';
            } else if (strength <= 75) {
                strengthBar.className = 'progress-bar bg-info';
                strengthText = 'Good password';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText = 'Strong password';
            }
            
            strengthTextEl.textContent = strengthText;
        });
    </script>
</body>
</html>
