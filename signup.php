<?php
require_once('models/User.php');

$error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the post form
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (User::userExists($username)) {
        $error_message = "Username already exists!";
    } else {
        $user = new User($name, $username, $password);
        if ($user->save()) {
            echo "
            <script>
                if (window.confirm('Signup successful!'))
                {
                    window.location.href = 'login.php';
                }
            </script>
            ";
        } else {
            die("Error: saving the data.");
        }
    }
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

                <div class="text-center my-2">
                    <a class="btn btn-outline-success btn-block" href="login.php">Login</a>
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
