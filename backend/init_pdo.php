<?php
// Load the database configuration
require_once('config.php');

try {
    // Build the connection string
    $connectionString = "mysql:host=" . _MYSQL_HOST;
    if (defined('_MYSQL_PORT')) {
        $connectionString .= ";port=" . _MYSQL_PORT;
    }
    $connectionString .= ";dbname=" . _MYSQL_DBNAME;

    // Options for the PDO connection.
    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions for error handling
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ // Fetch data as objects by default
    );

    // Create a new PDO instance
    $pdo = new PDO($connectionString, _MYSQL_USER, _MYSQL_PASSWORD, $options);
    
} catch (PDOException $e) {
    // Handle connection errors
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
