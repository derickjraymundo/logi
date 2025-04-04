<?php
include "includes/session.php";


if (isset($_POST['operation'])) {
    $operation = $_POST['operation'];

    if ($operation == 'add' || $operation == 'edit') {
        $id = (empty($_POST['text_1'])) ? NULL : $_POST['text_1'];
        $port_name = (empty($_POST['text_2'])) ? NULL : strtoupper($_POST['text_2']);

        if ($operation == 'edit') {
            // Update existing subject
            try {
                $stmt = $conn->prepare("UPDATE tbl_setup_ports SET ports_name = :ports_name WHERE id = :id");
                $stmt->execute(['ports_name' => $port_name, 'id' => $id]);

                if ($stmt) {
                    $output = array("success","Success", "Port Updated");
                } else {
                    $output = array("error","Error", $stmt);
                }

            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'ports_name')) {
                    $output = array("error","Error", "Port Already Saved");
                } else {
                    $output = array("error","Error", $e->getMessage());
                }
            }
        } else {
            // Insert new subject
            try {
                $stmt = $conn->prepare("INSERT INTO tbl_setup_ports(ports_name, added_by) VALUES (:ports_name, :added_by)");
                $stmt->execute(['ports_name' => $port_name, 'added_by' => $_SESSION['SESS_USER_ID']]);

                if ($stmt) {
                    $output = array("success","Success", $port_name. " Succesfully Added");
                } else {
                    $output = array("error","Error", $stmt);
                }

            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'ports_name')) {
                    $output = array("error","Error", "Port Already Saved");
                } else {
                    $output = array("error","Error", $e->getMessage());
                }
            }
        }
    } elseif ($operation == 'delete') {
        // Delete subject
        if (isset($_POST['text_1'])) {
            $id = $_POST['text_1'];
            try {
                $stmt = $conn->prepare("UPDATE tbl_setup_ports SET isDeleted = (CASE WHEN isDeleted = 1 THEN 0 ELSE 1 END) WHERE id = :id");
                $stmt->execute(['id' => $id]);

                if ($stmt) {
                    $output = array("success","Success", "Port's Status Updated.");
                } else {
                    $output = array("error","Error", $stmt);
                }

            } catch (PDOException $e) {
                $output = array("error","Error", $e->getMessage());
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
                INSERT INTO tbl_setup_ports (
                    ports_name, added_by
                ) VALUES ( 
                    :ports_name, :added_by
                ) ON DUPLICATE KEY UPDATE 
                    ports_name = VALUES(ports_name),
                    updated_by = '".$_SESSION['SESS_USER_ID']."',
                    updated_date = CURRENT_TIMESTAMP()
            ");
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {


                // Skip the row if all required fields are empty
                if (empty($row[0])) {
                    continue; // Skip this iteration
                }

                $stmt->execute(['ports_name'=>strtoupper($row[0]),
                            'added_by'=>$_SESSION['SESS_USER_ID']
            ]);
            }
    
            fclose($handle);
            $output = array("success","Success", "Origins succesfully Uploaded.");
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

    $stmt = $conn->prepare("SELECT * FROM tbl_setup_ports");
    $stmt->execute();
    $records = $stmt->fetchAll();
    $data = array();
    foreach ($records as $row) {
        $row1           = $row['id'];
        $row2           = $row['ports_name'];
        $row3           = $row['isDeleted'];
        // $row3           = $row['announcement_text'];
        // $row4           = $row['action_date'];
        // $row5           = isDeleted($row['isDeleted'], "");
 
        $data[] = array(
            "row1" => $row1,
            "row2" => $row2,
            "row3" => $row3
            // "row4" => $row4,
            // "row5" => $row5,
            // "row6" => $row6
        );
    }
    $response = array(
        "aaData" => $data
    );

    echo json_encode($response);
    $pdo->close();
}

?>
