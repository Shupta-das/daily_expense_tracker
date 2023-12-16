<?php
require_once('database/config.php');

class Category {
    public $user_id;
    public $name;
    public $budget;
    private $conn;

    public function __construct($user_id, $name, $budget) {
        global $servername, $dbusername, $dbpassword, $dbname;
        $this->conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        $this->user_id = $user_id;
        $this->name = $name;
        $this->budget = $budget;
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function save() {
        /* Inserts the category into the database */
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        // Save the values to the database
        $sql = "INSERT INTO categories (user_id, name, budget) VALUES ('$this->user_id', '$this->name', '$this->budget')";
        return $this->conn->query($sql);
    }

    public static function getCategoriesByUser($user_id) {
        /* Returns an array of categories for the given user */
        global $servername, $dbusername, $dbpassword, $dbname;
        
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        $sql = "SELECT * FROM categories WHERE user_id = '$user_id'";
        $result = $conn->query($sql);
        $conn->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}