<?php
require_once '../config.php';

if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    
    $result = loginAdmin($email, $password);
    
    if ($result['success']) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Smart Film Makers</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Admin Portal</h3>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-outline mb-4">
                                <input type="email" name="email" class="form-control" required />
                                <label class="form-label">Email address</label>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" name="password" class="form-control" required />
                                <label class="form-label">Password</label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="../index.php" class="small text-muted">Back to Main Site</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</body>
</html>