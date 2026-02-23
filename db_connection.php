<?php
// db_connection.php for InfinityFree Hosting

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// InfinityFree MySQL credentials
$servername = "sql107.infinityfree.com";
$username   = "if0_41196114";
$password   = "AM5RlhTPgwx";
$dbname     = "if0_41196114_scs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed. Please try again later.");
}

// Set charset
$conn->set_charset("utf8mb4");
?>