<?php
// Strict session validation
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    session_unset();
    session_destroy();
    header("Location: ../../pages/login.php?error=unauthorized");
    exit();
}

// Security headers
header("Referrer-Policy: strict-origin-when-cross-origin");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Secure System</title>
    <link rel="stylesheet" href="../../styles/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="dashboard.php" class="navbar-brand">Student Portal</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="courses.php" class="nav-link">My Courses</a></li>
                <li class="nav-item"><a href="grades.php" class="nav-link">My Grades</a></li>
                <li class="nav-item"><a href="../../authentication/logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <p>Academic standing: Good</p>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3><i class="icon-schedule"></i> Class Schedule</h3>
                <div class="card-content">
                    <p>Current courses: 5</p>
                    <p>Next class: CS101 in 2 hours</p>
                </div>
                <a href="schedule.php" class="btn">View Schedule</a>
            </div>

            <div class="dashboard-card">
                <h3><i class="icon-assignment"></i> Assignments</h3>
                <div class="card-content">
                    <p>Due this week: 3</p>
                    <p>Overdue: 0</p>
                </div>
                <a href="assignments.php" class="btn">View Assignments</a>
            </div>

            <div class="dashboard-card">
                <h3><i class="icon-grades"></i> Academic Progress</h3>
                <div class="card-content">
                    <p>GPA: 3.42</p>
                    <p>Credits earned: 45</p>
                </div>
                <a href="grades.php" class="btn">View Details</a>
            </div>
        </div>
    </div>

    <script>
    // Inactivity timer (15 minutes)
    let inactivityTime = 0;
    const resetTimer = () => {
        inactivityTime = 0;
    };

    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;

    setInterval(() => {
        inactivityTime++;
        if (inactivityTime > 15) { // 15 minutes
            window.location.href = "../../authentication/logout.php?reason=inactivity";
        }
    }, 60000); // Check every minute
    </script>
</body>
</html>