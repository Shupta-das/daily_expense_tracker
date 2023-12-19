<?php
require_once('database/config.php');

class Expense {
    public $expense_id;
    public $user_id;
    public $amount;
    public $category_id;
    public $description;
    public $date;
    public $payment_method;
    public $location;
    private $conn;

    public function __construct($expense_id=null, $user_id, $amount, $category_id, $description, $date, $payment_method, $location) {
        global $servername, $dbusername, $dbpassword, $dbname;
        $this->conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        $this->expense_id = $expense_id;
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
        $sql = "INSERT INTO expenses (user_id, amount, category_id, description, date, payment_method, location) 
                VALUES ('$this->user_id', '$this->amount', '$this->category_id', '$this->description', '$this->date', '$this->payment_method', '$this->location')";
        return $this->conn->query($sql);
    }

    public function update() {
        /* Updates the expense in the database */
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        // Update the values in the database
        $sql = "UPDATE expenses SET amount='$this->amount', category_id='$this->category_id', description='$this->description', date='$this->date', payment_method='$this->payment_method', location='$this->location' WHERE id='$this->expense_id'";
        return $this->conn->query($sql);
    }

    public static function getAllExpensesByUser($user_id, $month_no) {
        /* Returns all the expenses for the user */
        global $servername, $dbusername, $dbpassword, $dbname;
        // Connect to the database
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT a.id, b.name AS category, a.amount, a.description, a.date, a.payment_method, a.location 
                            FROM expenses a 
                            INNER JOIN categories b ON a.category_id = b.id 
                            WHERE a.user_id = ? AND MONTH(a.date) = ?");
        $stmt->bind_param("ss", $user_id, $month_no);
        $stmt->execute();
        $result = $stmt->get_result();
        $conn->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getAllExpensesByUserBetween($user_id, $date_from, $date_to) {
        /* Returns all the expenses for the user between the given dates */
        global $servername, $dbusername, $dbpassword, $dbname;
        // Connect to the database
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT a.id, b.name AS category, a.amount, a.description, a.date, a.payment_method, a.location 
                            FROM expenses a 
                            INNER JOIN categories b ON a.category_id = b.id 
                            WHERE a.user_id = ? AND a.date BETWEEN ? AND ?");
        $stmt->bind_param("sss", $user_id, $date_from, $date_to);
        $stmt->execute();
        $result = $stmt->get_result();
        $conn->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getExpenseById($user_id, $expense_id) {
        /* Returns the expense with the given id */
        global $servername, $dbusername, $dbpassword, $dbname;
        // Connect to the database
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT a.id, b.name AS category, a.amount, a.description, a.date, a.payment_method, a.location FROM expenses a INNER JOIN categories b ON a.category_id = b.id WHERE a.user_id = ? AND a.id = ?");
        $stmt->bind_param("ss", $user_id, $expense_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $conn->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function deleteExpenseById($user_id, $expense_id) {
        /* Deletes the expense with the given id */
        global $servername, $dbusername, $dbpassword, $dbname;
        // Connect to the database
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query
        $sql = "DELETE FROM expenses WHERE user_id = '$user_id' AND id = '$expense_id'";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    public static function getTotalExpensesByUserAndMonth($user_id, $month_no) {
        /* Returns the total expenses for the user */
        global $servername, $dbusername, $dbpassword, $dbname;
        // Connect to the database
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT SUM(amount) AS total_expenses FROM expenses WHERE user_id = ? AND MONTH(date) = ?");
        $stmt->bind_param("ss", $user_id, $month_no);
        $stmt->execute();
        $result = $stmt->get_result();
        $conn->close();
        return $result->fetch_assoc()['total_expenses'];
    }
}