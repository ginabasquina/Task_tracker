<?php
session_start();
include 'config.php';

if (!isset($_SESSION['login'])) {
    header('location: login.php'); // Redirect to login if not logged in
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    if ($id) {
        $query = "UPDATE tasks SET description=?, due_date=?, status=? WHERE id=?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sssi", $description, $due_date, $status, $id);
    } else {
        $query = "INSERT INTO tasks (user_id, description, due_date, status) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ssss", $_SESSION['user_id'], $description, $due_date, $status);
    }

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Task " . ($id ? "updated" : "added") . " successfully!";
        header('location: task-list.php');
        exit;
    } else {
        $_SESSION['msg'] = "Error " . ($id ? "updating" : "adding") . " task: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM tasks WHERE id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();
    
    $stmt->close();
}

// Determine the page title and GIF based on whether you're adding or editing a task
$pageTitle = isset($task) ? 'Edit Task' : 'Add Task';
$gifSource = isset($task) ? 'style/animation3.gif' : 'style/animation4.gif';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 60%;">
        <div class="border p-4 rounded mt-4 form-container">
            <h1 class="text-center"><?php echo $pageTitle; ?></h1>
        
            <?php
            if (isset($_SESSION['msg'])) {
            ?>
                <div class="alert alert-info" role="alert">
                    <?php 
                    echo $_SESSION['msg']; 
                    unset($_SESSION['msg']); 
                    ?>
                </div>
            <?php
            }
            ?>

            <div class="row">
                <div class="col-md-6">
                    <form method="post">
                        <?php if (isset($task)): ?>
                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" name="description" class="form-control" id="description" value="<?php echo isset($task) ? $task['description'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" name="due_date" class="form-control" id="due_date" value="<?php echo isset($task) ? $task['due_date'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="normal" <?php if (isset($task) && $task['status'] === 'normal') echo 'selected'; ?>>Normal</option>
                                <option value="priority" <?php if (isset($task) && $task['status'] === 'priority') echo 'selected'; ?>>Priority</option>
                            </select>
                        </div>

                        <div class="form-group row text-center">
                            <div class="col-sm-4 offset-sm-2 mb-2 mb-sm-0">
                                <button type="submit" class="btn btn-primary btn-block"><?php echo isset($task) ? 'Update' : 'Add'; ?> Task</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="reset" class="btn btn-secondary btn-block">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div style="text-align: center;">
                        <img src="<?php echo $gifSource; ?>" alt="Task GIF" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


