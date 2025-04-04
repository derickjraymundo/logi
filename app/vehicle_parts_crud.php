<?php
include "includes/session.php";

$action = $_GET['action'] ?? '';

if ($action == "add") {
    $vehicle_id = $_POST['vehicle_id'];
    $vehicle_parts_id = $_POST['vehicle_parts_id'];
    $vehicle_parts_lifespan = empty($_POST['vehicle_parts_lifespan']) ? NULL : $_POST['vehicle_parts_lifespan'];
    $added_by = $_POST['added_by'];
    $added_date = date("Y-m-d H:i:s");

    // Check if the vehicle part already exists
    $checkQuery = $conn->prepare("SELECT id FROM tbl_v_vehicles_parts WHERE vehicle_id = ? AND vehicle_parts_id = ?");
    $checkQuery->execute([$vehicle_id, $vehicle_parts_id]);
    $existingPart = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if ($existingPart) {
        // Update lifespan if the part exists
        $query = $conn->prepare("UPDATE tbl_v_vehicles_parts SET vehicle_parts_lifespan = ?, added_by = ?, added_date = ? WHERE id = ?");
        $result = $query->execute([$vehicle_parts_lifespan, $added_by, $added_date, $existingPart['id']]);

        echo json_encode(["success" => $result, "message" => $result ? "Part lifespan updated successfully" : "Failed to update part"]);
    } else {
        // Insert new record if the part does not exist
        $query = $conn->prepare("INSERT INTO tbl_v_vehicles_parts (vehicle_id, vehicle_parts_id, vehicle_parts_lifespan, added_by, added_date) VALUES (?, ?, ?, ?, ?)");
        $result = $query->execute([$vehicle_id, $vehicle_parts_id, $vehicle_parts_lifespan, $added_by, $added_date]);

        echo json_encode(["success" => $result, "message" => $result ? "Part added successfully" : "Failed to add part"]);
    }
}

if ($action == "delete") {
    $id = $_GET['id'];

    $query = $conn->prepare("DELETE FROM tbl_v_vehicles_parts WHERE id = ?");
    $result = $query->execute([$id]);

    echo json_encode(["success" => $result, "message" => $result ? "Part deleted successfully" : "Failed to delete part"]);
}
?>
