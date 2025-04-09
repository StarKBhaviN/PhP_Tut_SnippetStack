<?php
session_start();
require_once 'config/database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Please login to save snippets']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    error_log("Invalid JSON data received: " . file_get_contents('php://input'));
    echo json_encode(['success' => false, 'message' => 'Invalid data format']);
    exit;
}

// Validate required fields
if (empty($data['name']) || empty($data['language']) || empty($data['snippet'])) {
    error_log("Missing required fields in data: " . print_r($data, true));
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    // Prepare SQL statement
    $sql = "INSERT INTO snippets (user_id, name, language, prefix, snippet, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "issss", 
            $_SESSION['id'],
            $data['name'],
            $data['language'],
            $data['prefix'] ?? '',
            $data['snippet']
        );

        // Execute statement
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Snippet saved successfully']);
        } else {
            error_log("Error executing statement: " . mysqli_stmt_error($stmt));
            throw new Exception('Error executing statement: ' . mysqli_stmt_error($stmt));
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        error_log("Error preparing statement: " . mysqli_error($conn));
        throw new Exception('Error preparing statement: ' . mysqli_error($conn));
    }
} catch (Exception $e) {
    error_log('Snippet save error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error saving snippet: ' . $e->getMessage()]);
}

// Close connection
mysqli_close($conn);
?> 