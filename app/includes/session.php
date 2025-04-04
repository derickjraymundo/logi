<?php
    include '../includes/conn.php';
	session_start();


    if(empty($_SESSION['SESS_USER_ID'])){
        header('location: ../index');
        exit();
    }else if ($_SESSION['SESS_USER_TYPE'] != 1){
        header('location: ../index');
        exit();

    }else {
        $conn = $pdo->open();
    }


?>