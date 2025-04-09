<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - SnippetStack</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/about.css">
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
            <li><a href="about.php" class="active">About</a></li>
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

    <div class="about-container">
        <section class="about-section">
            <h2>About SnippetStack</h2>
            <p>SnippetStack is a powerful code snippet generator and manager that helps developers create, organize, and share their code snippets across different programming languages and editors.</p>
        </section>

        <section class="features-section">
            <h3>Key Features</h3>
            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-code"></i>
                    <h4>Multiple Languages</h4>
                    <p>Support for various programming languages including JavaScript, PHP, Python, HTML, CSS, Java, and C#.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-edit"></i>
                    <h4>Editor Support</h4>
                    <p>Generate snippets for popular code editors like VS Code, Sublime Text, and Atom.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-folder"></i>
                    <h4>Organized Library</h4>
                    <p>Save and organize your snippets in a personal library for easy access.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-share-alt"></i>
                    <h4>Share & Export</h4>
                    <p>Easily share your snippets with others or export them for use in your favorite editor.</p>
                </div>
            </div>
        </section>

        <section class="team-section">
            <h3>Our Mission</h3>
            <p>At SnippetStack, our mission is to make code snippet management easier and more efficient for developers. We believe that by providing a simple yet powerful tool for creating and managing code snippets, we can help developers be more productive and focus on what matters most - writing great code.</p>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 SnippetStack. All rights reserved.</p>
    </footer>
</body>
</html> 