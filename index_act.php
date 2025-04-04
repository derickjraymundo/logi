<?php
	include 'includes/session.php';
	$conn = $pdo->open();

	if(isset($_POST['login'])) {
		$username 	= $_POST['email'];
		$password 	= $_POST['password'];

		try{   

            $stmt = $conn->prepare("SELECT 
								*, a.id as user, b.user_type_name , c.gender_name, (SELECT r.vehicle_type_name FROM tbl_setup_vehicle_types r WHERE r.id = a.vehicle_type_id) as vehicletype
							FROM tbl_users a
								LEFT JOIN
									tbl_setup_user_type b ON b.id = a.user_type_id 
								LEFT JOIN 
									tbl_setup_gender c ON c.id = a.gender_id
							WHERE a.email_address = :email_address AND a.isDeleted = 0
						");
            $stmt->execute(['email_address'=>$username]);
            $countstmt = $stmt->rowCount();

            if($countstmt == 0) {
                $output = array("danger","Error", "Invalid Username or Password.");
            }else {
                $ftc = $stmt->fetch();

                if(password_verify($password, $ftc['password'])){

					if($ftc['isDeleted']) {

						$output = array("danger","Error", "Account Deativated.");
					}else {						
							$_SESSION['SESS_USER_ID'] = $ftc['user']; 
							$_SESSION['SESS_USER_TYPE'] = $ftc['user_type_id']; 
							$_SESSION['SESS_USER_TYPE_NAME'] = $ftc['user_type_name']; 
							$_SESSION['SESS_USER_LASTNAME'] = $ftc['lastname']; 
							$_SESSION['SESS_USER_FIRSTNAME'] = $ftc['firstname'];
							$_SESSION['SESS_USER_MIDDLENAME'] = $ftc['middlename'];
							$_SESSION['SESS_USER_SUFFIX'] = $ftc['suffix'];
							$_SESSION['SESS_USER_ADDEDDATE'] = $ftc['added_date'];
							$_SESSION['SESS_USER_PHOTO'] = $ftc['user_photo'];
							$_SESSION['SESS_USER_GENDER_ID'] = $ftc['gender_id'];
							$_SESSION['SESS_USER_GENDER'] = $ftc['gender_name'];
							$_SESSION['SESS_USER_EMAIL'] = $ftc['email_address'];
							$_SESSION['SESS_USER_REGDATE'] = $ftc['added_date'];
							$_SESSION['SESS_USER_LICENSE'] = $ftc['license_number'];
							$_SESSION['SESS_USER_LICENSE_EXPIRY'] = $ftc['license_expire_date'];
							$_SESSION['SESS_USER_CONTACT'] = $ftc['contact_number'];
							$_SESSION['SESS_USER_ADDRESS'] = $ftc['address'];
							$_SESSION['SESS_USER_VEHICLE_TYPE'] = $ftc['vehicletype'];
							$_SESSION['SESS_USER_VEHICLE_PLATE'] = $ftc['vehicle_plate_number'];
							$output = array('success', 'Success', 'Redirecting...');
						}

                }else {
                    $output = array("danger","Error", "Invalid Username or Password.");
                }

            }
                
		}catch(PDOException $e){
            $output = $e->getMessage();
		}
	

		$pdo->close();
		echo json_encode($output);
		exit();
	}
	
?>