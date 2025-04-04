<?php
// Fetch data from your database
include "includes/session.php";

header('Content-Type: application/json');

// Example: Get search query from the AJAX request
$query = isset($_GET['q']) ? $_GET['q'] : '';

// Create your SQL query
// This example assumes you're using PDO, but you can modify it based on your database method
// Query data from your table
$stmt = $conn->prepare("SELECT id, consignee_name as name FROM tbl_setup_consignee WHERE isDeleted = 0 AND consignee_name LIKE :query LIMIT 30");
$stmt->execute(['query' => '%' . $query . '%']);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the data in Select2 format
$data = [
    'items' => [],
    'total_count' => 0
];

if ($results) {
    foreach ($results as $row) {
        $data['items'][] = [
            'id' => $row['id'],
            'text' => $row['name']
        ];
    }
    $data['total_count'] = count($results); // Set the total count for pagination
}

// Return the data as JSON
echo json_encode($data);
