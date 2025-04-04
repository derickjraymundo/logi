<?php
	include 'conn.php';

    $conn = $pdo->open();

	session_start();


	if(isset($_SESSION['SESS_USER_ID'])) {
	
		if( $_SESSION['SESS_USER_TYPE'] == 1 ) {
			header('Location: app/dashboard');  
			
		}else if( $_SESSION['SESS_USER_TYPE'] == 3 ) {
			header('Location: form/dashboard');  

		}else if( $_SESSION['SESS_USER_TYPE'] == 2 ) {
			header('Location: pages/dashboard');  
		}else if( $_SESSION['SESS_USER_TYPE'] == 5) {
			header('Location: hr/dashboard');  

		}else {
			header('Location: index');
		}
	
	}


	

?>