<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $_SESSION['current_user'] = $_POST['username'];
    echo json_encode(['status' => 'success']);
    exit();
} 