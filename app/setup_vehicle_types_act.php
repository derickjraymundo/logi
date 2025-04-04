<?php
include "includes/session.php";

if (isset($_POST['operation'])) {
    $operation = $_POST['operation'];

    if ($operation == 'add' || $operation == 'edit') {
        $id = (empty($_POST['text_1'])) ? NULL : $_POST['text_1'];
        $vehicle_type = (empty($_POST['text_2'])) ? NULL : strtoupper($_POST['text_2']);
        $uploaded_photo = isset($_FILES['text_4']) ? $_FILES['text_4'] : null;

        $upload_dir = '../images/vehicle_types/'; // Directory to save images
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
                    $stmt = $conn->prepare("SELECT photo FROM tbl_setup_vehicle_types WHERE id = :id");
                    $stmt->execute(['id' => $id]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $item_photo = $row ? $row['photo'] : ''; // Keep existing photo
                } else {
                    // Append directory path to the file name if a new photo was uploaded
                    $item_photo = $upload_dir . $item_photo;
                }

                $stmt = $conn->prepare("UPDATE tbl_setup_vehicle_types SET vehicle_type_name = :vehicle_type_name, photo = :photo WHERE id = :id");
                $stmt->execute([
                    'vehicle_type_name' => $vehicle_type,
                    'photo' => $item_photo,
                    'id' => $id
                ]);

                if ($stmt) {
                    $output = ["success", "Success", "Vehicle Type Details Updated."];
                } else {
                    $output = ["error", "Error", "Failed to update details."];
                }
            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'vehicle_type_name')) {
                    $output = ["error", "Error", "Vehicle Type Already Saved"];
                } else {
                    $output = ["error", "Error", $e->getMessage()];
                }
            }
        } else {
            // Insert new item
            try {
                // If no image is uploaded, set default value or NULL
                $item_photo = $item_photo ? $upload_dir . $item_photo : '';

                $stmt = $conn->prepare("INSERT INTO tbl_setup_vehicle_types(vehicle_type_name, photo, added_by) 
                VALUES (:vehicle_type_name, :photo, :added_by)");
                $stmt->execute([
                    'vehicle_type_name' => $vehicle_type,
                    'photo' => $item_photo,
                    'added_by' => $_SESSION['SESS_USER_ID']
                ]);

                if ($stmt) {
                    $output = ["success", "Success", $vehicle_type . " Successfully Added"];
                } else {
                    $output = ["error", "Error", "Failed to add vehicle type."];
                }
            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'vehicle_type_name')) {
                    $output = ["error", "Error", "Vehicle Type Already Saved"];
                } else {
                    $output = ["error", "Error", $e->getMessage()];
                }
            }
        }
    } elseif ($operation == 'delete') {
        if (isset($_POST['text_1'])) {
            $id = $_POST['text_1'];
            try {
                $stmt = $conn->prepare("UPDATE tbl_setup_vehicle_types SET isDeleted = (CASE WHEN isDeleted = 1 THEN 0 ELSE 1 END) WHERE id = :id");
                $stmt->execute(['id' => $id]);

                if ($stmt) {
                    $output = ["success", "Success", "Vehicle Type Updated."];
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
    $stmt = $conn->prepare("SELECT * FROM tbl_setup_vehicle_types");
    $stmt->execute();
    $records = $stmt->fetchAll();
    $data = array();
    foreach ($records as $row) {
        $data[] = array(
            "row1" => $row['id'],
            "row2" => $row['vehicle_type_name'],
            "row3" => $row['photo'],
            "row4" => $row['isDeleted']
        );
    }
    $response = array(
        "aaData" => $data
    );

    echo json_encode($response);
    $pdo->close();
}
?>