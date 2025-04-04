<?php 
include "includes/session.php"; // Ensure this includes the database connection `$conn`

header("Content-Type: application/json");

try {
    // Ensure all required fields are present
    $required_fields = ["driver_id", "to_location", "booking_date", "booking_time", "work_purpose"];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Retrieve and sanitize input values
    $driver_id = $_POST["driver_id"];
    $from_location = $_POST["from_location"];
    $to_location = $_POST["to_location"];
    $tos_lat = $_POST["to_lat"];
    $tos_long = $_POST["to_long"];
    $booking_date = $_POST["booking_date"];
    $work_time = $_POST["booking_time"];
    $work_purpose = $_POST["work_purpose"];
    $helpers = isset($_POST["helpers"]) ? json_decode($_POST["helpers"], true) : [];

    // Validate JSON decoding
    if (!is_array($helpers)) {
        throw new Exception("Invalid helpers data received.");
    }

    function generateBookingID($conn) {
        do {
            $randomString = strtoupper(bin2hex(random_bytes(5))); // Generates 10-character unique ID
            $booking_id = "TR-" . $randomString;

            // Check if the generated ID already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_driver_book WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
            $exists = $stmt->fetchColumn();
        } while ($exists > 0); // Repeat if ID already exists

        return $booking_id;
    }

    $booking_id = generateBookingID($conn);

    // Insert booking into `tbl_driver_book`
    $stmt = $conn->prepare("INSERT INTO tbl_driver_book (driver_id, tos_lat, tos_long, booking_id, froms, tos, booking_date, booking_remarks, booking_status) 
                            VALUES (:driver_id, :tos_lat, :tos_long,  :booking_id, :from_location, :to_location, :booking_date, :booking_remarks, 0)");
    $stmt->execute([
        ":driver_id" => $driver_id,
        ":tos_lat" => $tos_lat,
        ":tos_long" => $tos_long,
        ":booking_id" => $booking_id,
        ":from_location" => $from_location,
        ":to_location" => $to_location,
        ":booking_date" => $booking_date . " " . $work_time,
        ":booking_remarks" => $work_purpose
    ]);

    // Get last inserted booking ID
    $booking_id = $conn->lastInsertId();

    // Mark driver as unavailable
    $stmt = $conn->prepare("UPDATE tbl_users SET rider_availability = 0 WHERE id = :driver_id");
    $stmt->execute([":driver_id" => $driver_id]);

    // Insert helpers if any
    if (!empty($helpers)) {
        error_log("Helpers found: " . print_r($helpers, true));
    
        $stmt = $conn->prepare("INSERT INTO tbl_helpers (booking_id, lastname, firstname, middlename) 
                                VALUES (:booking_id, :lastname, :firstname, :middlename)");
    
        foreach ($helpers as $helper) {
            if (isset($helper["lastname"], $helper["firstname"], $helper["middlename"])) {
                error_log("Inserting helper: " . print_r($helper, true));
    
                $stmt->execute([
                    ":booking_id" => $booking_id,
                    ":lastname" => $helper["lastname"],
                    ":firstname" => $helper["firstname"],
                    ":middlename" => $helper["middlename"]
                ]);
            } else {
                error_log("Missing keys in helper: " . print_r($helper, true));
            }
        }
    }

    // Success response
    echo json_encode(["success" => true, "message" => "Booking saved successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
