$(document).ready(function() {

    // Function to handle hazard data submission
    function submitHazardData(latitude, longitude, hazardType, severity) {
        $.ajax({
            url: 'heatMap.php', // PHP script to handle the data
            method: 'POST',
            data: {
                latitude: latitude,
                longitude: longitude,
                hazard_type: hazardType,
                severity: severity
            },
            success: function(response) {
                console.log('Hazard submitted successfully:', response);
                // Optionally, refresh the heatmap after submission
                loadHeatmap();
            },
            error: function(error) {
                console.error('Error:', error);
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

        // Submit the hazard data to the PHP script
        submitHazardData(latitude, longitude, hazardType, severity);
    });
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
