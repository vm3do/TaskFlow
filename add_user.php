<?php
session_start();
require_once 'config/database.php';
require_once 'classes/TaskManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_user'])) {
    $db = (new Database())->connect();
    $taskManager = new TaskManager($db);
    
    $result = $taskManager->addUser($_POST['new_user']);
    
    header('Content-Type: application/json');
    echo json_encode($result);
    exit();
}