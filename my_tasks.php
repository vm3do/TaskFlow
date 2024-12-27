<?php
require_once 'classes/Database.php';
require_once 'classes/Task.php';
require_once 'classes/SimpleTask.php';
require_once 'classes/BugTask.php';
require_once 'classes/FeatureTask.php';
require_once 'classes/TaskManager.php';

// TODO: Replace with actual user ID from session
$currentUserId = 1; // Temporary hardcoded user ID

$taskManager = new TaskManager();
$tasks = $taskManager->getTasksByUser($currentUserId);
