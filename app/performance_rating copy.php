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

        // Get the start and end dates from POST (if provided)
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

        // Fetch employee details based on employee_id
        $sql = "SELECT id, CONCAT(firstname, ' ', lastname) AS name FROM tbl_users WHERE drivers_id = :employee_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':employee_id', $employee_id, PDO::PARAM_INT);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employee) {
            // If employee found, calculate their performance rating
            $user_id = $employee['id'];

            // Fetch all schedules and clock-in/out times for the employee, applying date range filters if provided
            $sql = "
                SELECT S.id, S.date_in, S.time_in, S.date_out, S.time_out, C.clock_in, C.clock_out 
                FROM SCHEDULE S 
                LEFT JOIN clock_in_out C ON S.user_id = C.user_id AND S.date_in = DATE(C.clock_in)
                WHERE S.user_id = :user_id
            ";

            // Apply date range filtering if provided
            if ($start_date && $end_date) {
                $sql .= " AND S.date_in BETWEEN :start_date AND :end_date";
            } elseif ($start_date) {
                $sql .= " AND S.date_in >= :start_date";
            } elseif ($end_date) {
                $sql .= " AND S.date_in <= :end_date";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

            // Bind date range parameters if provided
            if ($start_date) {
                $stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
            }
            if ($end_date) {
                $stmt->bindValue(':end_date', $end_date, PDO::PARAM_STR);
            }

            $stmt->execute();

            $totalDays = 0;
            $onTimeDays = 0;
            $missedDays = 0;
            $lateDays = 0;

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

            // Return the performance rating and employee details
            echo json_encode([
                "employee_name" => $employee['name'],
                "performance" => round($performance, 2),
                "on_time_days" => $onTimeDays,
                "missed_days" => $missedDays,
                "late_days" => $lateDays
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
