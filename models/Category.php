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
        $sql = "INSERT INTO categories (name, budget) VALUES ('$this->name', '$this->budget')";
        return $this->conn->query($sql);
    }

    public static function getAllCategoriesByUser($user_id) {
        /* Returns all categories for the user */
        global $servername, $dbusername, $dbpassword, $dbname;
        // Connect to the database
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT name, budget FROM categories WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $conn->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}