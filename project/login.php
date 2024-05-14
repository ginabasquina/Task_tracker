<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <?php
        session_start();

        if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
            // User is already logged in and visited the login page
            header('location: task-list.php');
        }
    ?>

    <div class="container-fluid container-content" style="max-width: 95%;">
        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-md-6">
                <div style="text-align: center; margin-top: 0 auto">
                    <img src="style/animation2.gif" alt="Task Management System GIF" style="max-width: 90%; height: auto; vertical-align: middle;">
                </div>
            </div>
            <div class="col-md-6">
                <h1 style="font-size: 40px; text-align: center; margin-bottom: 40px; font-family: 'Cursive', 'Poppins', sans-serif;">To-Do List Web Application</h1>
                <form method="post" action="login-process.php" class="border p-4 rounded form-container">
                    <h2 class="text-center mb-4">Login Account</h2>
                    <?php
                        if(isset($_SESSION['Error'])){
                            echo '<div class="alert alert-danger mb-4">' . $_SESSION['Error'] . '</div>';
                            unset($_SESSION['Error']);
                        }
                        if (isset($_SESSION['Success'])) {
                            echo '<div class="alert alert-success">' . $_SESSION['Success'] . '</div>';
                            unset($_SESSION['Success']);
                        }
                    ?>

                    <div class="form-group">
                        <label for="exampleInputUsername">Enter Username</label>
                        <input type="text" name="username" class="form-control" id="exampleInputUsername" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Enter Password</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
                    </div>

                    <div class="form-group row text-center">
                        <div class="col-sm-4 offset-sm-2 mb-2 mb-sm-0">
                            <button type="submit" class="btn btn-primary btn-block">Log In</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="reset" class="btn btn-secondary btn-block">Reset</button>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <a href="signup.php" class="btn btn-link">Create New Account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>




