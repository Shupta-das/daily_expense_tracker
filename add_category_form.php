<?php
session_start();

// If user not looged in 
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
}

require_once("models/Category.php");

$error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $name = trim($_POST["name"]);
    $budget = trim($_POST["budget"]);
    $user_id = $_SESSION["user_id"];
    
    if ($budget < 0) {
        $error_message = "Budget cannot be negative.";
    } else {
        $category = new Category(null, $user_id, $name, $budget);
        if ($category->save()){
            echo "
                <script>
                    if (window.confirm('Data saved successfully!'))
                    {
                        window.location.href = 'index.php';
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
    <title>Category From</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center m-4">
            <div class="col-md-6">
                <h2 class="text-center">New Expense Category</h2>
                
                <form method="POST" action="add_category_form.php">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter category name (e.g. Groceries, Entertainment, Utilities)" required>
                    </div>
                    <div class="form-group">
                        <label for="budget">Budget</label>
                        <input type="number" class="form-control" id="budget" name="budget" placeholder="Enter category budget (e.g. $100)" required>
                    </div>
                    <button type="submit" class="btn btn-warning btn-block">Submit</button>
                </form>

                <div class="text-center my-2">
                    <a class="btn btn-outline-warning btn-block" href="index.php">Dashboard</a>
                </div>

                <div class="text-center">
                    <p class="text-danger"><?php echo $error_message; ?></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
