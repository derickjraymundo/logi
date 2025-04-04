<?php
include "includes/session.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $requested_by = $_POST["requested_by"];
    $purpose = $_POST["purpose"];
    $date_vehicle_needed = $_POST["date_vehicle_needed"];
    $time_vehicle_needed = $_POST["time_vehicle_needed"];

    try {
        $sql = "INSERT INTO tbl_vehicle_rollouts (requested_by, purpose, requested_date, date_vehicle_needed, time_vehicle_needed, status) 
        VALUES (:requested_by, :purpose, NOW(), :date_vehicle_needed, :time_vehicle_needed, 'Pending')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':requested_by' => $requested_by,
            ':purpose' => $purpose,
            ':date_vehicle_needed' => $date_vehicle_needed,
            ':time_vehicle_needed' => $time_vehicle_needed
        ]);
        echo "Request submitted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
