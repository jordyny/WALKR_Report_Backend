<?php
require 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get the Google API key from environment variables
$googleApiKey = $_ENV['GOOGLE_API_KEY'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hazard Heatmap</title>
    <!-- Pass the Google API key from PHP to JavaScript -->
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&libraries=visualization"></script>
    <style>
        #map {
            height: 100vh;
            width: 100%;
        }
    </style>
</head>
<body>
    <div>
        <h1>Report Hazard</h1>
        <form id="hazardForm">
            <label for="latitude">Latitude:</label>
            <input type="number" id="latitude" required><br>
            <label for="longitude">Longitude:</label>
            <input type="number" id="longitude" required><br>
            <label for="hazard_type">Hazard Type:</label>
            <input type="text" id="hazard_type" required><br>
            <label for="severity">Severity:</label>
            <input type="number" id="severity" min="1" max="10" required><br>
            <button type="submit">Submit Hazard</button>
        </form>
    </div>

    <div id="map"></div>

    <script src="script.js"></script>
</body>
</html>