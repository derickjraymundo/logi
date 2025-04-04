<?php
include "includes/session.php";

if (isset($_POST['current_password'])) {
    $user_id = $_SESSION['SESS_USER_ID'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current password from database
    $stmt = $conn->prepare("SELECT password FROM tbl_users WHERE id =:id");
    $stmt->execute(['id'=>$user_id]);
    $row = $stmt->fetch();

    if (!password_verify($current_password, $row['password'])) {
        $_SESSION['errorpass'] = "Current password is incorrect!";
        header("Location: profile.php");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['errorpass'] = "New passwords do not match!";
        header("Location: profile.php");
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_query = $conn->prepare("UPDATE tbl_users SET password = :password WHERE id = :id");
    $update_query->execute(['password'=>$hashed_password, 'id'=>$user_id]);

    if($update_query) {

        $_SESSION['successpass'] = "Password changed successfully!";
    }else {
        $_SESSION['errorpass'] = "Failed to change password: " . $update_query;

    }

    header("Location: profile.php");
    exit();
}
?>
