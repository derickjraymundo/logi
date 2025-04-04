<?php
include "includes/session.php";

if (isset($_POST['operation'])) {
    $operation = $_POST['operation'];

    if ($operation == 'add' || $operation == 'edit') {
        $id = (empty($_POST['text_1'])) ? NULL : $_POST['text_1'];
        $vendor_name = (empty($_POST['text_2'])) ? NULL : strtoupper($_POST['text_2']);
        $vendor_type = (empty($_POST['text_3'])) ? NULL : strtoupper($_POST['text_3']);
        $vendor_organization = (empty($_POST['text_5'])) ? NULL : strtoupper($_POST['text_5']);
        
        $uploaded_photo = isset($_FILES['text_4']) ? $_FILES['text_4'] : null;

        $upload_dir = '../images/vendor/'; // Directory to save images
        $item_photo = NULL; // Initialize as NULL

        if ($uploaded_photo && $uploaded_photo['error'] === UPLOAD_ERR_OK) {
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
                // Get current photo from database if no new image is uploaded
                if (!$item_photo) {
                    $stmt = $conn->prepare("SELECT photo FROM tbl_setup_vendors WHERE id = :id");
                    $stmt->execute(['id' => $id]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $item_photo = $row ? $row['photo'] : ''; // Keep existing photo
                } else {
                    // Append directory path to the file name if a new photo was uploaded
                    $item_photo = $upload_dir . $item_photo;
                }

                $stmt = $conn->prepare("UPDATE tbl_setup_vendors SET vendor_name = :vendor_name, 
                    organization = :organization, vendor_type =:vendor_type,
                    photo = :photo WHERE id = :id");
                $stmt->execute([
                    'vendor_name' => $vendor_name,
                    'organization' => $vendor_organization,
                    'vendor_type' => $vendor_type,
                    'photo' => $item_photo,
                    'id' => $id
                ]);

                if ($stmt) {
                    $output = ["success", "Success", "Vendor Details Updated."];
                } else {
                    $output = ["error", "Error", "Failed to update details."];
                }
            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'vendor_name')) {
                    $output = ["error", "Error", "Vendor Already Saved"];
                } else {
                    $output = ["error", "Error", $e->getMessage()];
                }
            }
        } else {
            // Insert new item
            try {
                // If no image is uploaded, set default value or NULL
                $item_photo = $item_photo ? $upload_dir . $item_photo : '';

                $stmt = $conn->prepare("INSERT INTO tbl_setup_vendors(vendor_name, organization, vendor_type, photo, added_by) 
                VALUES (:vendor_name, :organization, :vendor_type, :photo, :added_by)");
                $stmt->execute([
                    'vendor_name' => $vendor_name,
                    'organization' => $vendor_organization,
                    'vendor_type' => $vendor_type,
                    'photo' => $item_photo,
                    'added_by' => $_SESSION['SESS_USER_ID']
                ]);

                if ($stmt) {
                    $output = ["success", "Success", $vendor_name . " Successfully Added"];
                } else {
                    $output = ["error", "Error", "Failed to add Vendor."];
                }
            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'vendor_name')) {
                    $output = ["error", "Error", "Vendor Already Saved"];
                } else {
                    $output = ["error", "Error", $e->getMessage()];
                }
            }
        }
    } elseif ($operation == 'delete') {
        if (isset($_POST['text_1'])) {
            $id = $_POST['text_1'];
            try {
                $stmt = $conn->prepare("UPDATE tbl_setup_vendors SET isDeleted = (CASE WHEN isDeleted = 1 THEN 0 ELSE 1 END) WHERE id = :id");
                $stmt->execute(['id' => $id]);

                if ($stmt) {
                    $output = ["success", "Success", "Vendor Updated."];
                } else {
                    $output = ["error", "Error", "Failed to update."];
                }
            } catch (PDOException $e) {
                $output = ["error", "Error", $e->getMessage()];
            }
        }
    }

    echo json_encode($output);
}


if (isset($_POST['tbl_1'])) {
    $stmt = $conn->prepare("SELECT a.id, a.vendor_name, a.vendor_type, b.vendor_type_name,
            a.organization, a.photo, a.isDeleted
         FROM tbl_setup_vendors a
        LEFT JOIN tbl_setup_vendor_type b on b.id = a.vendor_type
         ");
    $stmt->execute();
    $records = $stmt->fetchAll();
    $data = array();
    foreach ($records as $row) {
        $data[] = array(
            "row1" => $row['id'],
            "row2" => $row['vendor_name'],
            "row3" => $row['vendor_type'],
            "row7" => $row['vendor_type_name'],
            "row4" => $row['organization'],
            "row5" => $row['photo'],
            "row6" => $row['isDeleted']
        );
    }
    $response = array(
        "aaData" => $data
    );

    echo json_encode($response);
    $pdo->close();
}
?>