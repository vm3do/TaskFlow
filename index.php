<?php
require_once 'config/database.php';
require_once 'classes/TaskManager.php';

$db = (new Database())->connect();
$taskManager = new TaskManager($db);
$tasks = $taskManager->getAllTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Task Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        main {
            padding: 2rem;
        }

        .task-list, .task-form {
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        h2 {
            color: #333;
        }

        .task {
            border-bottom: 1px solid #ddd;
            padding: 0.5rem 0;
        }

        .task:last-child {
            border-bottom: none;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-radius: 3px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        input, select {
            width: 100%;
            padding: 0.5rem;
            margin: 0.5rem 0 1rem;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        label {
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<header>
    <h1>TaskFlow - Task Management</h1>
</header>

<main>
    <section class="task-list">
        <h2>Task List</h2>
        <?php foreach ($tasks as $task): ?>
            <div class="task">
                <p><strong>Title:</strong> <?= htmlspecialchars($task['title']) ?></p>
                <p><strong>Type:</strong> <?= htmlspecialchars($task['type']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($task['status']) ?></p>
                <p><strong>Assigned To:</strong> <?= htmlspecialchars($task['assigned_user']) ?></p>
                <p><strong>Additional Info:</strong> <?= htmlspecialchars($task['additional_info']) ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="task-form">
        <h2>Create a Task</h2>
        <form action="create_task.php" method="post">
            <div class="form-group">
                <label for="task-title">Task Title:</label>
                <input type="text" id="task-title" name="task_title" required>
            </div>

            <div class="form-group">
                <label for="task-type">Task Type:</label>
                <select id="task-type" name="task_type" required>
                    <option value="">Select Type</option>
                    <option value="Simple">Simple</option>
                    <option value="Bug">Bug</option>
                    <option value="Feature">Feature</option>
                </select>
            </div>

            <div class="form-group">
                <label for="assigned-user">Assign To:</label>
                <input type="text" id="assigned-user" name="assigned_user" required>
            </div>

            <div class="form-group">
                <label for="task-status">Status:</label>
                <select id="task-status" name="task_status" required>
                    <option value="">Select Status</option>
                    <option value="To Do">To Do</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Done">Done</option>
                </select>
            </div>

            <button type="submit" class="btn">Create Task</button>
        </form>
    </section>
</main>

</body>
</html>
