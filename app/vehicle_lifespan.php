<?php
include "includes/session.php";
include "includes/header.php";
?>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Lifespan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">View Lifespan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <!-- <th>#</th> -->
                            <th>Model</th>
                            <th>License Plate</th>
                            <th>Part Name</th>
                            <th>Lifespan (Months)</th>
                            <th>Added Date</th>
                            <th>Remaining Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch vehicle parts that are nearing expiration (within 60 days)
                        $stmt = $conn->prepare("
                            SELECT t.model, t.license_plate, v.id, v.vehicle_parts_lifespan, v.added_date, s.vehicle_parts_name
                            FROM tbl_v_vehicles_parts v
                            INNER JOIN tbl_setup_vehicle_parts s ON v.vehicle_parts_id = s.id
                            INNER JOIN vehicles t ON v.vehicle_id = t.id
                            WHERE v.vehicle_parts_lifespan IS NOT NULL
                            ORDER BY v.added_date ASC
                        ");
                        $stmt->execute();
                        $count = 0;
                        $hasData = false;

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $count++;

                            // Calculate expiration date
                            $expiry_date = date("Y-m-d", strtotime("+{$row['vehicle_parts_lifespan']} months", strtotime($row['added_date'])));
                            $remaining_days = floor((strtotime($expiry_date) - time()) / (60 * 60 * 24));

                            // Show only parts expiring within 60 days
                            if ($remaining_days <= 60) {
                                $hasData = true;
                        ?>
                                <tr>
                                    <!-- <td><?php echo $count; ?></td> -->
                                    <td><?php echo $row['model']; ?></td>
                                    <td><?php echo $row['license_plate']; ?></td>
                                    <td><?php echo $row['vehicle_parts_name']; ?></td>
                                    <td><?php echo $row['vehicle_parts_lifespan'] . " Months"; ?></td>
                                    <td><?php echo date("F d, Y", strtotime($row['added_date'])); ?></td>
                                    <td><?php echo ($remaining_days > 0) ? $remaining_days . " Days Left" : "<span class='text-danger'>Expired</span>"; ?></td>
                                </tr>
                        <?php
                            }
                        }

                        // If no data found, show message
                        if (!$hasData) {
                            echo "<tr><td colspan='5' class='text-center text-muted'>No vehicle parts nearing expiration</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
