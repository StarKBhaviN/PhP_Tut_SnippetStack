<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Please login to update password']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['currentPassword']) || !isset($data['newPassword'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Get current password hash
$sql = "SELECT password FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Verify current password
if (!password_verify($data['currentPassword'], $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
    exit;
}

// Hash new password
$newPasswordHash = password_hash($data['newPassword'], PASSWORD_DEFAULT);

// Update password
$sql = "UPDATE users SET password = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $newPasswordHash, $_SESSION['id']);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating password']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?> 