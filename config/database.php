<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');  // Default XAMPP MySQL password is empty
define('DB_NAME', 'snippetstack');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Attempt to connect to MySQL server first
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Check connection
if($conn === false){
    error_log("ERROR: Could not connect to MySQL server. " . mysqli_connect_error());
    die("ERROR: Could not connect to MySQL server. " . mysqli_connect_error());
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if(mysqli_query($conn, $sql)){
    // Select the database
    mysqli_select_db($conn, DB_NAME);
    
    // Create tables if they don't exist
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if(!mysqli_query($conn, $sql)){
        error_log("ERROR: Could not create users table. " . mysqli_error($conn));
        die("ERROR: Could not create users table. " . mysqli_error($conn));
    }
    
    $sql = "CREATE TABLE IF NOT EXISTS snippets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        language VARCHAR(50) NOT NULL,
        prefix VARCHAR(50),
        snippet TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    if(!mysqli_query($conn, $sql)){
        error_log("ERROR: Could not create snippets table. " . mysqli_error($conn));
        die("ERROR: Could not create snippets table. " . mysqli_error($conn));
    }
    
    // Create indexes
    $sql = "CREATE INDEX IF NOT EXISTS idx_user_id ON snippets(user_id)";
    mysqli_query($conn, $sql);
    
    $sql = "CREATE INDEX IF NOT EXISTS idx_language ON snippets(language)";
    mysqli_query($conn, $sql);
} else {
    error_log("ERROR: Could not create database. " . mysqli_error($conn));
    die("ERROR: Could not create database. " . mysqli_error($conn));
}
?> 