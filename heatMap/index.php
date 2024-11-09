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
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&callback=initMap&libraries=visualization" async defer></script>
    <style>
        #map {
            height: 100vh;
            width: 100%;
        }
    </style>
</head>
<body>
    <div>
        <h1>Test Google Maps API</h1>
        <p id="message">Loading...</p>
    </div>

    <div id="map"></div>

    <script>
        // Function to initialize the map and test the API key
        function initMap() {
            // Show a message in the browser to confirm the key is working
            document.getElementById("message").textContent = "Hello, Google Maps API is working!";

            // Create a map centered on a specific location (e.g., New York City)
            var map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 40.7128, lng: -74.0060 },
                zoom: 10
            });

            // Optionally, you can add a marker to test further
            var marker = new google.maps.Marker({
                position: { lat: 40.7128, lng: -74.0060 },
                map: map,
                title: "New York City"
            });
        }
    </script>
</body>
</html>
