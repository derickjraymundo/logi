<?php
include "includes/session.php";

if (isset($_POST['operation'])) {
    $operation = $_POST['operation'];

    if ($operation == 'add' || $operation == 'edit') {
        $id = (empty($_POST['text_1'])) ? NULL : $_POST['text_1'];
        $item_name = (empty($_POST['text_2'])) ? NULL : strtoupper($_POST['text_2']);
        $item_description = (empty($_POST['text_3'])) ? NULL : strtoupper($_POST['text_3']);
        $uploaded_photo = isset($_FILES['text_4']) ? $_FILES['text_4'] : null;

        // Handle image upload
        $item_photo = NULL;
        if ($uploaded_photo && $uploaded_photo['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../images/items/'; // Directory to save images
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

                $stmt = $conn->prepare("UPDATE tbl_setup_item SET item_name = :item_name, item_photo = :item_photo, item_description = :item_description WHERE id = :id");
                $stmt->execute([
                    'item_name' => $item_name,
                    'item_photo' => $current_photo,  // Use either new or existing photo
                    'item_description' => $item_description,
                    'id' => $id
                ]);

                if ($stmt) {
                    $output = array("success", "Success", "Item Updated");
                } else {
                    $output = array("error", "Error", $stmt);
                }
            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'item_name')) {
                    $output = array("error", "Error", "Item Already Saved");
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

                $stmt = $conn->prepare("INSERT INTO tbl_setup_item(item_name, item_photo, item_description, added_by) VALUES (:item_name, :item_photo, :item_description, :added_by)");
                $stmt->execute([
                    'item_name' => $item_name,
                    'item_photo' => $item_photo,
                    'item_description' => $item_description,
                    'added_by' => $_SESSION['SESS_USER_ID']
                ]);

                if ($stmt) {
                    $output = array("success", "Success", $item_name . " Successfully Added");
                } else {
                    $output = array("error", "Error", $stmt);
                }
            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'item_name')) {
                    $output = array("error", "Error", "Item Already Saved");
                } else {
                    $output = array("error", "Error", $e->getMessage());
                }
            }
        }
    } elseif ($operation == 'delete') {
        if (isset($_POST['text_1'])) {
            $id = $_POST['text_1'];
            try {
                $stmt = $conn->prepare("UPDATE tbl_setup_item SET isDeleted = (CASE WHEN isDeleted = 1 THEN 0 ELSE 1 END) WHERE id = :id");
                $stmt->execute(['id' => $id]);

                if ($stmt) {
                    $output = array("success", "Success", "Item Status Updated.");
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

if (isset($_FILES['text_4_1']) && $_FILES['text_4_1']['error'] == 0) {
    $file_tmp = $_FILES['text_4_1']['tmp_name'];
    $file_ext = pathinfo($_FILES['text_4_1']['name'], PATHINFO_EXTENSION);

    // Validate file type (only CSV allowed)
    if (strtolower($file_ext) !== 'csv') {
        echo json_encode(["danger", "Error", "Only CSV files are allowed."]);
        exit();
    }

    try {
        if (($handle = fopen($file_tmp, "r")) !== FALSE) {
            // Skip the first row if it contains headers
          // Skip the first row (header)
            fgetcsv($handle);

            // Prepare SQL statement with `ON DUPLICATE KEY UPDATE`
            $stmt = $conn->prepare("
                INSERT INTO tbl_setup_item (
                    item_name, item_description, added_by
                ) VALUES ( 
                    :item_name, :item_description, :added_by
                ) ON DUPLICATE KEY UPDATE 
                    item_name = VALUES(item_name),
                    item_description = VALUES(item_description),
                    updated_by = '".$_SESSION['SESS_USER_ID']."',
                    updated_date = CURRENT_TIMESTAMP()
            ");
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {


                // Skip the row if all required fields are empty
                if (empty($row[0])) {
                    continue; // Skip this iteration
                }

                $stmt->execute(['item_name'=>strtoupper($row[0]),
                            'item_description'=>strtoupper($row[1]),
                            'added_by'=>$_SESSION['SESS_USER_ID']
            ]);
            }
    
            fclose($handle);
            $output = array("success","Success", "Item's succesfully Uploaded.");
        } else {
            $output = array("danger","Error", "There's An error uploading the file.");
        }

    }catch(PDOException $e) {
        $output = array("danger","Error", $e->getMessage());
    }

    echo json_encode($output);
    $pdo->close();
   
}



if (isset($_POST['tbl_1'])) {
    $stmt = $conn->prepare("SELECT * FROM tbl_setup_item");
    $stmt->execute();
    $records = $stmt->fetchAll();
    $data = array();
    foreach ($records as $row) {
        $data[] = array(
            "row1" => $row['id'],
            "row2" => $row['item_name'],
            "row3" => $row['item_photo'],
            "row4" => $row['item_description'],
            "row5" => $row['isDeleted']
        );
    }
    $response = array(
        "aaData" => $data
    );

    echo json_encode($response);
    $pdo->close();
}
?>
