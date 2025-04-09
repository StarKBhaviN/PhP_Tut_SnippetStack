<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnippetStack - Code Snippet Generator</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dark-theme">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            <h1>SnippetStack</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="generate.php">Generate</a></li>
            <li><a href="library.php">Library</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Create and Manage Code Snippets</h1>
            <p>Generate, save, and share your code snippets across different programming languages and editors.</p>
            <a href="generate.php" class="cta-button">Start Generating</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="feature-card">
            <i class="fas fa-code"></i>
            <h3>Multiple Languages</h3>
            <p>Support for various programming languages and code editors</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-share-alt"></i>
            <h3>Share & Export</h3>
            <p>Easily share your snippets with others</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-folder"></i>
            <h3>Organized Library</h3>
            <p>Keep your snippets organized by language and category</p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 SnippetStack. All rights reserved.</p>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html> 