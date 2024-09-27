<?php
$host = 'localhost';      // Database host, usually 'localhost'
$dbname = 'students';     // The name of your database (updated to match the SQL)
$username = 'root';       // Your database username, usually 'root' for local development
$password = '';           // Your database password, leave empty for local development

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8 (optional, but recommended)
$conn->set_charset("utf8");
?>
