<?php
require_once('models/Expense.php');
require_once('models/Category.php');

session_start();

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
            <div class="col-md-10">
                <h1 class="text-center">Dashboard</h1>
                <h2 class="text-center">Welcome, <?php echo $_SESSION["username"]; ?></h2>
                <p class="text-center"><a href="logout.php">Logout</a></p>
                <br/>

                <h3>Total Expense: $<?php echo Expense::getTotalExpenseByUser($_SESSION["user_id"]); ?></h3>
                <br/>

                <h3>Budget by Category:</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Budget</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $budgets = Category::getAllCategoriesByUser($_SESSION["user_id"]);
                        foreach ($budgets as $budget) {
                            echo "<tr>";
                            echo "<td>" . $budget['name'] . "</td>";
                            echo "<td>" . $budget['budget'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <br/>

                <h3>Expense by Category:</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $expenses = Expense::getExpenseOfCategoriesByUser($_SESSION["user_id"]);
                        foreach ($expenses as $expense) {
                            echo "<tr>";
                            echo "<td>" . $expense['name'] . "</td>";
                            echo "<td>" . $expense['amount'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <br/>

                <h3>All Expenses:</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Payment Method</th>
                            <th>Location</th>
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
                            echo "<td>" . $expense['amount'] . "</td>";
                            echo "<td>" . $expense['description'] . "</td>";
                            echo "<td>" . $expense['payment_method'] . "</td>";
                            echo "<td>" . $expense['location'] . "</td>";
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
