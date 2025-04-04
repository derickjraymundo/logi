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

    // Filters
    if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
        $whereClause .= " AND S.date_in BETWEEN :start_date AND :end_date";
        $params[':start_date'] = $_POST['start_date'];
        $params[':end_date'] = $_POST['end_date'];
    } elseif (!empty($_POST['month']) && !empty($_POST['year'])) {
        $whereClause .= " AND MONTH(S.date_in) = :month AND YEAR(S.date_in) = :year";
        $params[':month'] = $_POST['month'];
        $params[':year'] = $_POST['year'];
    }

    // Query to fetch performance data
    $sql = "SELECT 
    U.id, 
    CONCAT(U.firstname, ' ', U.lastname) AS employee_name,
    COUNT(S.id) AS total_days,
    SUM(CASE WHEN C.clock_in <= S.time_in AND C.clock_out >= S.time_out THEN 1 ELSE 0 END) AS on_time_days,
    SUM(CASE WHEN C.clock_in IS NULL THEN 1 ELSE 0 END) AS missed_days,
    SUM(CASE WHEN C.clock_in > S.time_in OR C.clock_out < S.time_out THEN 1 ELSE 0 END) AS late_days,
    -- Ensure no negative performance and adjust for 0%
    GREATEST(
        ((SUM(CASE WHEN C.clock_in <= S.time_in AND C.clock_out >= S.time_out THEN 1 ELSE 0 END) / NULLIF(COUNT(S.id), 0)) * 100)
        - (SUM(CASE WHEN C.clock_in IS NULL THEN 1 ELSE 0 END) * 10)
        - (SUM(CASE WHEN C.clock_in > S.time_in OR C.clock_out < S.time_out THEN 1 ELSE 0 END) * 5),
        0 -- Minimum value is 0
    ) AS performance
FROM tbl_users U
JOIN SCHEDULE S ON U.id = S.user_id
LEFT JOIN clock_in_out C ON U.id = C.user_id AND S.date_in = DATE(C.clock_in)
WHERE 1=1 $whereClause
GROUP BY U.id
ORDER BY performance DESC
LIMIT 10
";

    $stmt = $conn->prepare($sql);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();
    $topPerformers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["top_performers" => $topPerformers]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database Error: " . $e->getMessage()]);
}
