<?php
require_once __DIR__ . '/../connection/db_connect.php';

// Password hashing function
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Generate random string for CSRF token
function generateRandomString($length = 32) {
    return bin2hex(random_bytes($length));
}

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check user role
function checkRole($allowedRoles) {
    if (!isLoggedIn() || !in_array($_SESSION['role'], $allowedRoles)) {
        $_SESSION['error'] = "Unauthorized access!";
        redirect('../pages/login.php');
    }
}

// Verify Google reCAPTCHA
function verifyRecaptcha($response) {
    $secretKey = '6LdyaYArAAAAAI7QvLHVoN6tANXiby9voMYR546H'; // Replace with your actual secret key
    
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $response
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $responseKeys = json_decode($result, true);

    return $responseKeys["success"];
}

// Safe session start
function safeSessionStart() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function handleFailedLoginAttempt() {
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 1;
        $_SESSION['first_failed_attempt'] = time();
    } else {
        $_SESSION['login_attempts']++;
    }
    
    // Lock for 15 seconds after 3 attempts
    if ($_SESSION['login_attempts'] >= 3) {
        $_SESSION['login_locked'] = time() + 15;
    }
}

function isLoginLockedOut() {
    if (isset($_SESSION['login_locked'])) {
        if (time() < $_SESSION['login_locked']) {
            $timeLeft = $_SESSION['login_locked'] - time();
            return "Too many failed attempts. Please try again in $timeLeft seconds.";
        }
        // Lock expired - reset
        unset($_SESSION['login_locked']);
        unset($_SESSION['login_attempts']);
        unset($_SESSION['first_failed_attempt']);
    }
    return false;
}

function clearLoginAttempts() {
    unset($_SESSION['login_attempts']);
    unset($_SESSION['first_failed_attempt']);
    unset($_SESSION['login_locked']);
}

?>