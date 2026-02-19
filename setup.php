<?php
// Database Setup Script
require_once 'config.php';

// Create database if it doesn't exist
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

$conn->close();

// Connect to the new database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read and execute the SQL schema
$sql_file = 'database.sql';
if (file_exists($sql_file)) {
    $sql = file_get_contents($sql_file);
    
    // Split SQL statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            if ($conn->query($statement) === TRUE) {
                echo "✓ Table/record created successfully<br>";
            } else {
                echo "✗ Error: " . $conn->error . "<br>";
                echo "Statement: " . $statement . "<br><br>";
            }
        }
    }
    
    echo "<h3>Database setup completed!</h3>";
    echo "<p><a href='index.php'>Go to Homepage</a></p>";
    echo "<p><a href='login.php'>Go to Login</a></p>";
    echo "<p><a href='admin/login.php'>Go to Admin Login</a></p>";
    
} else {
    echo "Error: database.sql file not found";
}

$conn->close();
?>
