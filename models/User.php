<?php
require_once('database/config.php');

class User {
    public $name;
    public $username;
    private $password;
    private $conn;

    public function __construct($name, $username, $password) {
        global $servername, $dbusername, $dbpassword, $dbname;
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
        $this->conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function getPassword() {
        /* Return the hashed password */
        return password_hash($this->password, PASSWORD_DEFAULT);
    }

    public function save() {
        /* Inserts the user into the database */
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        // Save the values to the database
        $hashed_password = $this->getPassword();
        $sql = "INSERT INTO users (name, username, password) VALUES ('$this->name', '$this->username', '$hashed_password')";
        return $this->conn->query($sql);
    }

    public static function userExists($username) {
        /* Returns if the username exists */
        global $servername, $dbusername, $dbpassword, $dbname;
        // Connect to the database
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public static function passwordMatches($username, $password) {
        /* Returns if the password matches the username */
        global $servername, $dbusername, $dbpassword, $dbname;
        // Connect to the database
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the username exists
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verify the hashed password
            return password_verify($password, $row['password']);
        } else {
            return false;
        }
    }
}
    