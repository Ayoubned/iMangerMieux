<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport and Running Map Services</title>
    <link rel="stylesheet" href="css/map.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div id="controls-container">
        <h1>Control Panel</h1>
        <div id="controls">
            <div id="search-bar">
                <input type="text" id="search-box" placeholder="Enter location">
                <button onclick="searchLocation()">Search</button>
            </div>
            <div>
                <label for="length">Distance (km):</label>
                <input type="number" id="length" placeholder="Enter distance" min="1" step="0.5">
            </div>
            <button id="toggle-locations-btn" onclick="switchService('locations')">Show Locations</button>
            <button onclick="switchService('path')">Generate Path</button>
        </div>
    </div>
    <div id="map"></div>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=initMap"
        async
        defer>
    </script>
    <script src="js/map.js"></script>
</body>
</html>
