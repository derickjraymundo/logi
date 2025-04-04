<?php
include "includes/session.php"; // Adjust based on your project setup

if (isset($_GET['driver_id'])) {
    $driver_id = $_GET['driver_id'];

    // Fetch work details for the given driver
    $query = "SELECT b.id, b.froms, b.tos, b.booking_date, b.booking_remarks
              FROM tbl_driver_book b 
              WHERE b.driver_id = ? 
              ORDER BY b.booking_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute([$driver_id]);
    $workDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($workDetails) {
        echo "<div class='table-responsive'>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>To</th>
                            <th>Date & Time</th>
                            <th>Purpose</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($workDetails as $work) {
            $work_id = $work['id'];
            $dateTime = strtotime($work['booking_date']); // Convert to timestamp

            $date = date("Y-m-d", $dateTime); // Extract date
            $time = date("g:i A", $dateTime); // Convert time to 12-hour format (e.g., 4:00 PM)

            // Check if the date is today
            $today = date("Y-m-d");
            $displayDate = ($date == $today) ? "Today @ $time" : date("M d, Y", $dateTime) . " @ $time";

            echo "<tr>

                    <td>{$work['tos']}</td>
                    <td>{$displayDate}</td>
                    <td>{$work['booking_remarks']}</td>
                  </tr>";

            // Fetch helpers for this work entry
            $helperQuery = "SELECT CONCAT(h.firstname, ' ', h.lastname) AS helper_name FROM tbl_helpers h WHERE h.booking_id = ?";
            $helperStmt = $conn->prepare($helperQuery);
            $helperStmt->execute([$work_id]);
            $helpers = $helperStmt->fetchAll(PDO::FETCH_ASSOC);

            if ($helpers) {
                echo "<tr><td colspan='4'><strong>Helpers:</strong> ";
                $helperNames = array_column($helpers, 'helper_name');
                echo implode(", ", $helperNames);
                echo "</td></tr>";
            }
        }

        echo "</tbody></table></div>";
    } else {
        echo "<p class='text-center'>No work details found.</p>";
    }
} else {
    echo "<p class='text-center'>Invalid request.</p>";
}
?>
