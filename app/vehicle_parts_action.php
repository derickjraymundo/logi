<?php
include "includes/session.php";

$action = $_GET['action'] ?? '';

if ($action == "save") {
    $vehicle_id = $_POST['vehicle_id'];
    $vehicle_parts_id = $_POST['vehicle_parts_id'];
    $vehicle_parts_lifespan = (empty($_POST['vehicle_parts_lifespan'])) ? NULL : $_POST['vehicle_parts_lifespan'];
    $added_by = $_POST['added_by'];
    $added_date = date("Y-m-d H:i:s");

    // Check if part already exists for this vehicle
    $checkStmt = $conn->prepare("SELECT id FROM tbl_v_vehicles_parts WHERE vehicle_id = ? AND vehicle_parts_id = ?");
    $checkStmt->execute([$vehicle_id, $vehicle_parts_id]);
    $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Update existing record
        $query = $conn->prepare("UPDATE tbl_v_vehicles_parts SET vehicle_parts_lifespan = ?, added_by = ?, added_date = ? WHERE vehicle_id = ? AND vehicle_parts_id = ?");
        $result = $query->execute([$vehicle_parts_lifespan, $added_by, $added_date, $vehicle_id, $vehicle_parts_id]);
    } else {
        // Insert new record
        $query = $conn->prepare("INSERT INTO tbl_v_vehicles_parts (vehicle_id, vehicle_parts_id, vehicle_parts_lifespan, added_by, added_date) VALUES (?, ?, ?, ?, ?)");
        $result = $query->execute([$vehicle_id, $vehicle_parts_id, $vehicle_parts_lifespan, $added_by, $added_date]);
    }

    echo json_encode(["success" => $result, "message" => $result ? "Part saved successfully" : "Failed to save part", "ids"=>$vehicle_id]);
}

if ($action == "delete") {
    $id = $_GET['id'];
    $query = $conn->prepare("DELETE FROM tbl_v_vehicles_parts WHERE id = ?");
    $result = $query->execute([$id]);

    echo json_encode(["success" => $result, "message" => $result ? "Part deleted successfully" : "Failed to delete part"]);
}
?>
