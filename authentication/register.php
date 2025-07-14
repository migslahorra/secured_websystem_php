<?php
require_once __DIR__ . '/../connection/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCsrfToken($_POST['csrf_token'])) {
        $_SESSION['error'] = "Invalid CSRF token!";
        redirect('../pages/register.php');
    }

    // Verify reCAPTCHA
    if (!verifyRecaptcha($_POST['g-recaptcha-response'])) {
        $_SESSION['error'] = "Please verify you're not a robot!";
        redirect('../pages/register.php');
    }

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'student'; // Get role from form or default to student

    // Define allowed roles for registration (exclude admin for security)
    $allowedRoles = ['student', 'faculty', 'admin'];

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required!";
        redirect('../pages/register.php');
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        redirect('../pages/register.php');
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters long!";
        redirect('../pages/register.php');
    }

    // Validate role
    if (!in_array($role, $allowedRoles)) {
        $_SESSION['error'] = "Invalid account type selected!";
        redirect('../pages/register.php');
    }

    try {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Username or email already exists!";
            redirect('../pages/register.php');
        }

        // Hash password
        $hashedPassword = hashPassword($password);

        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword, $role]);

        $_SESSION['success'] = "Registration successful! Please login.";
        redirect('../pages/login.php');
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        redirect('../pages/register.php');
    }
}
?>