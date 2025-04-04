<?php
include "includes/session.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $vehicle_parts_id = $_POST['vehicle_parts_id'];
    $vehicle_parts_lifespan = $_POST['vehicle_parts_lifespan'];

    $stmt = $conn->prepare("UPDATE tbl_v_vehicles_parts 
                            SET vehicle_parts_id = :vehicle_parts_id, 
                                vehicle_parts_lifespan = :vehicle_parts_lifespan 
                            WHERE id = :id");

    $stmt->bindParam(":vehicle_parts_id", $vehicle_parts_id, PDO::PARAM_INT);
    $stmt->bindParam(":vehicle_parts_lifespan", $vehicle_parts_lifespan, PDO::PARAM_INT);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
