<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>
<div class="container-fluid container-content" style="max-width: 95%;">
        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-md-6">
                <div style="text-align: center; margin-top: 0 auto">
                    <img src="style/animation1.gif" alt="Task Management System GIF" style="max-width: 90%; height: auto; vertical-align: middle;">
                </div>
            </div>
            <div class="col-md-6">
                <h1 style="font-size: 40px; text-align: center; margin-bottom: 40px; font-family: 'Cursive', 'Poppins', sans-serif;">To-Do List Web Application</h1>
                <form method="post" action="signup-process.php" class="border p-4 rounded form-container">
                <h2 class="text-center mb-4">Create New Account</h2>

            <?php
            session_start();

            if (isset($_SESSION['Error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['Error'] . '</div>';
                unset($_SESSION['Error']);
            }
            ?>
            <!-- Your form fields for registration -->
            <div class="form-group">
                <label for="exampleInputUsername">Create Username</label>
                <input type="text" name="username" class="form-control" id="exampleInputUsername" required>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Create Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
            </div>

            
            <!-- Add more form fields as needed -->
            <div class="form-group row text-center">
                <div class="col-sm-4 offset-sm-2 mb-2 mb-sm-0">
                    <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                </div>
                <div class="col-sm-4">
                    <button type="reset" class="btn btn-secondary btn-block">Reset</button>
                 </div>
            </div>

            <div class="form-group text-center">
                <a href="login.php" class="btn btn-link">Already have an account? Log in</a>
            </div>
        </form>
    </div>
</body>
</html>
