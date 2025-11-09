<?php
// db_connect.php - Handles the PDO connection to MySQL

// Database configuration (MODIFY THESE IF YOUR SETUP IS DIFFERENT)
define('DB_HOST', 'localhost');  
define('DB_NAME', 'blog1.db');    // The database name you created
define('DB_USER', 'uditha');       // Default XAMPP/WAMP username
define('DB_PASS', '');           // Default XAMPP/WAMP password (usually empty)

$pdo = null; // Initialize the connection variable

try {
    // Data Source Name string
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    
    // Create a new PDO instance
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Fetch results as associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                   // Use native prepared statements
    ]);
    
} catch (\PDOException $e) {
    // Stop execution and show error if connection fails
    die("Database connection failed. Please check your db_connect.php credentials and MySQL server status.");
}
?>