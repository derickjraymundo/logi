<?php
include "includes/session.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = $_POST['transaction_id'];
    $amount = $_POST['amount'];

    // Validate input
    if ($amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid payment amount']);
        exit;
    }

    // Insert payment record
    $stmt = $conn->prepare("
        INSERT INTO tbl_c_payments (transaction_id, amount, added_by, payed_date)
        VALUES (:transaction_id, :amount, :added_by, NOW())
    ");
    
    $result = $stmt->execute([
        'transaction_id' => $transaction_id,
        'amount' => $amount,
        'added_by'=>$_SESSION['SESS_USER_ID']
    ]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
}
?>
