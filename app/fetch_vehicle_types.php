<?php
include "includes/session.php"; // Ensure $conn is available

try {
    $sql = "SELECT id, vehicle_type_name FROM tbl_setup_vehicle_types WHERE isDeleted = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $vehicleTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<option value=''>Select Vehicle Type</option>";
    foreach ($vehicleTypes as $type) {
        echo "<option value='{$type['id']}'>{$type['vehicle_type_name']}</option>";
    }
} catch (PDOException $e) {
    echo "<option value=''>Error loading vehicle types</option>";
}
?>
