<?php
require_once 'config.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($errors)) {
        // Check if email exists in database
        global $conn;
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Save token to database
            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
            $stmt->bind_param("sss", $token, $expiry, $email);
            
            if ($stmt->execute()) {
                // Send reset email (in production, use actual email service)
                $reset_link = SITE_URL . "/reset-password.php?token=" . $token;
                
                // For demo purposes, we'll just show success
                $success = "Password reset link has been sent to your email address.";
                
                // In production, you would send an actual email:
                // mail($email, "Password Reset", "Click here to reset your password: $reset_link");
            } else {
                $errors[] = 'Failed to generate reset link';
            }
        } else {
            // Don't reveal if email exists or not for security
            $success = "If an account with that email exists, a password reset link has been sent.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Smart Film Makers</title>
    
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
                                <i class="fas fa-lock fa-3x text-primary"></i>
                            </div>
                            <h2 class="display-6 text-gradient">Forgot Password?</h2>
                            <p class="text-muted">Enter your email address and we'll send you a link to reset your password</p>
                        </div>

                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <div class="text-center mt-4">
                                <a href="login.php" class="btn btn-primary">Return to Login</a>
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

                            <form method="POST" class="needs-validation" novalidate>
                                <div class="form-outline mb-4">
                                    <input type="email" id="email" name="email" class="form-control" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                           required>
                                    <label class="form-label" for="email">Email Address</label>
                                    <div class="invalid-feedback">
                                        Please provide your email address.
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                    Send Reset Link
                                </button>

                                <div class="text-center">
                                    <p class="mb-0">Remember your password? 
                                        <a href="login.php" class="text-decoration-none">Login here</a>
                                    </p>
                                </div>
                            </form>
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
</body>
</html>
