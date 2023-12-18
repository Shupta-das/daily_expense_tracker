<?php
require_once('models/Expense.php');
require_once('models/User.php');

session_start();

// If user not looged in 
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Index</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center m-2">
            <div class="col-md-11">
                <h1 class="text-center">Dashboard</h1>
                <h2 class="text-center">Welcome, <?php echo $_SESSION["username"]; ?></h2>
                
                <br/>
                
                <div class="row justify-content-center">
                    <div class="col-md-3"><a class="btn btn-block btn-outline-warning" href="add_category_form.php">Add a New Category</a></div>
                    <div class="col-md-3"><a class="btn btn-block btn-outline-info" href="add_expense_form.php">Add a New Expense</a></div>
                    <div class="col-md-3"><a class="btn btn-block btn-outline-danger" href="logout.php">Logout</a></div>
                </div>

                <br/>

                <h3>Budget and Expense by Category:</h3>
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Budget</th>
                            <th>Expense</th>
                            <th>Remaining Budget</th>
                            <th>Edit Budget</th>
                            <th>Delete Budget</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $budgets = User::getBudgetAndExpenseOfCategoriesByUser($_SESSION["user_id"]);
                        $total_budget = 0;
                        $total_expense = 0;
                        $remaining_budget = 0;
                        foreach ($budgets as $budget) {
                            $total_budget += $budget['budget'];
                            $total_expense += $budget['expense'];
                            $remaining_budget += $budget['budget'] - $budget['expense'];
                            echo "<tr>";
                            echo "<td>" . $budget['name'] . "</td>";
                            echo "<td> $" . $budget['budget'] . "</td>";
                            echo "<td> $" . $budget['expense'] . "</td>";
                            echo "<td> $" . number_format($budget['budget'] - $budget['expense'], 2) . "</td>";
                            echo "<td> <a class='btn btn-outline-warning btn-block' href='update_category_form.php?user_id=". $budget['user_id'] ."&category_id=". $budget['category_id'] ."' >Edit</a></td>";
                            echo "<td> <a class='btn btn-outline-danger btn-block' href='delete_category.php?user_id=". $budget['user_id'] ."&category_id=". $budget['category_id'] ."' >Delete</a></td>";
                            echo "</tr>";
                        }
                        echo "<tr>";
                        echo "<td><b>Total:</b></td>";
                        echo "<td><b> $" . number_format($total_budget, 2) . "</b></td>";
                        echo "<td><b> $" . number_format($total_expense, 2) . "</b></td>";
                        echo "<td><b> $" . number_format($remaining_budget, 2) . "</b></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "</tr>";
                        ?>
                    </tbody>
                </table>
                <br/>

                <h3>All Expenses:</h3>
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Payment Method</th>
                            <th>Location</th>
                            <th>Edit Expense</th>
                            <th>Delete Expense</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $expenses = Expense::getAllExpensesByUser($_SESSION["user_id"]);
                        foreach ($expenses as $expense) {
                            echo "<tr>";
                            echo "<td>" . $expense['id'] . "</td>";
                            echo "<td>" . date('F j', strtotime($expense['date'])) . "</td>";
                            echo "<td>" . $expense['category'] . "</td>";
                            echo "<td> $" . $expense['amount'] . "</td>";
                            echo "<td>" . $expense['description'] . "</td>";
                            echo "<td>" . $expense['payment_method'] . "</td>";
                            echo "<td>" . $expense['location'] . "</td>";
                            echo "<td> <a class='btn btn-outline-info btn-block' href='update_expense_form.php?user_id=". $_SESSION["user_id"] ."&expense_id=". $expense['id'] ."' >Edit</a></td>";
                            echo "<td> <a class='btn btn-outline-danger btn-block' href='delete_expense.php?user_id=". $_SESSION["user_id"] ."&expense_id=". $expense['id'] ."' >Delete</a></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <br/>
            </div>
        </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
