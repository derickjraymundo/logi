<?php
include "includes/session.php"; // Ensure $conn is available

if (isset($_POST['rollout_id']) && isset($_POST['vehicle_id']) && isset($_POST['remarks'])) {
    $rollout_id = $_POST['rollout_id'];
    $vehicle_type = $_POST['vehicle_type'];
    $vehicle_id = $_POST['vehicle_id'];
    $remarks = $_POST['remarks'];
    $admin_replied_date = date("Y-m-d H:i:s");

    try {
        $sql = "UPDATE tbl_vehicle_rollouts 
                SET admin_vehicle_type= ?, admin_vehicle_id = ?, admin_remarks = ?, admin_replied_date = ?, status = 'Assigned'
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$vehicle_type, $vehicle_id, $remarks, $admin_replied_date, $rollout_id]);

        echo "Vehicle assigned successfully!";
    } catch (PDOException $e) {
        echo "Error updating record: " . $e->getMessage();
    }
} else {
    echo "Invalid request!";
}
?>
