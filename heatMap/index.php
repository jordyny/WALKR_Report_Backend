<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hazard Heat Map</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=visualization"></script>
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h2>Hazard Density Heat Map</h2>
    <div id="map"></div>

    <script>
        async function initMap() {
            // Initialize the Google Map centered at a default location
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: { lat: 40.7128, lng: -74.0060 },  // Default to New York City
                mapTypeId: "roadmap"
            });

            // Fetch hazard data from the backend
            const response = await fetch("path/to/your/combined_php_file.php");
            const hazards = await response.json();

            // Prepare heatmap data with hazard points and severity as weight
            const heatMapData = hazards.map(hazard => ({
                location: new google.maps.LatLng(hazard.latitude, hazard.longitude),
                weight: hazard.severity  // Adjusting intensity by severity
            }));

            // Create the heat map layer
            const heatmap = new google.maps.visualization.HeatmapLayer({
                data: heatMapData,
                map: map
            });

            // Optional: Configure the heatmap appearance
            heatmap.set("radius", 20);  // Adjust the radius for denser/larger heat spots
            heatmap.set("opacity", 0.6);
        }

        // Initialize the map when the page loads
        window.onload = initMap;
    </script>
</body>
</html>
