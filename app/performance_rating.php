<?php
include 'includes/session.php';

header('Content-Type: application/json');

if (!isset($_SESSION['SESS_USER_ID'])) {
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

try {
    if (isset($_POST['employee_id'])) {
        // Search for employee by ID
        $employee_id = $_POST['employee_id'];

        // Fetch employee details based on employee_id
        $sql = "SELECT id, CONCAT(firstname, ' ', lastname) AS name FROM tbl_users WHERE drivers_id = :employee_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':employee_id', $employee_id, PDO::PARAM_INT);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employee) {
            // If employee found, calculate their performance rating
            $user_id = $employee['id'];

            // Fetch all schedules and clock-in/out times for the employee
            $sql = "
                SELECT S.id, S.date_in, S.time_in, S.date_out, S.time_out, C.clock_in, C.clock_out 
                FROM SCHEDULE S 
                LEFT JOIN clock_in_out C ON S.user_id = C.user_id AND S.date_in = DATE(C.clock_in)
                WHERE S.user_id = :user_id
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            // Variables to store performance data
            $totalDays = 0;
            $onTimeDays = 0;
            $missedDays = 0;
            $lateDays = 0;

            // Loop through schedules to calculate performance
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $scheduledStart = strtotime($row['date_in'] . ' ' . $row['time_in']);
                $scheduledEnd = strtotime($row['date_out'] . ' ' . $row['time_out']);
                $clockIn = strtotime($row['clock_in']);
                $clockOut = strtotime($row['clock_out']);

                if ($clockIn && $clockOut) {
                    $totalDays++;

                    // Check if clock-in and clock-out are within scheduled times
                    if ($clockIn <= $scheduledStart && $clockOut >= $scheduledEnd) {
                        $onTimeDays++;
                    } elseif ($clockIn > $scheduledStart || $clockOut < $scheduledEnd) {
                        $lateDays++;
                    }
                } else {
                    $missedDays++;
                    $totalDays++;
                }
            }

            // Calculate the performance percentage based on on-time and missed days
            $performance = 0;
            if ($totalDays > 0) {
                $performance = ($onTimeDays / $totalDays) * 100;
            }

            // Calculate missed days and late days as factors
            $performance -= ($missedDays * 10); // Missed days reduce score by 10%
            $performance -= ($lateDays * 5); // Late days reduce score by 5%

            // Ensure performance doesn't go below 0%
            $performance = max($performance, 0);

            // Fetch the driver's booking details (only status = 3)
            $sql = "
                SELECT booking_id, froms, tos, booking_date, booking_status 
                FROM tbl_driver_book 
                WHERE driver_id = :user_id AND booking_status = 3
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            // Prepare the booking data
            $bookings = [];
            while ($booking = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bookings[] = [
                    'booking_id' => $booking['booking_id'],
                    'froms' => $booking['froms'],
                    'tos' => $booking['tos'],
                    'booking_date' => $booking['booking_date'],
                    'booking_status' => $booking['booking_status'] == 3 ? 'Completed' : 'Cancelled',
                ];
            }

            // Return the performance rating, employee details, and booking information
            echo json_encode([
                "employee_name" => $employee['name'],
                "performance" => round($performance, 2),
                "on_time_days" => $onTimeDays,
                "missed_days" => $missedDays,
                "late_days" => $lateDays,
                "bookings" => $bookings // Added bookings
            ]);
        } else {
            echo json_encode(["error" => "Employee not found"]);
        }
    } else {
        echo json_encode(["error" => "Employee ID not provided"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database Error: " . $e->getMessage()]);
}
?>
