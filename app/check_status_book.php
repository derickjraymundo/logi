<?php
    include "includes/session.php";

    if(isset($_POST['chk_status'])) {

        $driver_id = $_POST['driverId'];

        $ftc = $conn->prepare("SELECT booking_status FROM tbl_driver_book WHERE driver_id = :driver_id AND booking_status NOT IN('3','2') ");
        $ftc->execute(['driver_id'=>$driver_id]);

        $row = $ftc->fetch();

        $output = array($row['booking_status']);


        echo json_encode($output);

        $pdo->close();
    }


?>