<?php
include "includes/session.php"; // Ensure this includes the database connection `$conn`

if (isset($_GET['driver_id'])) {
    $driver_id = $_GET['driver_id'];

    $query = "SELECT latitude, longitude, updated_at FROM tbl_driver_location WHERE driver_id = ? ORDER BY updated_at DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute([$driver_id]);
    $location = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($location) {
        echo json_encode([
            'status' => 'success',
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
            'updated_at' => $location['updated_at']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No location found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
