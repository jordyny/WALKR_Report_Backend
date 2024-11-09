<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hazard Heat Map</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=visualization"></script>
</head>
<body>
    <div id="map" style="height: 600px; width: 100%;"></div>
    <script>
        async function initMap() {
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: { lat: 40.7128, lng: -74.0060 },
                mapTypeId: 'roadmap'
            });

            // Fetch hazard data
            const response = await fetch('get_hazards.php');
            const data = await response.json();

            // Prepare data for heat map
            const heatMapData = data.map(item => ({
                location: new google.maps.LatLng(item.latitude, item.longitude),
                weight: item.severity
            }));

            // Create heat map layer
            const heatmap = new google.maps.visualization.HeatmapLayer({
                data: heatMapData,
                map: map
            });
        }

        window.onload = initMap;
    </script>
</body>
</html>



