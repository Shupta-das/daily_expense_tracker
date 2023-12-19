<?php
require_once('database/config.php');

class MonthlyBudget {
    public $user_id;
    public $month_no;
    public $total_budget;
    private $conn;

    public function __construct($user_id, $month_no, $total_budget) {
        global $servername, $dbusername, $dbpassword, $dbname;
        $this->conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        $this->user_id = $user_id;
        $this->month_no = $month_no;
        $this->total_budget = $total_budget;
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
        $sql = "INSERT INTO monthly_budget (user_id, month_no, total_budget) VALUES ('$this->user_id', '$this->month_no', '$this->total_budget')";
        return $this->conn->query($sql);
    }

    public function update() {
        /* Updates the category in the database */
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        // Update the values in the database
        $sql = "UPDATE monthly_budget SET total_budget = '$this->total_budget' WHERE user_id = '$this->user_id' AND month_no = '$this->month_no'";
        return $this->conn->query($sql);
    }

    public static function getTotalBudgetByUserAndMonth($user_id, $month_no) {
        /* Returns an array of categories for the given user */
        global $servername, $dbusername, $dbpassword, $dbname;
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        $sql = "SELECT total_budget FROM monthly_budget WHERE user_id = '$user_id' AND month_no = '$month_no'";
        $result = $conn->query($sql);
        $conn->close();
        return $result->fetch_assoc()['total_budget'];
    }

    public static function setDefaultMonthlyBudgets($user_id, $total_budget) {
        /* Returns an array of categories for the given user */
        global $servername, $dbusername, $dbpassword, $dbname;
        for ($i = 1; $i <= 12; $i++) {
            $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
            $sql = "INSERT INTO monthly_budget (user_id, month_no, total_budget) VALUES ('$user_id', '$i', '$total_budget')";
            $result = $conn->query($sql);
            $conn->close();
        }
        return $result;
    }

}