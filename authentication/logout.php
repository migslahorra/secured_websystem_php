<?php
require_once __DIR__ . '/../includes/csrf.php';

session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: ../pages/login.php");
exit();
?>