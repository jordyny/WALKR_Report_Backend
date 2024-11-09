<?php
require 'vendor/autoload.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["error" => "Connection failed: " . $e->getMessage()]));
}

// Set header to return JSON
header('Content-Type: application/json');

// Handle POST requests for submitting hazard reports
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data from POST request
    $latitude = isset($_POST['latitude']) ? (float)$_POST['latitude'] : null;
    $longitude = isset($_POST['longitude']) ? (float)$_POST['longitude'] : null;
    $hazard_type = isset($_POST['hazard_type']) ? (string)$_POST['hazard_type'] : null;
    $severity = isset($_POST['severity']) ? (int)$_POST['severity'] : null;

    if ($latitude && $longitude && $hazard_type && $severity !== null) {
        // Insert hazard report into the database
        $query = "INSERT INTO hazard_reports (latitude, longitude, hazard_type, severity) 
                  VALUES ($latitude, $longitude, '$hazard_type', $severity)";
        
        if ($pdo->exec($query)) {
            echo json_encode(["message" => "Hazard report saved successfully"]);
        } else {
            echo json_encode(["error" => "Error saving hazard report"]);
        }
    } else {
        echo json_encode(["error" => "Missing required data"]);
    }
    exit;
}

// Handle GET requests to fetch heatmap data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'get') {
    // Fetch hazard reports data for the heatmap
    $stmt = $pdo->query('SELECT latitude, longitude, severity FROM hazard_reports');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
    exit;
}
?>
