<?php
abstract class Task {
    protected $id;
    protected $title;
    protected $status;
    protected $assignedUser;

    public function __construct($title, $status, $assignedUser) {
        $this->title = $title;
        $this->status = $status;
        $this->assignedUser = $assignedUser;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getAssignedUser() {
        return $this->assignedUser;
    }

    // Setters
    public function setTitle($title) {
        $this->title = $title;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setAssignedUser($assignedUser) {
        $this->assignedUser = $assignedUser;
    }

    abstract public function getType();
}
?>
