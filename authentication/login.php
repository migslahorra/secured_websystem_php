<?php
session_start();
require_once '../includes/functions.php';
require_once '../connection/db_con.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    $recaptcha_response = $_POST['g-recaptcha-response'];

    if (!verify_recaptcha($recaptcha_response)) {
        $_SESSION['error'] = "Please complete the reCAPTCHA challenge";
        redirect('../pages/login.php');
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
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
            }
        } else {
            $_SESSION['error'] = "Invalid username or password";
            redirect('../pages/login.php');
        }
    } else {
        $_SESSION['error'] = "Invalid username or password";
        redirect('../pages/login.php');
    }
}
?>