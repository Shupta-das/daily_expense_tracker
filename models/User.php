<?php
require_once('database/config.php');

class User {
    public $name;
    public $username;
    private $password;
    private $conn;

    public function __construct($name, $username, $password) {
        global $servername, $dbusername, $dbpassword, $dbname;
        $this->conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
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
        $result = $stmt->get_result();
        $conn->close();
        return $result->num_rows > 0;
    }

    public static function passwordMatches($username, $password) {
        /* Returns user_id if the password matches the username */
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
            $conn->close();
            // Verify the hashed password
            return password_verify($password, $row['password'])? $row['id'] : null;
        } else {
            return null;
        }
    }

    public static function getBudgetAndExpenseOfCategoriesByUser($user_id) {
         /* Returns the budget and expense of categories for the user */
         global $servername, $dbusername, $dbpassword, $dbname;
         // Connect to the database
         $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
 
         // Check connection
         if ($conn->connect_error) {
             die("Connection failed: " . $conn->connect_error);
         }
         // Prepare and execute the SQL query
         $stmt = $conn->prepare("SELECT c.user_id, c.id AS category_id, c.name, c.budget, COALESCE(SUM(e.amount), 0) AS expense 
                                    FROM categories c
                                    LEFT JOIN expenses e ON e.category_id = c.id
                                    WHERE c.user_id = ?
                                    GROUP BY c.id
                                    ORDER BY c.id");

         $stmt->bind_param("s", $user_id);
         $stmt->execute();
         $result = $stmt->get_result();
         
         $conn->close();
         return $result->fetch_all(MYSQLI_ASSOC);
    }
}
    