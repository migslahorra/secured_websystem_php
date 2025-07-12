<?php
// Strict session validation
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
    session_unset();
    session_destroy();
    header("Location: ../../pages/login.php?error=unauthorized");
    exit();
}

// Security headers
header("Content-Security-Policy: default-src 'self'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard | Secure System</title>
    <link rel="stylesheet" href="../../styles/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="dashboard.php" class="navbar-brand">Faculty Portal</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="courses.php" class="nav-link">My Courses</a></li>
                <li class="nav-item"><a href="grades.php" class="nav-link">Gradebook</a></li>
                <li class="nav-item"><a href="../../authentication/logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-header">
            <h1>Welcome, Professor <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <p>Teaching schedule: Fall 2023</p>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3><i class="icon-book"></i> My Courses</h3>
                <div class="card-content">
                    <p>Active courses: 3</p>
                    <p>Students enrolled: 142</p>
                </div>
                <a href="courses.php" class="btn">View Courses</a>
            </div>

            <div class="dashboard-card">
                <h3><i class="icon-assignment"></i> Pending Assignments</h3>
                <div class="card-content">
                    <p>To grade: 87</p>
                    <p>Overdue: 5</p>
                </div>
                <a href="grades.php" class="btn">Grade Assignments</a>
            </div>

            <div class="dashboard-card">
                <h3><i class="icon-announcement"></i> Announcements</h3>
                <div class="card-content">
                    <p>New: 2 announcements</p>
                    <p>Department updates</p>
                </div>
                <a href="announcements.php" class="btn">View All</a>
            </div>
        </div>
    </div>
</body>
</html>