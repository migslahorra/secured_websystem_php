<?php
require_once __DIR__ . '/../connection/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCsrfToken($_POST['csrf_token'])) {
        $_SESSION['error'] = "Invalid CSRF token!";
        redirect('../pages/login.php');
    }

    // Verify reCAPTCHA
    if (!verifyRecaptcha($_POST['g-recaptcha-response'])) {
        $_SESSION['error'] = "Please verify you're not a robot!";
        redirect('../pages/login.php');
    }

    // Check for lockout
    if ($lockoutMessage = isLoginLockedOut()) {
        $_SESSION['error'] = $lockoutMessage;
        redirect('../pages/login.php');
    }
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Generate new CSRF token for session
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            
            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    redirect('../pages/admin/dashboard.php');
                    break;
                case 'faculty':
                    redirect('../pages/faculty/dashboard.php');
                    break;
                case 'student':
                    redirect('../pages/student/dashboard.php');
                    break;
                default:
                    redirect('../pages/index.php');
            }
        } else {
            $_SESSION['error'] = "Invalid username or password!";
            redirect('../pages/login.php');
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        redirect('../pages/login.php');
    }
}
?>