<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Please login to delete snippets']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Verify that the snippet belongs to the user
$sql = "SELECT id FROM snippets WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $data['id'], $_SESSION['id']);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) == 0) {
    echo json_encode(['success' => false, 'message' => 'Snippet not found or unauthorized']);
    exit;
}

mysqli_stmt_close($stmt);

// Delete the snippet
$sql = "DELETE FROM snippets WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $data['id'], $_SESSION['id']);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Snippet deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting snippet']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?> 