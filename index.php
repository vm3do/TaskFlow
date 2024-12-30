<?php
session_start();
require_once 'config/database.php';
require_once 'classes/TaskManager.php';

$db = (new Database())->connect();
$taskManager = new TaskManager($db);


if (isset($_GET['logout'])) {
    unset($_SESSION['current_user']);
}

if (!isset($_SESSION['current_user'])) {

    $users = $taskManager->getAllUsers();
    if (!empty($users)) {
        $_SESSION['current_user'] = $users[0]['name'];
    } else {
        $taskManager->addUser('Default User');
        $_SESSION['current_user'] = 'Default User';
    }
}

$currentUser = $_SESSION['current_user'];

$filterType = $_GET['filter'] ?? 'all';
$tasks = ($filterType === 'my_tasks' && $_SESSION['current_user']) 
    ? $taskManager->getTasksByUser($_SESSION['current_user'])
    : $taskManager->getAllTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Task Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>TaskFlow - Task Management</h1>
</header>

<div id="notification" class="notification" style="display: none;">
    <span id="notification-message"></span>
    <button onclick="closeNotification()" class="close-btn">&times;</button>
</div>

<main>

    <div class="task-list">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2>Task List</h2>
            <select onchange="window.location.href='?filter=' + this.value">
                <option value="all" <?= $filterType === 'all' ? 'selected' : '' ?>>All Tasks</option>
                <option value="my_tasks" <?= $filterType === 'my_tasks' ? 'selected' : '' ?>>My Tasks</option>
            </select>
        </div>
        
        <?php foreach ($tasks as $task): ?>
            <div class="task">
                <div class="task-header">
                    <h3><?= htmlspecialchars($task['title']) ?></h3>
                    <button class="btn-details" onclick="toggleDetails(this)">Show Details</button>
                </div>
                <div class="task-details" style="display: none;">
                    <p><strong>Type:</strong> <?= htmlspecialchars($task['type']) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($task['status']) ?></p>
                    <p><strong>Assigned To:</strong> <?= htmlspecialchars($task['assigned_user']) ?></p>
                    <p><strong>Additional Info:</strong> <?= htmlspecialchars($task['additional_info']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <section class="task-form">
        <h2>Create a Task</h2>
        <form id="taskForm" onsubmit="createTask(event)">
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
                <label for="assignment-type">Assignment:</label>
                <select id="assignment-type" onchange="toggleAssignmentField()" required>
                    <option value="self">Assign to Myself (<?= htmlspecialchars($currentUser) ?>)</option>
                    <option value="other">Assign to Someone Else</option>
                </select>
            </div>

            <div class="form-group" id="assigned-user-group">
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

<script>
async function createTask(event) {
    event.preventDefault();
    const form = document.getElementById('taskForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('create_task.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        showNotification(result.message, result.status);
        
        if (result.status === 'success') {
            
            location.reload();

            form.reset();
        }
    } catch (error) {
        showNotification('Error creating task: ' + error.message, 'error');
    }
}

function showNotification(message, type) {
    const notification = document.getElementById('notification');
    const messageElement = document.getElementById('notification-message');
    
    notification.className = 'notification ' + type;
    messageElement.textContent = message;
    notification.style.display = 'flex';
    
    setTimeout(() => {
        closeNotification();
    }, 5000);
}

function closeNotification() {
    const notification = document.getElementById('notification');
    notification.style.display = 'none';
}

function toggleDetails(button) {
    const details = button.closest('.task').querySelector('.task-details');
    const isHidden = details.style.display === 'none';
    
    details.style.display = isHidden ? 'block' : 'none';
    button.textContent = isHidden ? 'Hide Details' : 'Show Details';
}

function toggleAssignmentField() {
    const assignmentType = document.getElementById('assignment-type').value;
    const assignedUserGroup = document.getElementById('assigned-user-group');
    const assignedUserField = document.getElementById('assigned-user');
    
    if (assignmentType === 'self') {
        const currentUser = '<?php echo $_SESSION['current_user']; ?>';
        assignedUserField.value = currentUser;
        assignedUserGroup.style.display = 'none';
    } else {
        assignedUserField.value = '';
        assignedUserGroup.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleAssignmentField();
});

async function addUser(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    try {
        const response = await fetch('add_user.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        showNotification(result.message, result.status);
        
        if (result.status === 'success') {
            
            location.reload();
            form.reset();
        }
    } catch (error) {
        showNotification('Error adding user: ' + error.message, 'error');
    }
}

function switchUser(username) {
    
    fetch('switch_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'username=' + encodeURIComponent(username)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            showNotification('Error switching user', 'error');
        }
    })
    .catch(error => {
        showNotification('Error switching user: ' + error.message, 'error');
    });
}
</script>

</body>
</html>
