async function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: { lat: 40.73061, lng: -73.935242 }, // Default center
    });

    // Fetch hazard data from your backend
    const response = await fetch('https://your-backend-url.com/getHazards'); // Replace with your backend URL
    const data = await response.json();

    // Convert data to LatLng objects and apply weighting based on severity
    const heatmapData = data.map(item => ({
        location: new google.maps.LatLng(item.latitude, item.longitude),
        weight: item.severity, // Severity to weigh each point
    }));

    // Create the heatmap layer and add it to the map
    const heatmap = new google.maps.visualization.HeatmapLayer({
        data: heatmapData,
        map: map,
        radius: 20, // Adjust radius as needed
    });

    // Optional: Customize the heatmap gradient
    heatmap.set('gradient', [
        'rgba(0, 255, 255, 0)',
        'rgba(0, 255, 255, 1)',
        'rgba(0, 191, 255, 1)',
        'rgba(0, 127, 255, 1)',
        'rgba(0, 63, 255, 1)',
        'rgba(0, 0, 255, 1)',
        'rgba(63, 0, 255, 1)',
        'rgba(127, 0, 255, 1)',
        'rgba(191, 0, 255, 1)',
        'rgba(255, 0, 255, 1)',
    ]);
}

// Handle the form submission
document.getElementById("hazardForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    const latitude = document.getElementById("latitude").value;
    const longitude = document.getElementById("longitude").value;
    const hazardType = document.getElementById("hazard_type").value;
    const severity = document.getElementById("severity").value;

    // Post the hazard data to the backend
    await fetch('https://your-backend-url.com', {  // Replace with your backend URL
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            latitude: latitude,
            longitude: longitude,
            hazard_type: hazardType,
            severity: severity
        })
    });

    // Re-fetch and update the map
    initMap();
});

// Initialize the map
initMap();
