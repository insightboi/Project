<?php
require_once '../config.php';
requireAdminLogin();

// Fetch Stats
$stats = [
    'users' => $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0],
    'projects' => $conn->query("SELECT COUNT(*) FROM projects")->fetch_row()[0],
    'prompts' => $conn->query("SELECT COUNT(*) FROM ai_prompts")->fetch_row()[0]
];

// Fetch Recent Users
$recent_users = $conn->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Film Makers</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Stats Cards -->
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h2 class="display-4"><?php echo $stats['users']; ?></h2>
                        <i class="fas fa-users fa-2x opacity-50 position-absolute end-0 top-0 m-3"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Projects</h5>
                        <h2 class="display-4"><?php echo $stats['projects']; ?></h2>
                        <i class="fas fa-film fa-2x opacity-50 position-absolute end-0 top-0 m-3"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">AI Prompts</h5>
                        <h2 class="display-4"><?php echo $stats['prompts']; ?></h2>
                        <i class="fas fa-robot fa-2x opacity-50 position-absolute end-0 top-0 m-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Registrations</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = $recent_users->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>