
<?php
require './config.php';

class Database {
    private $host = DB_SERVER;
    private $username = DB_USERNAME;
    private $db_name = DB_NAME;
    private $password = DB_PASSWORD;
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_errno) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}
?>



