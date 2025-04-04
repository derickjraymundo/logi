<?php
include "includes/session.php";

if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    $stmt = $conn->prepare("
        SELECT 
            a.id,
            b.item_name,
            a.amount
        FROM tbl_c_transactions_items a
        LEFT JOIN tbl_c_setup_items b ON b.id = a.item_id
        WHERE a.transaction_id = :transaction_id
    ");
    $stmt->execute(['transaction_id' => $transaction_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
}
?>
