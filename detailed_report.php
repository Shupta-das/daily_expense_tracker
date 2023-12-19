<?php
require_once('models/Expense.php');
require_once('models/User.php');
require_once('models/MonthlyBudget.php');

session_start();

// If user not looged in 
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
}

$date_from = "";
$date_to = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_from = $_POST["expense_from"];
    $date_to = $_POST["expense_to"];

    if (isset($_POST["csv"]) and $_POST["csv"] == "true") {
        // Save expenses as CSV
        $expenses = Expense::getAllExpensesByUserBetween($_SESSION["user_id"], $date_from, $date_to);
        $filename = "data/expenses.csv";
        
        $fp = fopen($filename, 'w');
        fputcsv($fp, array('ID', 'Date', 'Category', 'Amount', 'Description', 'Payment Method', 'Location'));
        foreach ($expenses as $expense) {
            fputcsv($fp, $expense);
        }
        fclose($fp);
        echo "
            <script>
            alert('Data exported to CSV!');
            </script>
            ";
    }

    if (isset($_POST["excel"]) and $_POST["excel"] == "true") {
        // Save expenses as Excel file
        $expenses = Expense::getAllExpensesByUserBetween($_SESSION["user_id"], $date_from, $date_to);
        $filename = "data/expenses.xls";
        
        $fp = fopen($filename, 'w');
        $header = array('ID', 'Date', 'Category', 'Amount', 'Description', 'Payment Method', 'Location');
        $header = implode("\t", $header) . "\n";
        fwrite($fp, $header);
        foreach ($expenses as $expense) {
            $row = implode("\t", $expense) . "\n";
            fwrite($fp, $row);
        }
        fclose($fp);
        echo "
            <script>
            alert('Data exported to Excel!');
            </script>
            ";
    }
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

                <h1 class="text-center">Expense Tracker</h1>

                <div class="row justify-content-center m-2">
                    <div class="col-md-7">
                    <form method="POST" action="detailed_report.php">
                        <div class="form-group">
                            <label for="expense_from">Expense From:</label>
                            <input type="date" class="form-control" id="expense_from" name="expense_from" required>
                        </div>

                        <div class="form-group">
                            <label for="expense_to">Expense To:</label>
                            <input type="date" class="form-control" id="expense_to" name="expense_to" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Generate Report</button>
                        <button type="submit" name="csv" value="true" class="btn btn-block btn-outline-dark" >Export as CSV</button>
                        <button type="submit" name="excel" value="true" class="btn btn-block btn-outline-dark" >Export as Excel File</button>
                    </form>

                    <div class="text-center my-2">
                        <a class="btn btn-outline-primary btn-block" href="index.php">Dashboard</a>
                    </div>
                    </div>
                </div>
                
            

                <h3 class="text-center">All Expenses between <b><?php echo $date_from . " and " . $date_to; ?></b></h3>
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
                        $expenses = Expense::getAllExpensesByUserBetween($_SESSION["user_id"], $date_from, $date_to);
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

