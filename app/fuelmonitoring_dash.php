<?php
include 'includes/session.php';

header('Content-Type: application/json');

if (!isset($_SESSION['SESS_USER_ID'])) {
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

try {
    $whereClause = "WHERE isDeleted = 0";
    $params = [];

    if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
        $whereClause .= " AND transaction_date BETWEEN :start_date AND :end_date";
        $params[':start_date'] = $_POST['start_date'];
        $params[':end_date'] = $_POST['end_date'];
    } elseif (!empty($_POST['month']) && !empty($_POST['year'])) {
        $whereClause .= " AND MONTH(transaction_date) = :month AND YEAR(transaction_date) = :year";
        $params[':month'] = $_POST['month'];
        $params[':year'] = $_POST['year'];
    }

    $sql = "
        SELECT DATE(transaction_date) AS date, 
               SUM(before_arrived) AS total_before, 
               SUM(after_arrived) AS total_after 
        FROM tbl_fuel_monitoring 
        $whereClause
        GROUP BY DATE(transaction_date)
        ORDER BY date ASC
    ";

    $stmt = $conn->prepare($sql);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();
    $fuelData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($fuelData);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database Error: " . $e->getMessage()]);
}
