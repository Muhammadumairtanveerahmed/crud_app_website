<?php
// Database connection settings
// IMPORTANT: On your EC2 server, update these values to match your MySQL setup.
define('DB_HOST', 'localhost');
define('DB_USER', 'root');            // XAMPP default username
define('DB_PASS', '');                // XAMPP default password (empty)
define('DB_NAME', 'crud_app');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
