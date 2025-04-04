<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Load PHPMailer

// Database connection

  include "includes/session.php";
  $conn = $pdo->open();

$username = "root";  // Default in XAMPP
$password = "";  // No password by default
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {

    // Query transactions due in 2 days
    $stmt = $conn->prepare("SELECT  
                                t.transaction_name,
                                (t.total - COALESCE(SUM(p.amount), 0)) AS remaining_balance,
                                t.due_date,
                                s.email_address AS student_email  
                            FROM tbl_c_transactions t
                            LEFT JOIN tbl_c_payments p ON t.id = p.transaction_id
                            LEFT JOIN tbl_users s ON t.student_id = s.id  
                            WHERE DATE(t.due_date) = DATE_ADD(CURDATE(), INTERVAL 2 DAY)
                            GROUP BY t.id
                            HAVING remaining_balance > 0");

    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($transactions) {
        foreach ($transactions as $txn) {
            $mail = new PHPMailer(true);
            try {
                // SMTP Configuration (Use Gmail for testing)
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Use your email provider
                $mail->SMTPAuth = true;
                $mail->Username = 'turstpell@gmail.com';  
                $mail->Password = 'lgka jwoc xxzo ypei';  
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email Settings
                $mail->setFrom('turstpell@gmail.com', 'Billing Department');
                $mail->addAddress($txn['student_email']); 
                $mail->isHTML(true);
                $mail->Subject = "Payment Reminder: {$txn['transaction_name']}";
                $mail->Body = "
                    <p>Dear Student,</p>
                    <p>Your transaction <b>{$txn['transaction_name']}</b> is due in <b>2 days</b>.</p>
                    <p>Amount Due: <b>₱" . number_format($txn['remaining_balance'], 2) . "</b></p>
                    <p>Due Date: <b>" . date('F d, Y', strtotime($txn['due_date'])) . "</b></p>
                    <p>Please ensure payment before the due date.</p>
                    <p>Thank you.</p>
                ";

                // Send Email
                $mail->send();
                echo "Reminder sent to {$txn['student_email']}<br>";
            } catch (Exception $e) {
                echo "Error sending email: " . $mail->ErrorInfo . "<br>";
            }
        }
    } else {
        echo "No upcoming payments due in 2 days.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
