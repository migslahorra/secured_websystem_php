<?php
// Strict session validation
session_start();

// Verify authentication and role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    session_unset();
    session_destroy();
    header("Location: ../../pages/login.php?error=unauthorized");
    exit();
}

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Secure System</title>
    <link rel="stylesheet" href="../../styles/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="dashboard.php" class="navbar-brand">Admin Portal</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="manage_users.php" class="nav-link">User Management</a></li>
                <li class="nav-item"><a href="settings.php" class="nav-link">System Settings</a></li>
                <li class="nav-item"><a href="../../authentication/logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <p>Last login: <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3><i class="icon-users"></i> User Accounts</h3>
                <div class="card-content">
                    <p>Total users: 42</p>
                    <p>Active today: 15</p>
                </div>
                <a href="users.php" class="btn">Manage Users</a>
            </div>

            <div class="dashboard-card">
                <h3><i class="icon-settings"></i> System Health</h3>
                <div class="card-content">
                    <p>Storage: 65% used</p>
                    <p>Last backup: 12 hours ago</p>
                </div>
                <a href="settings.php" class="btn">View Details</a>
            </div>

            <div class="dashboard-card">
                <h3><i class="icon-alert"></i> Security Alerts</h3>
                <div class="card-content">
                    <p>Failed logins: 3</p>
                    <p>New warnings: 1</p>
                </div>
                <a href="security.php" class="btn btn-warning">Review</a>
            </div>
        </div>
    </div>

    <script>
    // Session timeout warning
    let timeoutMinutes = 15;
    setTimeout(() => {
        alert('Your session will expire soon. Please save your work.');
    }, (timeoutMinutes - 1) * 60 * 1000);
    </script>
</body>
</html>