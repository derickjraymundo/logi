<?php
include "includes/session.php";

$filter = $_GET['filter'] ?? 'weekly';
$currentDate = date("Y-m-d");

if ($filter === "weekly") {
    $query = "SELECT DATE(delievered_date) AS date, COUNT(*) AS count 
              FROM tbl_vehicle_requests 
              WHERE delievered_date IS NOT NULL 
              AND delievered_date >= DATE_SUB(:currentDate, INTERVAL 7 DAY) 
              GROUP BY DATE(delievered_date)";
} else {
    $query = "SELECT DATE_FORMAT(delievered_date, '%Y-%m') AS date, COUNT(*) AS count 
              FROM tbl_vehicle_requests 
              WHERE delievered_date IS NOT NULL 
              AND delievered_date >= DATE_SUB(:currentDate, INTERVAL 1 MONTH) 
              GROUP BY DATE_FORMAT(delievered_date, '%Y-%m')";
}

$stmt = $conn->prepare($query);
$stmt->bindParam(":currentDate", $currentDate);
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($data);
?>
