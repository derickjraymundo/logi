<?php
    include "includes/session.php";


    $long = $_POST['longitude'];
    $lat  = $_POST['latitude'];

    try {
        
        $stmt = $conn->prepare("UPDATE tbl_driver_book SET froms_lat = :froms_lat, froms_long = :froms_long WHERE driver_id = :driver_id
                AND booking_status = :booking_status");

        $stmt->execute(['froms_lat'=>$lat, 'froms_long'=>$long, 'driver_id'=>$_SESSION['SESS_USER_ID'], 'booking_status'=>1 ]); 

        if($stmt) {
            echo "success";
        }else {
            echo $stmt;
        }


    }catch(PDOException $e) {
        echo $e->getMessage();
    }

?>