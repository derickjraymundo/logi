<?php
include "includes/session.php"; // Ensure $conn is available

if (isset($_POST['vehicle_type'])) {
    $vehicle_type = $_POST['vehicle_type'];

    try {
        $sql = "SELECT id, CONCAT(' ', 
                    (SELECT manufacturer_name FROM tbl_setup_vehicle_manufacturers WHERE id = v.make), 
                    '-', '(', v.model, ')') AS vehicle_name 
                FROM vehicles v 
                WHERE v.isActive = 1 AND v.vehicle_type = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$vehicle_type]);
        $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<option value=''>Select Vehicle</option>";
        foreach ($vehicles as $vehicle) {
            echo "<option value='{$vehicle['id']}'>{$vehicle['vehicle_name']}</option>";
        }
    } catch (PDOException $e) {
        echo "<option value=''>Error loading vehicles</option>";
    }
} else {
    echo "<option value=''>Invalid Request</option>";
}
?>
