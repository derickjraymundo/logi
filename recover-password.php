<?php 
  include "includes/session.php";
  $conn = $pdo->open();

require 'vendor/autoload.php'; // PHPMailer (Ensure it's installed)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM tbl_users WHERE email_address = :email_address");
    $stmt->execute(['email_address' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(50)); // Generate a secure token
        $stmt = $conn->prepare("UPDATE tbl_users SET reset_token = :token, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email_address = :email_address");
        $stmt->execute(['token' => $token, 'email_address' => $email]);

        $resetLink = "http://localhost/cashier_sms/reset-password.php?token=" . $token;
        
        // Send Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'turstpell@gmail.com';
            $mail->Password = 'lgka jwoc xxzo ypei';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('turstpell@gmail.com', 'Cashier SMS');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click <a href='$resetLink'>here</a> to reset your password. This link will expire in 1 hour.";
            $mail->send();
            
            echo "Password reset link has been sent to your email.";
        } catch (Exception $e) {
            echo "Error sending email: " . $mail->ErrorInfo;
        }
    } else {
        echo "Email not found.";
    }
}


?>