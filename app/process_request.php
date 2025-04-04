<?php
include "includes/session.php"; // Change this to your actual DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = intval($_POST['id']);

    if ($_POST['action'] === 'approve') {
        $stmt = $conn->prepare("UPDATE tbl_vehicle_requests SET request_status = 'Approved' WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($_POST['action'] === 'cancel') {
        $stmt = $conn->prepare("UPDATE tbl_vehicle_requests SET request_status = 'Cancelled' WHERE id = ?");
        $stmt->execute([$id]);
    }

    echo "success";
}
?>
