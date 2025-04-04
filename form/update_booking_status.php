<?php
    include "includes/session.php";


    if(isset($_POST['updateBook'])) { 

        $id = $_POST['bookingId'];
        $status = $_POST['status'];


        try {
            
            if($status == 1) {

                $from_lat = $_POST['latitude'];
                $from_long = $_POST['longitude'];

                $stmt = $conn->prepare("UPDATE tbl_driver_book SET booking_status = :booking_status, froms_lat =:froms_lat, froms_long=:froms_long WHERE id = :id");
                $stmt->execute(['booking_status'=>$status, 'froms_lat'=>$from_lat, 'froms_long'=>$from_long, 'id'=>$id]);

                    
                if($stmt) {
                    $output = array("success", "Success", "Status Updated.");
                }else {
                    $output = array("error", "Error Found", $stmt);
                }
            }else {

         
                $stmt = $conn->prepare("UPDATE tbl_driver_book SET booking_status = :booking_status WHERE id = :id");
                $stmt->execute(['booking_status'=>$status, 'id'=>$id]);
                
                
                if($stmt) {
                    $output = array("success", "Success", "Status Updated.");
                }else {
                    $output = array("error", "Error Found", $stmt);
                }

            }


    
    
        }catch(PDOException $e) {
    
            $output = array("error", "Error Found", $e->getMessage());
        }  
    
        echo json_encode($output);
        
    }
 
    
?>