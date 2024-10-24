<?php

class DB {
    public $connection;
    
    public function __construct() {
        $db_host = "127.0.0.1";
        $db_name = "example_database";
        $db_user = "example_user";
        $db_pass = "Aprilia";
        
        $this->connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function __destruct() {
        $this->connection = null;
    }
}
?>

