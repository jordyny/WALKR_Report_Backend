<?php
require 'vendor/autoload.php';

// Load environment variables from the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get credentials from environment variables
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Insert data
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $hazard_type = $_POST['hazard_type'];
        $severity = $_POST['severity'];

        $stmt = $conn->prepare("INSERT INTO hazard_reports (latitude, longitude, hazard_type, severity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$latitude, $longitude, $hazard_type, $severity]);

        echo json_encode(["status" => "success", "message" => "Data inserted successfully"]);
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // Retrieve data
        $stmt = $conn->prepare("SELECT latitude, longitude, severity FROM hazard_reports");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
