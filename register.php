<?php
session_start();
require_once 'config/database.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);

    // Validate input
    if (empty($username)) {
        $error = "Please enter a username.";
    } elseif (empty($email)) {
        $error = "Please enter an email.";
    } elseif (empty($password)) {
        $error = "Please enter a password.";
    } elseif (strlen($password) < 6) {
        $error = "Password must have at least 6 characters.";
    } elseif ($password != $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username exists
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $error = "This username is already taken.";
                } else {
                    // Check if email exists
                    $sql = "SELECT id FROM users WHERE email = ?";
                    
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_bind_param($stmt, "s", $email);
                        
                        if (mysqli_stmt_execute($stmt)) {
                            mysqli_stmt_store_result($stmt);
                            
                            if (mysqli_stmt_num_rows($stmt) == 1) {
                                $error = "This email is already registered.";
                            } else {
                                // Insert new user
                                $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
                                
                                if ($stmt = mysqli_prepare($conn, $sql)) {
                                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                                    
                                    mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $email);
                                    
                                    if (mysqli_stmt_execute($stmt)) {
                                        header("location: login.php");
                                    } else {
                                        $error = "Something went wrong. Please try again later.";
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SnippetStack</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dark-theme">
    <div class="auth-container">
        <div class="auth-box">
            <h2>Create Account</h2>
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="auth-button">Register</button>
            </form>
            <p class="auth-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html> 