<?php

  class Connect {

    protected $conn;

    function __construct() {
      $this->conn = new mysqli('202.166.198.46', 'learner', 'learner', 'db_ewallet');
      if($this->conn->connect_error) {
          die('Connect Error (' . $conn->connect_errno . ') '
            . $conn->connect_error);
      }
    }

    public function getConnection() {
      return $this->conn;
    }

  }

?>