<?php
session_start();

// If user not looged in 
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
}

require_once("models/Category.php");

$error_message = "";
$name = "";
$budget = "";
$category_id = -1;

if ($_SERVER["REQUEST_METHOD"] == "GET" and isset($_GET["user_id"]) and isset($_GET["category_id"])) {
    $user_id = $_GET["user_id"];
    $category_id = $_GET["category_id"];
    $category = Category::getCategoryById($user_id, $category_id)[0];
    if ($category) {
        $name = $category["name"];
        $budget = $category["budget"];
        $monthName = date("F", strtotime($category["date"]));
    } else {
        die("Error: category not found.");
    }
} 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $name = trim($_POST["name"]);
    $budget = trim($_POST["budget"]);
    $date = date('Y-m-d', strtotime($_POST["month"] . ' 1'));
    $user_id = $_SESSION["user_id"];
    $category_id = $_POST["category_id"];
    
    if ($budget < 0) {
        $error_message = "Budget cannot be negative.";
    } else {
        $category = new Category($category_id, $user_id, $name, $budget, $date);
        if ($category->update()){
            echo "
                <script>
                    if (window.confirm('Data updated successfully!'))
                    {
                        window.location.href = 'index.php';
                    }
                </script>
                ";
        } else {
            die("Error: updating the data.");
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
                <h2 class="text-center">Update Expense Category</h2>
                
                <form method="POST" action="update_category_form.php">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" placeholder="Enter category name (e.g. Groceries, Entertainment, Utilities)" required>
                    </div>
                    <div class="form-group">
                        <label for="budget">Budget</label>
                        <input type="number" class="form-control" id="budget" name="budget" value="<?php echo $budget; ?>" placeholder="Enter category budget (e.g. $100)" required>
                    </div>
                    <div class="form-group">
                        <label for="month">Month</label>
                        <select class="form-control" id="month" name="month" required>
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                $month = date("F", mktime(0, 0, 0, $i, 1));
                                if ($month == $monthName) {
                                    echo "<option value='$month' selected>$month</option>";
                                } else {
                                    echo "<option value='$month'>$month</option>";
                                }
                            } ?>
                        </select>
                    <input type="hidden" id="category_id" name="category_id" value="<?php echo $category_id; ?>">
                    <button type="submit" class="btn my-2 btn-warning btn-block">Update</button>
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
