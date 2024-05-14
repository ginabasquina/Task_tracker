<?php
session_start();
include 'config.php';

if (!isset($_SESSION['login'])) {
    header('location: login.php'); // Redirect to login if not logged in
}

// Initialize the logged-in user's ID
$userID = $_SESSION['user_id']; // Get the logged-in user's ID

// Initialize the base query with user filtering and order by status
$query = "SELECT * FROM tasks 
         INNER JOIN login ON tasks.user_id = login.user_id
         WHERE tasks.user_id = $userID
         ORDER BY
             CASE
                 WHEN tasks.status = 'priority' THEN 1
                 WHEN tasks.status = 'normal' THEN 2
                 WHEN tasks.status = 'done' THEN 3
                 ELSE 4
             END";

// Modify the query based on the keyword search
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = $_GET['keyword'];

    // Construct the base query
    $query = "SELECT * FROM tasks 
              INNER JOIN login ON tasks.user_id = login.user_id
              WHERE tasks.user_id = $userID";

    $randomSearch = '%' . $keyword . '%';

    // Check if the keyword is a valid month name or status
    $monthNumber = date_parse($keyword)['month'];
    if ($monthNumber !== false) {
        // Convert month number to month name
        $monthName = DateTime::createFromFormat('!m', $monthNumber)->format('F');
        $query .= " AND (description LIKE ? OR due_date LIKE ? OR MONTHNAME(due_date) LIKE ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sss", $randomSearch, $randomSearch, $monthName);
    } else {
        // If not a valid month name, check if it's a status
        $statusValues = ['normal', 'done', 'priority', 'Normal', 'Done', 'Priority', 'NORMAL', 'DONE', 'PRIORITY'];
        if (in_array($keyword, $statusValues)) {
            $query .= " AND (description LIKE ? OR due_date LIKE ? OR tasks.status = ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("sss", $randomSearch, $randomSearch, $keyword);
        } else {
            // No month name or status, bind only two parameters
            $query .= " AND (description LIKE ? OR due_date LIKE ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ss", $randomSearch, $randomSearch);
        }
    }

    // Execute the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // No keyword entered, execute the original query
    $result = $db->query($query);
}

// Delete task
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM tasks 
                    WHERE id={$id}";
        
    if ($db->query($deleteQuery)) {
        $_SESSION['msg'] = "Task successfully deleted!";
    }
       
    // Redirect back to the same page after deletion
    header('location: task-list.php');
    exit;
}

// Mark task as done
if (isset($_GET['done'])) {
    $id = $_GET['done'];
    $doneQuery = "UPDATE tasks SET status='done' WHERE id={$id}";
    
    if ($db->query($doneQuery)) {
        $_SESSION['msg'] = "Task marked as done!";
    }
    
    // Redirect back to the same page after marking as done
    header('location: task-list.php');
    exit;
}

// Count the completed and remaining tasks
$DoneTasks = 0;
$PriorityTasks = 0;

// Check for the 'msg' session message from EditForm.php and display it
if (isset($_SESSION['msg'])) {
    $updateMessage = $_SESSION['msg'];
    unset($_SESSION['msg']); // Clear the message
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="container mt-4 border rounded p-4 form-container container-content" style="margin: 0 auto;">
        <div class="p-2 rounded">
        <div class="row mb-4">
            <div class="col-md-5 d-flex align-items-left">
                <img src="style/website.gif" alt="Website GIF" style="width: 700px; height: 300px;">    
            </div>
            <div class="col-md-5 d-flex align-items-center">
                <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
            </div>
            <div class="col-md-2 text-right">
                <a href="logout.php" class="fa-solid fa-right-from-bracket"> Log Out</a>
            </div>
        </div>



        <div class="row">
            <div class="col-md-4 text-left">
                <a href="task-list.php" class="btn btn-secondary"><i class="fas fa-sync-alt"></i></a>
            </div>
            <div class="col-md-6 text-center">   
                <form id="filterForm" method="GET" action="">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="keyword" placeholder="Enter keyword">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
                    </div>
                </form>
            </div>
            <div class="col-md-2 text-center">
                <a href="Form.php" class="btn btn-success"><i class="fa-solid fa-plus"></i> Add New Task</a>
            </div>
        </div>


            <?php if (isset($updateMessage)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $updateMessage; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Description</th>
                            <th>Due</th>
                            <th class="col-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($result->num_rows > 0)  {
                            $DoneTasks = 0;
                            $PriorityTasks = 0;

                            while ($row = $result->fetch_assoc()) {
                                $statusClass = '';
                                $statusText = '';

                                if ($row['status'] === 'normal') {
                                    $statusClass = 'circle-green';
                                    $statusText = 'Normal';
                                } elseif ($row['status'] === 'priority') {
                                    $statusClass = 'circle-red';
                                    $statusText = 'Priority';
                                    $PriorityTasks++;
                                } elseif ($row['status'] === 'done') {
                                    $statusClass = 'circle-gray';
                                    $statusText = 'Done';
                                    $DoneTasks++;
                                }
                                ?>

                                <tr>
                                    <td><span class="indicator-circle <?php echo $statusClass; ?>"></span></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td><?php echo date('d-F-Y', strtotime($row['due_date'])); ?></td>
                                    <td>
                                        <?php if ($row['status'] !== 'done'): ?>
                                            <a href="task-list.php?done=<?php echo $row['id']; ?>" class="btn btn-success btn-sm"><i class="fa-solid fa-check"></i> Done</a>
                                        <?php endif; ?>
                                        <a href="task-list.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Remove</a>
                                        <a href="Form.php?edit=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-pencil"></i> Edit</a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="4">No tasks found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                
                <!-- Display statistics -->
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <div class="background-image">
                            <img src="style/calendar1.png" class="card-img" alt="Background Image">
                            <div class="card-img-overlay text-center">
                                <h5 class="card-title">DONE</h5>
                                <p class="card-text" style="font-size: 50px;"><?php echo $DoneTasks; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="background-image">
                            <img src="style/calendar1.png" class="card-img" alt="Background Image">
                            <div class="card-img-overlay text-center">
                                <h5 class="card-title">PRIORITY</h5>
                                <p class="card-text" style="font-size: 50px;"><?php echo $PriorityTasks; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>