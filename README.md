# SnippetStack

SnippetStack is a web application that allows users to create, manage, and share code snippets across different programming languages and editors. It features a beautiful dark UI and supports various code editors like VS Code, Sublime Text, and Atom.

## Features

- Create code snippets with support for multiple programming languages
- Generate snippets for different code editors (VS Code, Sublime Text, Atom)
- Save snippets to your personal library
- Share snippets with others
- User authentication system
- Beautiful dark theme UI
- Responsive design

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache, Nginx, etc.)
- Modern web browser

## Installation

1. Clone the repository to your web server directory:
   ```bash
   git clone https://github.com/yourusername/snippetstack.git
   ```

2. Create a MySQL database and import the database structure:
   ```bash
   mysql -u root -p < database.sql
   ```

3. Configure the database connection in `config/database.php`:
   ```php
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'your_username');
   define('DB_PASSWORD', 'your_password');
   define('DB_NAME', 'snippetstack');
   ```

4. Make sure your web server has write permissions for the project directory.

5. Access the application through your web browser:
   ```
   http://localhost/snippetstack
   ```

## Usage

1. Register a new account or login if you already have one.
2. Navigate to the "Generate" page to create a new snippet.
3. Select the programming language and target editor.
4. Enter your code in the editor.
5. Click "Generate" to create the snippet.
6. Copy the generated snippet or save it to your library.

## Project Structure

```
snippetstack/
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   ├── auth.css
│   │   └── generate.css
│   └── js/
│       └── generate.js
├── config/
│   └── database.php
├── index.php
├── generate.php
├── login.php
├── register.php
├── logout.php
├── save_snippet.php
├── database.sql
└── README.md
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- CodeMirror for the code editor
- Font Awesome for icons
- All contributors and users of the application 