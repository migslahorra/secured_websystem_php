<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'secured_websystem';  // Using your exact database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// No table creation code since you already have the table
?>