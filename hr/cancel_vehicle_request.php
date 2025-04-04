<?php
include "includes/session.php";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    try {
        $id = $_POST["id"];
        
        // Update the request status to "Cancelled"
        $sql = "UPDATE tbl_vehicle_rollouts SET status = 'Cancelled' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo "success";
        } else {
            echo "No changes made.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
