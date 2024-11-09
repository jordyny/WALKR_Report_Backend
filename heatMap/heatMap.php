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

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle hazard data
    if (isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['hazard_type']) && isset($_POST['severity'])) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $hazard_type = $_POST['hazard_type'];
        $severity = $_POST['severity'];

        // Insert hazard report into the database
        $stmt = $pdo->prepare('INSERT INTO hazard_reports (latitude, longitude, hazard_type, severity) 
                               VALUES (:latitude, :longitude, :hazard_type, :severity)');
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);
        $stmt->bindParam(':hazard_type', $hazard_type);
        $stmt->bindParam(':severity', $severity);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Hazard report saved successfully"]);
        } else {
            echo json_encode(["error" => "Error saving hazard report"]);
        }
        exit;
    }
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
