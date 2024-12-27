<?php
require_once 'Task.php';

class Bug extends Task {
    private $severity;

    public function __construct($title, $status, $assignedUser, $severity) {
        parent::__construct($title, $status, $assignedUser);
        $this->severity = $severity;
    }

    public function getType() {
        return 'Bug';
    }

    public function getSeverity() {
        return $this->severity;
    }

    public function setSeverity($severity) {
        $this->severity = $severity;
    }
}

?>