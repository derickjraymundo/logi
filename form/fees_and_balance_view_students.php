<?php
include "includes/session.php";

if(isset($_POST['search'])){
    $search = $_POST['search'];
    $response = [];

    try {
        $query = $conn->prepare("SELECT id, alternative_id, user_photo, CONCAT_WS(' ', lastname, firstname, middlename) as fullname FROM tbl_users 
                                 WHERE alternative_id LIKE :search OR CONCAT_WS(' ', lastname, firstname) LIKE :search 
                                 LIMIT 10");
        $likeSearch = "%$search%";
        $query->bindParam(':search', $likeSearch, PDO::PARAM_STR);
        $query->execute();
        
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $photoPath = !empty($row['user_photo']) ? '../images/user_photo/' . basename($row['user_photo']) : null; // ✅ Fixed Path
            $response[] = [
                'id' => $row['id'],
                'student_id' => $row['alternative_id'], // ✅ Corrected key
                'photo' => $photoPath,
                'fullname' => $row['fullname']
            ];
        }
    } catch (PDOException $e) {
        die(json_encode(["error" => "Query failed: " . $e->getMessage()]));
    }

    echo json_encode($response);
}
?>
