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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $category_id = $_POST["category_id"];
    $date = $_POST["date"];
    $amount = $_POST["amount"];
    $description = $_POST["description"];
    $payment_method = $_POST["payment_method"];
    $location = $_POST["location"];
    
    // Check if expense amount exceeds remaining budget
    $budget_exists = false;
    $budget_exceeds = false;

    $budgets = User::getBudgetAndExpenseOfCategoriesByUser($_SESSION["user_id"], date('n', strtotime($date)));
    if ($budgets != null) {
        $budget_exists = true;
    }
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
    } else if ($budget_exists == false) {
        echo "
        <script>
            alert('Please add the budget category for the given date first!');
        </script>
        ";
    } else {   
        // Create expense object
        $expense = new Expense(null, $_SESSION["user_id"], $amount, $category_id, $description, $date, $payment_method, $location);
        if ($expense->save()){
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
                <h2 class="text-center">Add Expense</h2>
                
                <form method="POST" action="add_expense_form.php">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select category name</option>
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter expense amount (e.g. $20)" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Enter description" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <input type="text" class="form-control" id="payment_method" name="payment_method" placeholder="Enter payment method" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter location" required>
                    </div>
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
