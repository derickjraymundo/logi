<?php
include "includes/session.php";

if (isset($_GET['driverId'])) {
    $driverId = $_GET['driverId'];

    // Prepare the SQL query
    $query = "SELECT froms_lat, froms_long, tos_lat, tos_long FROM tbl_driver_book WHERE driver_id = :driverId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":driverId", $driverId, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Driver not found"]);
    }
    exit;
}
?>
