document.getElementById('hazardForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const latitude = parseFloat(document.getElementById('latitude').value);
    const longitude = parseFloat(document.getElementById('longitude').value);
    const hazard_type = document.getElementById('hazard_type').value;
    const severity = parseInt(document.getElementById('severity').value);
    
    // Send data to the PHP script to store in the database
    $.ajax({
        url: 'heatMap.php', // Your PHP file
        method: 'POST',
        data: {
            latitude: latitude,
            longitude: longitude,
            hazard_type: hazard_type,
            severity: severity
        },
        success: function(response) {
            console.log('Hazard submitted successfully:', response);
            // After submitting, refresh the heatmap
            loadHeatmap();
        },
        error: function() {
            console.error('Error:', error);
        }
    });
});

// Initialize the OpenLayers map
let map = new ol.Map({
    target: 'map',
    layers: [
        new ol.layer.Tile({
            source: new ol.source.OSM()
        })
    ],
    view: new ol.View({
        center: ol.proj.fromLonLat([0, 0]),
        zoom: 2
    })
});

// Function to load heatmap data from the database
function loadHeatmap() {
    $.ajax({
        url: 'heatMap.php?action=get',
        method: 'GET',
        success: function(data) {
            const heatmapData = data.map(item => ({
                lat: item.latitude,
                lng: item.longitude,
                value: item.severity
            }));

            // Use Heatmap.js to create the heatmap layer
            let heatmapLayer = new ol.layer.Heatmap({
                source: new ol.source.Vector({
                    features: heatmapData.map(function(coord) {
                        return new ol.Feature({
                            geometry: new ol.geom.Point(ol.proj.fromLonLat([coord.lng, coord.lat])),
                            weight: coord.value
                        });
                    })
                }),
                blur: 15,
                radius: 10
            });

            // Add heatmap layer to the map
            map.addLayer(heatmapLayer);
        },
        error: function() {
            console.error('Error loading heatmap data:', error);
        }
    });
}

// Load heatmap on page load
loadHeatmap();
