<?php
include 'includes/session.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['SESS_USER_ID'])) {
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

$user_id = $_SESSION['SESS_USER_ID'];

try {
    $sql = "SELECT * FROM SCHEDULE WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $schedules = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Adjust end time if necessary (e.g., if time_out is not set, use a default)
        if (!$row['time_out']) {
            // Default to 1 hour after time_in if time_out is not set
            $row['time_out'] = date('H:i:s', strtotime($row['time_in'] . ' +1 hour'));
        }
    
        $schedules[] = [
            'id' => $row['id'],
            'title' => 'Scheduled',
            'start' => $row['date_in'] . 'T' . $row['time_in'],
            'end' => $row['date_out'] . 'T' . $row['time_out'],
            'color' => '#28a745'  // Green color for scheduled events
        ];
    }

    echo json_encode($schedules);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database Error: " . $e->getMessage()]);
}
?>
