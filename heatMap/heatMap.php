<?php
// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoload Composer dependencies
require '../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection details from the environment variables
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

// Check if environment variables are loaded correctly
if (!$host || !$dbname || !$username || !$password) {
    die("Environment variables not loaded correctly.");
}

try {
    // Create a new PDO instance and set attributes for error handling
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo json_encode(["message" => "Database connection successful"]);
} catch (PDOException $e) {
    // Handle any database connection errors and return as JSON
    die(json_encode(["error" => "Connection failed: " . $e->getMessage()]));
}
?>