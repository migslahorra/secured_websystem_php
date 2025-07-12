<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Secure Web System</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="#" class="navbar-brand">Secure Web System</a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="login.php" class="nav-link">Login</a>
                </li>
                <li class="nav-item">
                    <a href="register.php" class="nav-link">Register</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card" style="text-align: center; padding: 50px 20px; margin: 50px auto;">
            <h1 style="font-size: 2.5rem; margin-bottom: 20px;">Welcome to Secure Web System</h1>
            <p style="font-size: 1.2rem; margin-bottom: 30px;">
                A secure platform for administrators, faculty, and students with reCAPTCHA protection.
            </p>
            <div style="display: flex; justify-content: center; gap: 20px;">
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn btn-danger">Register</a>
            </div>
        </div>
    </div>
</body>
</html>