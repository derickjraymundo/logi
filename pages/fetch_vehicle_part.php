<?php
include "includes/session.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("SELECT * FROM tbl_v_vehicles_parts WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode(["status" => "success", "data" => $result]);
    } else {
        echo json_encode(["status" => "error", "message" => "No data found"]);
    }
}
?>
