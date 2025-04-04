<?php
include "includes/session.php";

if (isset($_POST['lastname'])) {
    try {
        $user_id = $_SESSION['SESS_USER_ID']; // Get logged-in user ID
        $lastname = strtoupper($_POST['lastname']);
        $firstname = strtoupper($_POST['firstname']);
        $middlename = strtoupper($_POST['middlename']);
        $suffix = strtoupper($_POST['suffix']);
        $email = strtolower($_POST['email']);
        $gender = $_POST['gender'];
        $branch = $_POST['branch'];

        $photo_sql = ""; // Default empty string for SQL query
        $params = [
            'lastname' => $lastname,
            'firstname' => $firstname,
            'middlename' => $middlename,
            'suffix' => $suffix,
            'email_address' => $email,
            'gender_id' => $gender,
            'branch_id' => $branch,
            'id' => $user_id
        ];

        // Handling profile photo upload
        if (!empty($_FILES['profile_photo']['name'])) {
            $photo_name = time() . "_" . $_FILES['profile_photo']['name'];
            $photo_tmp = $_FILES['profile_photo']['tmp_name'];
            $photo_destination = "../images/user_photo/" . $photo_name;

            if (move_uploaded_file($photo_tmp, $photo_destination)) {
                $photo_sql = ", user_photo = :user_photo";
                $params['user_photo'] = "../images/user_photo/".$photo_name;
            }
        }

        // Prepare SQL query
        $stmt = $conn->prepare("UPDATE tbl_users SET 
                    lastname = :lastname,
                    firstname = :firstname, 
                    middlename = :middlename,
                    suffix = :suffix,
                    email_address = :email_address,
                    gender_id = :gender_id,
                    branch_id = :branch_id
                    $photo_sql
                  WHERE id = :id");

        // Execute query with parameters
        if ($stmt->execute($params)) {
            $_SESSION['SESS_USER_LASTNAME'] = $lastname;
            $_SESSION['SESS_USER_FIRSTNAME'] = $firstname;
            $_SESSION['SESS_USER_MIDDLENAME'] = $middlename;
            $_SESSION['SESS_USER_SUFFIX'] = $suffix;
            $_SESSION['SESS_USER_EMAIL'] = $email;
            $_SESSION['SESS_USER_GENDER_ID'] = $gender;
            $_SESSION['SESS_USER_BRANCH'] = $branch;

            if (!empty($_FILES['profile_photo']['name'])) {
                $_SESSION['SESS_USER_PHOTO'] = "../images/user_photo/".$photo_name;
            }

    
            $stmtBranch = $conn->prepare("SELECT branch_name FROM tbl_setup_branch WHERE id = :branch_id");
            $stmtBranch->execute(['branch_id' => $branch]);
            $branchData = $stmtBranch->fetch(PDO::FETCH_ASSOC);
    
            $_SESSION['SESS_USER_BRANCH_NAME'] = $branchData ? $branchData['branch_name'] : "Unknown Branch";
    
            
            $_SESSION['success'] = "Profile updated successfully!";
        }

    } catch (PDOException $e) {
        // Check if the error is due to duplicate email
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = "The email address '$email' is already taken. Please use a different email.";
        } else {
            $_SESSION['error'] = "Failed to update profile: " . $e->getMessage();
        }
    }

    header("Location: profile.php");
    exit();
}
?>
