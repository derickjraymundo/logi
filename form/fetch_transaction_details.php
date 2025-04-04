<?php
include "includes/session.php";

$transaction_id = $_POST['transaction_id'];

// Fetch items
$stmt = $conn->prepare("
    SELECT s.item_name, i.amount 
    FROM tbl_c_transactions_items i 
    JOIN tbl_c_setup_items s ON i.item_id = s.id
    WHERE i.transaction_id = ?
");
$stmt->execute([$transaction_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch payments
$stmt = $conn->prepare("
    SELECT amount, payed_date 
    FROM tbl_c_payments 
    WHERE transaction_id = ?
");
$stmt->execute([$transaction_id]);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'items' => $items,
    'payments' => $payments
]);
?>
