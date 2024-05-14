<?php
session_start();
include 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$conn = new mysqli('localhost', 'root', '', 'taskmanagementdb');

if ($conn->connect_errno) { // Use $conn instead of $db here
    echo "Error Database Connection";
    exit;
}

$query = "SELECT * FROM login
          WHERE username='{$username}'
          AND PASSWORD='{$password}'";

$result = $conn->query($query); // Use $conn here

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['login'] = true;
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    header('location: task-list.php');
} else {
    $_SESSION['Error'] = 'Incorrect username or password';
    header('location: login.php');
}
?>

