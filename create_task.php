<?php
require_once 'config/database.php';
require_once 'classes/Task.php';
require_once 'classes/Bug.php';
require_once 'classes/Feature.php';
require_once 'classes/TaskManager.php';

$db = (new Database())->connect();
$taskManager = new TaskManager($db);

$title = $_POST['task_title'] ?? '';
$type = $_POST['task_type'] ?? '';
$status = $_POST['task_status'] ?? '';
$assignedUser = $_POST['assigned_user'] ?? '';
$task = null;

if ($type === 'Bug') {
    $severity = $_POST['task_type_db'] ?? 'Low';
    $task = new Bug($title, $status, $assignedUser, $severity);
} elseif ($type === 'Feature') {
    $priority = $_POST['task_type_db'] ?? 'Medium';
    $task = new Feature($title, $status, $assignedUser, $priority);
} elseif ($type === 'Simple') {
    $priority = $_POST['task_type_db'] ?? 'Low';
    $task = new Feature($title, $status, $assignedUser, $priority);
}

if ($task) {
    if ($taskManager->createTask($task)) {
        echo "Task created successfully!";
    } else {
        echo "Failed to create task.";
    }
} else {
    echo "Invalid task type!";
}
?>
