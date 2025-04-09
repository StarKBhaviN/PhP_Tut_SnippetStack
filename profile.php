<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Get user data
$sql = "SELECT username, email, created_at FROM users WHERE id = ?";
$user = null;

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
    }
    
    mysqli_stmt_close($stmt);
}

// Get user's snippets count
$sql = "SELECT COUNT(*) as total_snippets FROM snippets WHERE user_id = ?";
$snippets_count = 0;

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $snippets_count = $row['total_snippets'];
    }
    
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - SnippetStack</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dark-theme">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            <h1>SnippetStack</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="generate.php">Generate</a></li>
            <li><a href="library.php">Library</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="profile.php" class="active">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="profile-container">
        <div class="glass-card profile-card">
            <div class="profile-header">
                <div class="avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                <p class="email"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            
            <div class="profile-stats">
                <div class="stat-item">
                    <i class="fas fa-code"></i>
                    <h3><?php echo $snippets_count; ?></h3>
                    <p>Snippets</p>
                </div>
                <div class="stat-item">
                    <i class="fas fa-calendar-alt"></i>
                    <h3><?php echo date('M Y', strtotime($user['created_at'])); ?></h3>
                    <p>Member Since</p>
                </div>
            </div>
        </div>

        <div class="glass-card activity-card">
            <h3>Recent Activity</h3>
            <div class="activity-list">
                <?php
                // Get recent snippets
                $sql = "SELECT * FROM snippets WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="activity-item">';
                            echo '<i class="fas fa-file-code"></i>';
                            echo '<div class="activity-info">';
                            echo '<h4>' . htmlspecialchars($row['name']) . '</h4>';
                            echo '<p>' . date('M d, Y', strtotime($row['created_at'])) . ' • ' . htmlspecialchars($row['language']) . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                    
                    mysqli_stmt_close($stmt);
                }
                ?>
            </div>
        </div>

        <div class="glass-card settings-card">
            <h3>Account Settings</h3>
            <form id="profile-form" class="settings-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" placeholder="Enter current password">
                </div>
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" placeholder="Enter new password">
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm New Password</label>
                    <input type="password" id="confirm-password" placeholder="Confirm new password">
                </div>
                <button type="submit" class="update-btn">Update Password</button>
            </form>
        </div>
    </div>

    <script src="assets/js/profile.js"></script>
</body>
</html> 