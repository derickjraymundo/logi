<?php
include "includes/session.php";

    try {
        $stmt = $conn->prepare("TRUNCATE TABLE tbl_form_cargoes_import;");
        $stmt->execute();

        if($stmt) {
            $_SESSION['success_truncate'] = "Cargoes Data Reset";
        }

    } catch (PDOException $e) {
  
        $_SESSION['error_truncate'] = "Failed to Reset Dta: " . $e->getMessage();
     
    }

    header("Location: cargoes_import.php");
    exit();

?>
