<?php

require_once 'Task.php';
require_once 'Bug.php';
require_once 'Feature.php';

class TaskManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createTask($task) {
        $sql = "INSERT INTO tasks (title, type, status, assigned_user, additional_info) 
                VALUES (:title, :type, :status, :assigned_user, :additional_info)";
        $stmt = $this->conn->prepare($sql);
    
        $title = $task->getTitle();
        $type = $task->getType();
        $status = $task->getStatus();
        $assigned_user = $task->getAssignedUser();
        $additional_info = '';
    
        if ($type === 'Bug') {
            $additional_info = $task->getSeverity();
        } elseif ($type === 'Feature') {
            $additional_info = $task->getPriority();
        }
    
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':assigned_user', $assigned_user);
        $stmt->bindParam(':additional_info', $additional_info);
    
        return $stmt->execute();
    }

    
    public function getAllTasks() {
        $sql = "SELECT * FROM tasks ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTasksByUser($username) {
        $sql = "SELECT * FROM tasks WHERE assigned_user = :username ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM users ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUser($name) {
        $sql = "INSERT INTO users (name) VALUES (:name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        
        try {
            $stmt->execute();
            return ['status' => 'success', 'message' => 'User added successfully'];
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'User already exists'];
            }
            return ['status' => 'error', 'message' => 'Error adding user'];
        }
    }

}
