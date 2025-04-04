<?php
include "includes/session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaction_id = $_POST['transaction_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_status = $_POST['payment_status'];
    $payer_email = $_POST['payer_email'];
    $payer_name = $_POST['payer_name'];
    $paypal_order_id = $_POST['paypal_order_id'];

    // Save payment in the database
    $stmt = $conn->prepare("INSERT INTO tbl_c_payments (transaction_id, amount, payment_status, payer_email, payer_name, paypal_order_id) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$transaction_id, $amount_paid, $payment_status, $payer_email, $payer_name, $paypal_order_id]);

    if ($stmt) {
        echo "Payment successfully recorded!";
    } else {
        echo "Error processing payment.";
    }
}
?>
