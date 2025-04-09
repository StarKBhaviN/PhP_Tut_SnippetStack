<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Get user's snippets
$sql = "SELECT * FROM snippets WHERE user_id = ? ORDER BY created_at DESC";
$snippets = [];

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $snippets[] = $row;
        }
    }
    
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library - SnippetStack</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/library.css">
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
            <li><a href="library.php" class="active">Library</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="library-container">
        <h2>My Snippets</h2>
        
        <?php if (empty($snippets)): ?>
            <div class="empty-library">
                <i class="fas fa-folder-open"></i>
                <p>Your library is empty. Start by creating some snippets!</p>
                <a href="generate.php" class="cta-button">Create Snippet</a>
            </div>
        <?php else: ?>
            <div class="snippets-grid">
                <?php foreach ($snippets as $snippet): ?>
                    <div class="snippet-card">
                        <div class="snippet-header">
                            <h3><?php echo htmlspecialchars($snippet['name']); ?></h3>
                            <span class="language-badge"><?php echo htmlspecialchars($snippet['language']); ?></span>
                        </div>
                        <div class="snippet-content">
                            <pre><?php echo htmlspecialchars($snippet['snippet']); ?></pre>
                        </div>
                        <div class="snippet-footer">
                            <span class="date">Created: <?php echo date('M d, Y', strtotime($snippet['created_at'])); ?></span>
                            <div class="actions">
                                <button class="copy-btn" data-snippet="<?php echo htmlspecialchars($snippet['snippet']); ?>">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                                <button class="delete-btn" data-id="<?php echo $snippet['id']; ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Copy snippet to clipboard
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', () => {
                const snippet = button.getAttribute('data-snippet');
                navigator.clipboard.writeText(snippet).then(() => {
                    alert('Snippet copied to clipboard!');
                }).catch(err => {
                    console.error('Failed to copy snippet: ', err);
                });
            });
        });

        // Delete snippet
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', () => {
                if (confirm('Are you sure you want to delete this snippet?')) {
                    const id = button.getAttribute('data-id');
                    fetch('delete_snippet.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            button.closest('.snippet-card').remove();
                        } else {
                            alert('Failed to delete snippet: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the snippet');
                    });
                }
            });
        });
    </script>
</body>
</html> 