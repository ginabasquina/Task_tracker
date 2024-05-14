<?php
session_start();
include 'config.php'; // Include your database configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username already exists
    $check_query = "SELECT * FROM login WHERE username='$username'";
    $check_result = $db->query($check_query);

    if ($check_result->num_rows > 0) {
        $_SESSION['Error'] = 'Username already taken.';
        header('location: signup.php'); // Redirect back to the sign-up form
    } else {
        // Insert the new user into the database
        $insert_query = "INSERT INTO login (username, password) VALUES ('$username', '$password')";
        
        if ($db->query($insert_query)) {
            $_SESSION['Success'] = 'Account created successfully! You can now log in.';
            header('location: login.php'); // Redirect to the login form
        } else {
            $_SESSION['Error'] = 'Failed to create account. Please try again.';
            header('location: signup.php'); // Redirect back to the sign-up form
        }
    }
}
?>

