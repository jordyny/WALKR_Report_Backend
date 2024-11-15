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
} catch (PDOException $e) {
    // Handle any database connection errors and return as JSON
    die(json_encode(["error" => "Connection failed: " . $e->getMessage()]));
}

// Set header to return JSON
header('Content-Type: application/json');

// Handle POST requests for submitting hazard reports
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(isset($_POST['heatMap'])){
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $hazard_type = $_POST['hazard_type'];
        $severity = $_POST['severity'];


        if($latitude && $longitude && $hazard_type && $severity){
            $stmt = $pdo->prepare("INSERT INTO hazard_reports (latitude, longitude, hazard_type, severity) VALUES (?, ?, ?, ?)");
        } else{
            echo json_encode(["error" => "Missing required fields"]);
            die();
        }

        $stmt->bindParam(1, $latitude, PDO::PARAM_STR);  // latitude as a float
        $stmt->bindParam(2, $longitude, PDO::PARAM_STR);  // longitude as a float
        $stmt->bindParam(3, $hazard_type, PDO::PARAM_STR);
        $stmt->bindParam(4, $severity, PDO::PARAM_STR);
                
        if ($stmt->execute()) {
            echo json_encode(["message" => "Data saved successfully"]);
            exit();
        } else {
            echo json_encode(["error" => $stmt->error]);
            exit();
        }

    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Retrieve all hazard reports from the database
        $stmt = $pdo->prepare("SELECT latitude, longitude, severity FROM hazard_reports");
        $stmt->execute();
        $hazardData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the data as JSON
        echo json_encode($hazardData);
        exit();
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error fetching hazard data: " . $e->getMessage()]);
        exit();
    }
}

?>