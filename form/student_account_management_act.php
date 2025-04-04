<?php
include "includes/session.php";

if (isset($_POST['operation'])) {
    $operation = $_POST['operation'];

    if ($operation == 'add' || $operation == 'edit') {
        $id = (empty($_POST['text_1'])) ? NULL : $_POST['text_1'];
        $suffix = (empty($_POST['text_2'])) ? NULL : strtoupper($_POST['text_2']);
        $lastname = (empty($_POST['text_3'])) ? NULL : strtoupper($_POST['text_3']);
        $uploaded_photo = isset($_FILES['text_4']) ? $_FILES['text_4'] : null;
        $firstname = (empty($_POST['text_5'])) ? NULL : strtoupper($_POST['text_5']);
        $middlename = (empty($_POST['text_6'])) ? NULL : strtoupper($_POST['text_6']);
        $usertype = (empty($_POST['text_7'])) ? NULL : strtoupper($_POST['text_7']);
        $email_address = (empty($_POST['text_8'])) ? NULL : strtolower($_POST['text_8']);
        $gender = (empty($_POST['text_9'])) ? NULL : $_POST['text_9'];
        $facility_id = (empty($_POST['text_10'])) ? NULL : $_POST['text_10'];
        $password = (empty($_POST['text_11'])) ? NULL : $_POST['text_11'];
        
        // Handle image upload
        $item_photo = NULL;
        if ($uploaded_photo && $uploaded_photo['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../images/user_photo/'; // Directory to save images
            $ext = strtolower(pathinfo($uploaded_photo['name'], PATHINFO_EXTENSION));
            $item_photo = uniqid('item_') . '.' . $ext; // Generate unique name
            $target_file = $upload_dir . $item_photo;

            // Validate file type and size
            $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($ext, $valid_extensions)) {
                echo json_encode(["error", "Error", "Invalid file type. Only JPG, PNG, and GIF allowed."]);
                exit;
            }

            if ($uploaded_photo['size'] > 2 * 1024 * 1024) { // 2MB limit
                echo json_encode(["error", "Error", "File size exceeds 2MB."]);
                exit;
            }

            // Attempt to move the uploaded file
            if (!move_uploaded_file($uploaded_photo['tmp_name'], $target_file)) {
                echo json_encode(["error", "Error", "Failed to upload image."]);
                exit;
            }
        }

        if ($operation == 'edit') {
            try {
                // Use existing image if no new image is uploaded
                $current_photo = $item_photo ?? $_POST['current_photo']; // Fallback to current photo if no new image

                $stmt = $conn->prepare("UPDATE tbl_users SET suffix = :suffix, lastname = :lastname, firstname =:firstname, middlename =:middlename, 
                    user_type_id = :user_type_id,  user_photo = :user_photo, email_address=:email_address, gender_id = :gender_id, branch_id = :branch_id
                
                 WHERE id = :id");
                $stmt->execute([
                    'suffix' => $suffix,
                    'lastname'=>$lastname,
                    'firstname'=>$firstname,
                    'middlename'=>$middlename,
                    'user_type_id'=>$usertype,
                    'user_photo' =>$target_file, 
                    'email_address' =>$email_address, 
                    'gender_id' =>$gender, 
                    'branch_id' =>$facility_id, 
                    'id' =>$id
                ]);

                if ($stmt) {
                    $output = array("success", "Success", "User Details is Updated.");
                } else {
                    $output = array("error", "Error", $stmt);
                }
            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'email_address')) {
                    $output = array("error", "Error", "Email Address Already Saved");
                } else {
                    $output = array("error", "Error", $e->getMessage());
                }
            }
        } else {
            // Insert new item
            try {
                // If no image is uploaded during 'add', $item_photo will be NULL
                if (!$item_photo) {
                    $item_photo = ''; // Or use a default image if preferred
                }


                $stmt = $conn->prepare("INSERT INTO tbl_users(suffix, lastname, firstname, middlename, user_photo, user_type_id, email_address, gender_id, 
                    branch_id, password, added_by) 
                VALUES
                 (:suffix, :lastname, :firstname, :middlename, :user_photo, :user_type_id, :email_address, :gender_id, 
                    :branch_id, :password, :added_by)");
                $stmt->execute([
                    'suffix' => $suffix,
                    'lastname' => $lastname,
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'user_photo' => $target_file,
                    'user_type_id' => $usertype,
                    'email_address'=>$email_address,
                    'gender_id'=>$gender,
                    'branch_id'=>$facility_id,
                    'password'=>password_hash($password, PASSWORD_DEFAULT),
                    'added_by' => $_SESSION['SESS_USER_ID']
                ]);

                if ($stmt) {
                    $output = array("success", "Success", $lastname. " " .$firstname . " Successfully Added");
                } else {
                    $output = array("error", "Error", $stmt);
                }
            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'email_address')) {
                    $output = array("error", "Error", "Email Already Saved");
                } else {
                    $output = array("error", "Error", $e->getMessage());
                }
            }
        }
    } elseif ($operation == 'delete') {
        if (isset($_POST['text_1'])) {
            $id = $_POST['text_1'];
            try {
                $stmt = $conn->prepare("UPDATE tbl_users SET isDeleted = (CASE WHEN isDeleted = 1 THEN 0 ELSE 1 END) WHERE id = :id");
                $stmt->execute(['id' => $id]);

                if ($stmt) {
                    $output = array("success", "Success", "User Status Updated.");
                } else {
                    $output = array("error", "Error", $stmt);
                }
            } catch (PDOException $e) {
                $output = array("error", "Error", $e->getMessage());
            }
        }
    }

    echo json_encode($output);
    $pdo->close();
}

if (isset($_POST['tbl_1'])) {
    $stmt = $conn->prepare("SELECT 
                                a.id,
                                a.lastname,
                                a.firstname,
                                a.middlename,
                                a.suffix,
                                a.user_photo,
                                b.id AS usertype_id,
                                b.user_type_name,
                                a.isDeleted,
                                a.email_address,
                                a.branch_id,
                                c.branch_name,
                                a.gender_id,
                                d.gender_name
                            FROM
                                tbl_users a
                            LEFT JOIN
                                tbl_setup_user_type b ON b.id = a.user_type_id
                            LEFT JOIN
                                tbl_setup_branch c on c.id = a.branch_id
                            LEFT JOIN
                                tbl_setup_gender d on d.id = a.gender_id
                            WHERE a.id !=  :id
                            AND a.user_type_id = :user_type_id
                            ");
    $stmt->execute(['id'=>$_SESSION['SESS_USER_ID'], 'user_type_id'=>3]);
    $records = $stmt->fetchAll();
    $data = array();
    foreach ($records as $row) {
        $data[] = array(
            "row1" => $row['id'],
            "row2" => $row['lastname'] .", ".  $row['firstname'] . " ".  $row['middlename'] ." ".  $row['suffix'],
            "row3" => $row['user_photo'],
            "row4" => $row['lastname'],
            "row5" => $row['firstname'],
            "row6" => $row['middlename'],
            "row7" => $row['suffix'],
            "row8" => $row['isDeleted'],
            "row9" => $row['usertype_id'],
            "row10" => $row['user_type_name'],
            "row11" => $row['email_address'],
            "row12" => $row['gender_id'],
            "row16" => $row['gender_name'],
            "row13" => $row['isDeleted'],
            "row14" => $row['branch_id'],
            "row15" => $row['branch_name']
            
        );
    }
    $response = array(
        "aaData" => $data
    );

    echo json_encode($response);
    $pdo->close();
}
?>
