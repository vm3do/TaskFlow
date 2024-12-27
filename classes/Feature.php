<?php

require_once 'Task.php';

class Feature extends Task {
    private $priority;

    public function __construct($title, $status, $assignedUser, $priority) {
        parent::__construct($title, $status, $assignedUser);
        $this->priority = $priority;
    }

    public function getType() {
        return 'Feature';
    }

    public function getPriority() {
        return $this->priority;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
    }
}

?>