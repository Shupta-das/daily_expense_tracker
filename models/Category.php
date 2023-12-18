<?php
require_once('database/config.php');

class Category {
    public $category_id;
    public $user_id;
    public $name;
    public $budget;
    private $conn;

    public function __construct($category_id=null, $user_id, $name, $budget) {
        global $servername, $dbusername, $dbpassword, $dbname;
        $this->conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        $this->category_id = $category_id;
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

    public function update() {
        /* Updates the category in the database */
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        // Update the values in the database
        $sql = "UPDATE categories SET name = '$this->name', budget = '$this->budget' WHERE id = '$this->category_id'";
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

    public static function getCategoryById($user_id, $category_id) {
        /* Returns  category for the given user and category_id */
        global $servername, $dbusername, $dbpassword, $dbname;
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        $sql = "SELECT * FROM categories WHERE user_id = '$user_id' AND id = '$category_id'";
        $result = $conn->query($sql);
        $conn->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function deleteCategoryById($user_id, $category_id) {
        /* Deletes category for the given user and category_id */
        global $servername, $dbusername, $dbpassword, $dbname;
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Delete the values from the database
        $sql = "DELETE FROM categories WHERE user_id = '$user_id' AND id = '$category_id'";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }
}