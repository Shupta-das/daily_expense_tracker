<?php
session_start();

// If user not logged in 
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
}

require_once("models/Expense.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" and isset($_GET["user_id"]) and isset($_GET["expense_id"])) {
    if (Expense::deleteExpenseById($_GET["user_id"], $_GET["expense_id"])) {
        echo "
            <script>
                if (window.confirm('Data deleted successfully!'))
                {
                    window.location.href = 'index.php';
                }
            </script>
            ";
    } else {
        die("Error: deleting the data.");
    }
} 