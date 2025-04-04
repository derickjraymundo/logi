<?php
include "includes/session.php";

$student_id = $_GET['student_id'];
$transaction_id = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : '';

$query = "SELECT 
            a.id,
            a.transaction_name,
            a.total,
            a.total - COALESCE((SELECT SUM(b.amount) FROM tbl_c_payments b WHERE b.transaction_id = a.id), 0) AS remaining_balance,
            a.date_ordered
          FROM tbl_c_transactions a
          WHERE a.student_id = :student_id";

$params = ['student_id' => $student_id];

if (!empty($transaction_id)) {
    $query .= " AND a.id = :transaction_id";
    $params['transaction_id'] = $transaction_id;
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($transactions);
?>
