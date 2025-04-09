<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Snippet - SnippetStack</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/generate.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
</head>
<body class="dark-theme">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            <h1>SnippetStack</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="generate.php" class="active">Generate</a></li>
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

    <div class="generator-container">
        <div class="controls">
            <div class="control-group">
                <label for="language">Language:</label>
                <select id="language">
                    <option value="javascript">JavaScript</option>
                    <option value="php">PHP</option>
                    <option value="python">Python</option>
                    <option value="html">HTML</option>
                    <option value="css">CSS</option>
                    <option value="java">Java</option>
                    <option value="csharp">C#</option>
                </select>
            </div>
            <div class="control-group">
                <label for="editor">Editor:</label>
                <select id="editor">
                    <option value="vscode">VS Code</option>
                    <option value="sublime">Sublime Text</option>
                    <option value="atom">Atom</option>
                </select>
            </div>
            <div class="control-group">
                <label for="snippet-name">Snippet Name:</label>
                <input type="text" id="snippet-name" placeholder="Enter snippet name">
            </div>
            <div class="control-group">
                <label for="snippet-prefix">Prefix:</label>
                <input type="text" id="snippet-prefix" placeholder="Enter trigger prefix">
            </div>
        </div>

        <div class="editor-container">
            <div class="code-editor">
                <h3>Your Code</h3>
                <textarea id="code-input"></textarea>
            </div>
            <div class="snippet-preview">
                <h3>Snippet Preview</h3>
                <pre id="snippet-output"></pre>
            </div>
        </div>

        <div class="actions">
            <button id="generate-btn" class="cta-button">Generate Snippet</button>
            <button id="copy-btn" class="cta-button">Copy Snippet</button>
            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <button id="save-btn" class="cta-button">Save to Library</button>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/python/python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/clike/clike.min.js"></script>
    <script src="assets/js/generate.js"></script>
</body>
</html> 