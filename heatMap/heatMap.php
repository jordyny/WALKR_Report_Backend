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

// Set header to return JSON
header('Content-Type: application/json');

// Handle POST requests for submitting hazard reports
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(isset($_POST['heat'])){
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $hazard_type = $_POST['hazard_type'];
        $severity = $_POST['severity'];


        if($latitude && $longitude && $hazard_type && $severity){
            $stmt = $pdo->prepare("INSERT INTO hazards_report (latitude, longitude, hazard_type, severity) VALUES (?, ?, ?, ?)");
        }

        $stmt->bind_param("ddsi", $latitude, $longitude, $hazard_type, $severity);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Data saved successfully"]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }
        $stmt->close();

    }
}

?>