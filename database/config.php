<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "Shupta@";
$dbname = "expense_tracker";

// Connect to the database
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

$tables = array(
    // tablename => sqlfile
    "users" => "./database/sql/create_users_table.sql",
    "categories" => "./database/sql/create_categories_table.sql",
    "monthly_budget" => "./database/sql/create_monthly_budget_table.sql",
    "expenses" => "./database/sql/create_expenses_table.sql",
);

foreach ($tables as $tableName => $sqlFile) {
    // Check if the table exists
    $checkTableQuery = "SHOW TABLES LIKE '$tableName'";
    if ($conn->query($checkTableQuery)->num_rows <= 0) {
        // Remove all sessions
        session_start();
        session_unset();
        
        // If the table doesn't exist, execute the create table SQL
        $createTableSql = file_get_contents($sqlFile);
        $conn->query($createTableSql);
    }
}

$conn->close();
