<?php
include "includes/session.php"; // Include your DB connection

if (isset($_POST['vehicle_id']) && isset($_POST['lifespan'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $lifespan = $_POST['lifespan'];

    $stmt = $conn->prepare("UPDATE vehicles SET vehicle_lifespan = ? WHERE id = ?");
    if ($stmt->execute([$lifespan, $vehicle_id])) {
        echo "Lifespan updated successfully!";
    } else {
        echo "Error updating lifespan.";
    }
}
?>
