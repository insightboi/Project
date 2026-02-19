<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'smart_film_makers');
define('DB_USER', 'root');
define('DB_PASS', '');

// AI API Configuration
define('OPENAI_API_KEY', 'your-openai-api-key-here');
define('OPENAI_API_URL', 'https://api.openai.com/v1/chat/completions');

// Site Configuration
define('SITE_NAME', 'Smart Film Makers');
define('SITE_URL', 'http://localhost/smart_film_makers');
define('ADMIN_EMAIL', 'admin@smartfilmmakers.com');

// Session Configuration
define('SESSION_LIFETIME', 86400); // 24 hours

// File Upload Configuration
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 10485760); // 10MB

// Export Configuration
define('EXPORT_PATH', 'exports/');

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Include functions
require_once 'functions.php';

// Connect to database
$conn = connectDB();
?>
