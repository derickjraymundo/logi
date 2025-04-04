<?php
include 'includes/session.php';

header('Content-Type: application/json');

if (!isset($_SESSION['SESS_USER_ID'])) {
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

try {
    $whereClause = "";
    $params = [];

    if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
        $whereClause .= " AND booking_date BETWEEN :start_date AND :end_date";
        $params[':start_date'] = $_POST['start_date'];
        $params[':end_date'] = $_POST['end_date'];
    } elseif (!empty($_POST['month']) && !empty($_POST['year'])) {
        $whereClause .= " AND MONTH(booking_date) = :month AND YEAR(booking_date) = :year";
        $params[':month'] = $_POST['month'];
        $params[':year'] = $_POST['year'];
    }

    $sql = "
        SELECT 
            SUM(CASE WHEN booking_status = 3 THEN 1 ELSE 0 END) AS successful,
            SUM(CASE WHEN booking_status = 4 THEN 1 ELSE 0 END) AS unsuccessful
        FROM tbl_driver_book
        WHERE 1=1 $whereClause
    ";

    $stmt = $conn->prepare($sql);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database Error: " . $e->getMessage()]);
}
