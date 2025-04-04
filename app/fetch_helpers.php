<?php
include "includes/session.php"; // Ensure this includes the database connection `$conn`

try {
    // Query to get helpers
    $query = "SELECT id, lastname, firstname, middlename FROM tbl_users WHERE user_type_id = 4 AND isDeleted = 0";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetch all helpers as an associative array
    $helpers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format data to include full name and safe JSON encoding
    $formattedHelpers = array_map(function ($helper) {
        return [
            "id" => $helper["id"],
            "lastname" => htmlspecialchars($helper["lastname"], ENT_QUOTES, 'UTF-8'),
            "firstname" => htmlspecialchars($helper["firstname"], ENT_QUOTES, 'UTF-8'),
            "middlename" => htmlspecialchars($helper["middlename"] ?? "", ENT_QUOTES, 'UTF-8'),
            "fullname" => htmlspecialchars($helper["lastname"] . ", " . $helper["firstname"] . " " . ($helper["middlename"] ?? ""), ENT_QUOTES, 'UTF-8')
        ];
    }, $helpers);

    // Return formatted JSON
    echo json_encode($formattedHelpers);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
