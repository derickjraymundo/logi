<?php
include "includes/session.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $requested_by = $_POST["requested_by"];
    $purpose = $_POST["purpose"];

    try {
        $sql = "UPDATE tbl_vehicle_rollouts SET requested_by = :requested_by, purpose = :purpose WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':requested_by' => $requested_by,
            ':purpose' => $purpose
        ]);
        echo "Request updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
