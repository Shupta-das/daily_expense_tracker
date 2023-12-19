<?php
session_start();

// If user not looged in 
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
}

require_once("models/MonthlyBudget.php");

$error_message = "";
$month_no  = -1;

if ($_SERVER["REQUEST_METHOD"] == "GET" and isset($_GET["user_id"]) and isset($_GET["month_no"])) {
    $user_id = $_GET["user_id"];
    $month_no = $_GET["month_no"];
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $total_budget = trim($_POST["budget"]);
    $month_no = $_POST["month_no"];
    $user_id = $_SESSION["user_id"];

    if ($total_budget < 0) {
        $error_message = "Budget cannot be negative.";
    } else {
        $budget = new MonthlyBudget($user_id, $month_no, $total_budget);
        if ($budget->update()){
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
                <h2 class="text-center">Update Budget of <?php echo date("F", mktime(0, 0, 0, $month_no, 1)); ?></h2>
                
                <form method="POST" action="update_monthly_budget_form.php">
                    <div class="form-group">
                        <label for="budget">Budget</label>
                        <input type="number" class="form-control" id="budget" name="budget" value="<?php echo $budget; ?>" placeholder="Enter total budget (e.g. $1000)" required>
                    </div>
                    <input type="hidden" id="month_no" name="month_no" value="<?php echo $month_no; ?>">
                    <button type="submit" class="btn my-2 btn-success btn-block">Update</button>
                </form>

                <div class="text-center my-2">
                    <a class="btn btn-outline-success btn-block" href="index.php">Dashboard</a>
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
