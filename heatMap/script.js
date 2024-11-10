$(document).ready(function() {
    console.log("Document is ready");

    let hazardData;
   // Function to handle hazard data submission
    function submitHazardData(latitude, longitude, hazardType, severity) {
        hazardData = {
            latitude: latitude,
            longitude: longitude,
            hazard_type: hazardType,
            severity: severity,
            heatMap: true // identifier for heat map
        }; 

        console.log("Hazard data stored in global variable:", hazardData);

        $.ajax({
            url: 'heatMap.php', // PHP script to handle the data
            method: 'POST',
            data: hazardData,
            success: function(response) {
                console.log('Hazard submitted successfully:', response);
                // Optionally, refresh the heatmap after submission
                // loadHeatmap();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error - Status:', status);
                console.error('AJAX Error - Message:', error);
                console.error('AJAX Response:', xhr.responseText); // Get the server's response text
            }
        });
    }

    // Event listener for the hazard form submission
    $('#hazardForm').on('submit', function(event) {
        event.preventDefault(); // Prevent form submission

        // Get values from the form inputs
        const latitude = $('#latitude').val();
        const longitude = $('#longitude').val();
        const hazardType = $('#hazard_type').val();
        const severity = $('#severity').val();


        console.log('Latitude:', latitude);
        console.log('Longitude:', longitude);
        console.log('Hazard Type:', hazardType);
        console.log('Severity:', severity);
        
        // Submit the hazard data to the PHP script
        submitHazardData(latitude, longitude, hazardType, severity);
    });

    function loadHeatmap() {
        $.ajax({
            url: 'heatMap.php', // PHP script to retrieve the hazard data
            method: 'GET',
            success: function(data) {
                console.log('Data received for heatmap:', data);

                // Create an array to hold the heatmap data points
                let heatmapData = [];

                // Process the data received from the server
                data.forEach(function(item) {
                    // Push each hazard's latitude, longitude, and severity into the heatmap data
                    heatmapData.push([item.latitude, item.longitude, item.severity]);
                });

                // // Initialize the Leaflet map
                // var map = L.map('map').setView([51.505, -0.09], 13); // Example center point and zoom level

                // // Add OpenStreetMap layer to the map
                // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                // }).addTo(map);

                // // Add heatmap layer using the heatmap.js plugin
                // var heat = L.heatLayer(heatmapData, {radius: 25, blur: 15, maxZoom: 17}).addTo(map);
            },
            error: function(xhr, status, error) {
                console.error('Error loading heatmap data:', error);
            }
        });
    }

    // Load the heatmap when the page loads
    loadHeatmap();

    
});

// // Initialize the OpenLayers map
// let map = new ol.Map({
//     target: 'map',
//     layers: [
//         new ol.layer.Tile({
//             source: new ol.source.OSM()
//         })
//     ],
//     view: new ol.View({
//         center: ol.proj.fromLonLat([0, 0]),
//         zoom: 2
//     })
// });

// // Function to load heatmap data from the database
// function loadHeatmap() {
//     $.ajax({
//         url: 'heatMap.php?action=get',
//         method: 'GET',
//         success: function(data) {
//             const heatmapData = data.map(item => ({
//                 lat: item.latitude,
//                 lng: item.longitude,
//                 value: item.severity
//             }));

//             // Use Heatmap.js to create the heatmap layer
//             let heatmapLayer = new ol.layer.Heatmap({
//                 source: new ol.source.Vector({
//                     features: heatmapData.map(function(coord) {
//                         return new ol.Feature({
//                             geometry: new ol.geom.Point(ol.proj.fromLonLat([coord.lng, coord.lat])),
//                             weight: coord.value
//                         });
//                     })
//                 }),
//                 blur: 15,
//                 radius: 10
//             });

//             // Add heatmap layer to the map
//             map.addLayer(heatmapLayer);
//         },
//         error: function() {
//             console.error('Error loading heatmap data:', error);
//         }
//     });
// }

// // Load heatmap on page load
// loadHeatmap();
