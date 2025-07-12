<?php
session_start();
require_once '../includes/functions.php';
require_once '../connection/db_con.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);
    $confirm_password = sanitize_input($_POST['confirm_password']);
    $role = sanitize_input($_POST['role']);
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $_SESSION['error'] = "All fields are required";
        redirect('../pages/register.php');
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        redirect('../pages/register.php');
    }

    if (!verify_recaptcha($recaptcha_response)) {
        $_SESSION['error'] = "Please complete the reCAPTCHA challenge";
        redirect('../pages/register.php');
    }

    // Check if username or email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username or email already exists";
        redirect('../pages/register.php');
    }

    // Insert new user
    $hashed_password = password_hash_secure($password);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful. Please login.";
        redirect('../pages/login.php');
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        redirect('../pages/register.php');
    }
}
?>