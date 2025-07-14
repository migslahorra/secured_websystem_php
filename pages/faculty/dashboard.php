<?php
require_once __DIR__ . '/../../includes/init.php';
checkRole(['faculty']);

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - Secured Web System</title>
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
                <li><a href="#"><i class="bi bi-journal-text me-2"></i> Courses</a></li>
                <li><a href="#"><i class="bi bi-people me-2"></i> Students</a></li>
                <li><a href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                <li><a href="../../authentication/logout.php"><i class="bi bi-box-arrow-left me-2"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Faculty Dashboard</h1>
                <div class="text-muted"><?= date('l, F j, Y') ?></div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card stats-card bg-primary text-white">
                        <i class="bi bi-journal-text fs-1"></i>
                        <div class="value">5</div>
                        <div class="label">My Courses</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card bg-success text-white">
                        <i class="bi bi-people fs-1"></i>
                        <div class="value">142</div>
                        <div class="label">Total Students</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card bg-warning text-dark">
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                        <div class="value">3</div>
                        <div class="label">Pending Grades</div>
                    </div>
                </div>
            </div>

            <!-- Faculty-specific content here -->
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>