<?php
include "includes/session.php";

if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    $stmt = $conn->prepare("
        SELECT 
            id,
            amount,
            payed_date 
        FROM tbl_c_payments
        WHERE transaction_id = :transaction_id
    ");
    $stmt->execute(['transaction_id' => $transaction_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
}
?>
