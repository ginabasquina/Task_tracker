<?php
// Database configuration
$hostname = "localhost";    // Replace with your database host
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "taskmanagementdb"; // Replace with your database name

// Create a MySQLi object to establish a connection (OOP)
$db = new mysqli($hostname, $username, $password, $database);

// Check if the connection was successful
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Set the character set to handle special characters properly
$db->set_charset("utf8mb4");
?>
