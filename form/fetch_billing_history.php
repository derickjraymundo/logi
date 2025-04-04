<?php
include "includes/session.php"; 

$student_id = $_SESSION['SESS_USER_ID'];

$stmt = $conn->prepare("
    SELECT 
        t.id,
        t.transaction_name,
        t.added_date,
        t.total,
        (t.total - COALESCE(SUM(p.amount), 0)) AS total_balance
    FROM tbl_c_transactions t
    LEFT JOIN tbl_c_payments p ON t.id = p.transaction_id
    WHERE t.student_id = ?
    GROUP BY t.id
");
$stmt->execute([$student_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($transactions);
?>
