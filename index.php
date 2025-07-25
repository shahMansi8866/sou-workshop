<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to the database
$conn = new mysqli("localhost", "root", "", "to-do list");
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST["add_task"])) {
    $task = $_POST["task"];
    if (!empty($task)) {
        $task = $conn->real_escape_string($task); // prevent SQL injection
        $sql = "INSERT INTO task (TASK) VALUES ('$task')";
        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Fetch tasks from the database
$sql = "SELECT TASK FROM task";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>To-Do list</title>
  <style>
    body {
      font-family: 'Times New Roman', Times, serif;
      background-color: #87e3db;
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .container {
      padding: 120px;
      background-color: aliceblue;
      border-radius: 20px;
      border-style: solid;
    }
    h1 {
      text-align: center;
      font-size: 50px;
      position: relative;
      top: -90px;
      font-weight: 200;
      font-family: monospace;
    }
    input[type="text"] {
      padding: 10px;
      text-align: center;
      position: relative;
      top: -60px;
      left: -30px;
      width: 200px;
    }
    .plus-button {
      position: relative;
      top: -55px;
      margin-left: 10px;
      background-color: #28a745;
      color: white;
      font-size: 24px;
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      cursor: pointer;
      line-height: 0;
    }
    .task-list {
      margin-top: 20px;
      position: relative;
      top: -50px;
    }
    .task-list ul {
      list-style-type: disc;
      padding-left: 20px;
    }
    .remove-btn {
      margin-left: 12px;
      background-color: #ff4d4d;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 2px 8px;
      cursor: pointer;
    }
  </style>
</head>
<body>
<div class="container">
    <h1>To-Do list</h1>
    <form id="taskForm" action="index.php" method="post"> 
        <input type="text" name="task" placeholder="Enter New Task:" id="taskInput">
        <button type="submit" name="add_task" class="plus-button">+</button>
    </form>
    <div class="task-list">
        <ul id="taskList">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <li>
                    <?php echo htmlspecialchars($row['TASK']); ?>
                    <button class="remove-btn" onclick="removeTask(event)">Remove</button>
                </li>
            <?php endwhile; ?>
        <?php endif; ?>
        </ul>
    </div>
</div>

<script>
// JS to validate and then submit form
document.getElementById('taskForm').addEventListener('submit', function(e) {
    var input = document.getElementById('taskInput');
    var task = input.value.trim();
    if (task === "") {
        e.preventDefault();
        alert("Please enter a task.");
    }
});

// Optional: remove from frontend only
function removeTask(event) {
    var li = event.target.closest('li');
    if (li) li.remove();
}
</script>
</body>
</html>
