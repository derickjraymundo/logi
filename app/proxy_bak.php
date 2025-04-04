<?php
$api_key = "5b3ce3597851110001cf6248815c35339e694fde96ecb05c571c3629"; // Your OpenRouteService API Key
$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';

if (!$start || !$end) {
    echo json_encode(["error" => "Invalid start or end location"]);
    exit;
}

$url = "https://api.openrouteservice.org/v2/directions/driving-car?api_key=$api_key&start=$start&end=$end";

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Set header & return response
header('Content-Type: application/json');
echo $response;
?>
