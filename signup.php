<?php
require_once('database/config.php');

$error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the values from the post form
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    // Check if the username already exists
    $checkUsernameQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkUsernameResult = $conn->query($checkUsernameQuery);
    
    if ($checkUsernameResult->num_rows > 0) {
        // Username already exists, ignore
        $error_message = "Username already exists!";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Save the values to the database
        $sql = "INSERT INTO users (name, username, password) VALUES ('$name', '$username', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            echo "
            <script>
                if (window.confirm('Signup successful!'))
                {
                    window.location.href = 'login.php';
                }
            </script>
            ";
        } else {
            die("Error: " . $conn->error);
        }
    }

    $conn->close();
}
?>
        

<!DOCTYPE html>
<html>
<head>
    <title>Signup Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center m-4">
            <div class="col-md-6">
                <h2 class="text-center">Signup</h2>
                <form action="signup.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group text-center"> 
                        <button type="submit" class="btn btn-success btn-block">Signup</button>
                    </div>
                </form>
                <div class="text-center">
                    <a href="login.php">Login</a>
                </div>
                <div class="text-center">
                    <p class="text-danger"><?php echo $error_message; ?></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
