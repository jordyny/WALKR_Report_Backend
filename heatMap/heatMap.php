<?php
require 'vendor/autoload.php';

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
    die('Could not connect to the database: ' . $e->getMessage());
}

// Handle form submission (insert hazard report)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
    $hazard_type = $data['hazard_type'];
    $severity = $data['severity'];
    
    $stmt = $pdo->prepare('INSERT INTO hazard_reports (latitude, longitude, hazard_type, severity) VALUES (:latitude, :longitude, :hazard_type, :severity)');
    $stmt->bindParam(':latitude', $latitude);
    $stmt->bindParam(':longitude', $longitude);
    $stmt->bindParam(':hazard_type', $hazard_type);
    $stmt->bindParam(':severity', $severity);
    $stmt->execute();
    
    echo json_encode(['status' => 'success']);
    exit;
}

// Handle heatmap data retrieval
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'get') {
    $stmt = $pdo->query('SELECT latitude, longitude, severity FROM hazard_reports');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($results);
    exit;
}
?>
