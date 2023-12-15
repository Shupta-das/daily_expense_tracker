<?php
require_once('database/config.php');
require_once('models/User.php');
require_once('models/Category.php');

class Expense {
    public $user_id;
    public $amount;
    public $category_id;
    public $description;
    public $date;
    public $payment_method;
    public $location;
    private $conn;

    public function __construct($user_id, $amount, $category_id, $description=null, $date, $payment_method, $location=null) {
        global $servername, $dbusername, $dbpassword, $dbname;
        $this->conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        $this->user_id = $user_id;
        $this->amount = $amount;
        $this->category_id = $category_id;
        $this->description = $description;
        $this->date = $date;
        $this->payment_method = $payment_method;
        $this->location = $location;
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function save() {
        /* Inserts the expense into the database */
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        // Save the values to the database
        $sql = "INSERT INTO expenses (user_id, amount, category_id, description, date, payment_method, location) VALUES ('$this->user_id', '$this->amount', '$this->category_id', '$this->description', '$this->date', '$this->payment_method', '$this->location')";
        return $this->conn->query($sql);
    }

    public static function getTotalExpenseByUser($user_id) {
        /* Returns the total expense for the user */
        global $servername, $dbusername, $dbpassword, $dbname;
        // Connect to the database
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT SUM(amount) AS amount FROM expenses WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $conn->close();
        return floatval($row['amount']);
    }

    public static function getAllExpensesByUser($user_id) {
        /* Returns all the expenses for the user */
        global $servername, $dbusername, $dbpassword, $dbname;
        // Connect to the database
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT a.id, b.name AS category, a.amount, a.description, a.date, a.payment_method, a.location FROM expenses a INNER JOIN categories b ON a.category_id = b.id WHERE a.user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $conn->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getExpenseOfCategoriesByUser($user_id) {
        /* Returns the categories for the user */
        global $servername, $dbusername, $dbpassword, $dbname;
        // Connect to the database
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT b.name, SUM(a.amount) AS amount FROM expenses a INNER JOIN categories b ON a.category_id = b.id WHERE a.user_id = ? GROUP BY a.category_id");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $conn->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}