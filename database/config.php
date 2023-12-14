<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "expense_tracker";

// Connect to the database
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

$tables = array(
    // tablename => sqlfile
    "users" => "./database/sql/create_users_table.sql"
);

foreach ($tables as $tableName => $sqlFile) {
    // Check if the table exists
    $checkTableQuery = "SHOW TABLES LIKE '$tableName'";
    if ($conn->query($checkTableQuery)->num_rows <= 0) {
        // If the table doesn't exist, execute the create table SQL
        $createTableSql = file_get_contents($sqlFile);
        $conn->query($createTableSql);
    }
}

$conn->close();
