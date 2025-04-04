<?php
include "includes/session.php";  // Include session and database connection
$conn = $pdo->open();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists and is still valid
    $stmt = $conn->prepare("SELECT id, email_address FROM tbl_users WHERE reset_token = :token AND reset_token_expires > NOW()");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if (!$user) {
        echo "Invalid or expired token.";
        exit;
    }
} else {
    echo "Token is required.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "Passwords do not match!";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password and clear the token
        $stmt = $conn->prepare("UPDATE tbl_users SET password = :password, reset_token = NULL, reset_token_expires = NULL WHERE id = :id");
        $stmt->execute([
            'password' => $hashed_password,
            'id' => $user['id']
        ]);

        echo "Password reset successful. <a href='index.php'>Login</a>";
        exit;
    }
}

$pdo->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Reset Password</b></a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Enter your new password.</p>
            <form method="POST">
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" placeholder="New Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                    </div>
                </div>
            </form>
            <p class="mt-3 mb-1">
                <a href="index.php">Back to Login</a>
            </p>
        </div>
    </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
