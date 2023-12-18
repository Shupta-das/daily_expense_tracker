<?php
session_start();

// If user not logged in 
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
}

require_once("models/User.php");
require_once("models/Expense.php");
require_once("models/Category.php");

// Get categories by user
$categories = Category::getCategoriesByUser($_SESSION["user_id"]);

$error_message = "";
$expense_id = -1;
$category_name = null;
$amount = 0;
$description = "";
$date = null;
$payment_method = "";
$location = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" and isset($_GET["user_id"]) and isset($_GET["expense_id"])) {
    $user_id = $_GET["user_id"];
    $expense_id = $_GET["expense_id"];
    $expense = Expense::getExpenseById($user_id, $expense_id)[0];
    if ($expense) {
        $category_name = $expense["category"];
        $amount = $expense["amount"];
        $description = $expense["description"];
        $date = $expense["date"];
        $payment_method = $expense["payment_method"];
        $location = $expense["location"];
    } else {
        die("Error: category not found.");
    }
} 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $expense_id = $_POST["expense_id"];
    $category_id = $_POST["category_id"];
    $date = $_POST["date"];
    $amount = $_POST["amount"];
    $description = $_POST["description"];
    $payment_method = $_POST["payment_method"];
    $location = $_POST["location"];
    
    // Check if expense amount exceeds remaining budget
    $budget_exceeds = false;
    $budgets = User::getBudgetAndExpenseOfCategoriesByUser($_SESSION["user_id"]);
    foreach ($budgets as $budget) {
        if ($budget['category_id'] == $category_id) {
            $remaining_budget = $budget['budget'] - $budget['expense'];
            if ($remaining_budget < $amount) {
                $budget_exceeds = true;
                break;
            }
        }
    }

    if ($budget_exceeds) {
        echo "
        <script>
            alert('Expense amount exceeds remaining budget');
        </script>
        ";
    } else {   
        // Create expense object
        $expense = new Expense($expense_id, $_SESSION["user_id"], $amount, $category_id, $description, $date, $payment_method, $location);
        if ($expense->update()){
            echo "
            <script>
            if (window.confirm('Data updated successfully!'))
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
                <h2 class="text-center">Update Expense</h2>
                
                <form method="POST" action="update_expense_form.php">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select category name</option>
                            <?php foreach ($categories as $category) { 
                                if ($category['name'] == $category_name) {?>
                                    <option value="<?php echo $category['id']; ?>" selected><?php echo $category['name']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php }} ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?php echo $date; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="<?php echo $amount; ?>" placeholder="Enter expense amount (e.g. $20)" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" name="description" value="<?php echo $description; ?>" placeholder="Enter description" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <input type="text" class="form-control" id="payment_method" name="payment_method" value="<?php echo $payment_method; ?>" placeholder="Enter payment method" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo $location; ?>" placeholder="Enter location" required>
                    </div>
                    <input type="hidden" id="expense_id" name="expense_id" value="<?php echo $expense_id; ?>">
                    <button type="submit" class="btn btn-info btn-block">Submit</button>
                </form>

                <div class="text-center my-2">
                    <a class="btn btn-outline-info btn-block" href="index.php">Dashboard</a>
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