<?php
require_once __DIR__ . '/../../includes/init.php';
checkRole(['admin']);

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Secured Web System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../styles/styles.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h3>SecureSys</h3>
                <p class="text-muted small mb-0">Welcome, <?= htmlspecialchars($username) ?></p>
            </div>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li><a href="manage_users.php"><i class="bi bi-people me-2"></i> Manage Users</a></li>
                <li><a href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                <li><a href="../../authentication/logout.php"><i class="bi bi-box-arrow-left me-2"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Admin Dashboard</h1>
                <div class="text-muted"><?= date('l, F j, Y') ?></div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card stats-card bg-primary text-white">
                        <i class="bi bi-people fs-1"></i>
                        <div class="value">142</div>
                        <div class="label">Total Users</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card bg-success text-white">
                        <i class="bi bi-person-check fs-1"></i>
                        <div class="value">24</div>
                        <div class="label">Active Today</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card bg-warning text-dark">
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                        <div class="value">3</div>
                        <div class="label">Pending Issues</div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Recent Activity -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Recent Activity</span>
                            <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="activity-item">
                                <div class="d-flex justify-content-between">
                                    <strong>New user registered</strong>
                                    <small class="text-muted">10 min ago</small>
                                </div>
                                <p class="mb-0">John Doe created a new account</p>
                            </div>
                            <div class="activity-item">
                                <div class="d-flex justify-content-between">
                                    <strong>Password reset</strong>
                                    <small class="text-muted">25 min ago</small>
                                </div>
                                <p class="mb-0">Jane Smith reset her password</p>
                            </div>
                            <div class="activity-item">
                                <div class="d-flex justify-content-between">
                                    <strong>System update</strong>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                                <p class="mb-0">Security patches installed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Quick Actions
                        </div>
                        <div class="card-body">
                            <a href="manage_users.php" class="btn btn-primary w-100 mb-2">
                                <i class="bi bi-plus-circle me-2"></i> Add User
                            </a>
                            <a href="#" class="btn btn-outline-secondary w-100 mb-2">
                                <i class="bi bi-envelope me-2"></i> Send Notification
                            </a>
                            <a href="#" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-file-earmark-text me-2"></i> Generate Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>