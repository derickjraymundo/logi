<?php
include 'includes/session.php'; // Assuming the connection is set up in session.php

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['SESS_USER_ID'])) {
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

$user_id = $_SESSION['SESS_USER_ID']; // Get the logged-in user's ID

// Query to fetch clock-in and clock-out times for the logged-in user
$sql = "SELECT * FROM clock_in_out WHERE user_id = :user_id ORDER BY clock_in DESC";
$stmt = $conn->prepare($sql); // Assuming $conn is your PDO connection

// Bind parameters
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

// Execute the query
$stmt->execute();

// Fetch results
$clockInOutEvents = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $clock_in = new DateTime($row['clock_in']);
    $clock_out = new DateTime($row['clock_out'] ?? $row['clock_in']); // Use clock_in if clock_out is null

    // Add events to array with formatted start and end times
    $clockInOutEvents[] = array(
        'id' => $row['id'],
        'title' => 'Clock In/Out',
        'start' => $clock_in->format('Y-m-d\TH:i:s'),
        'end' => $clock_out->format('Y-m-d\TH:i:s'),
        'color' => '#007bff' // You can change the color if you want
    );
}

// Close the connection
$stmt->closeCursor();

// Output the events as JSON
echo json_encode($clockInOutEvents);
?>
