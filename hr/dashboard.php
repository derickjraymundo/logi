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
                    <h1>Vehicle Rollout Requests</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Vehicle Rollouts</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">New Vehicle Request</h3>
            </div>
            <div class="card-body">
                <form id="requestForm">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Requested By</label>
                            <input type="text" class="form-control" id="requested_by" required>
                        </div>
                        <div class="col-md-6">
                            <label>Purpose</label>
                            <textarea class="form-control" id="purpose" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Date Vehicle Needed</label>
                            <input type="date" class="form-control" id="date_needed" required>
                        </div>
                        <div class="col-md-6">
                            <label>Time Vehicle Needed</label>
                            <input type="time" class="form-control" id="time_needed" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Submit Request</button>
                </form>
            </div>

        </div>

        <!-- Pending Requests Table -->
        <div class="card">
    <div class="card-header">
        <h3 class="card-title">Vehicle Rollouts</h3>
    </div>
    <div class="card-body">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="rolloutTabs">
            <li class="nav-item">
                <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="cancelled-tab" data-toggle="tab" href="#cancelled">Cancelled</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="assigned-tab" data-toggle="tab" href="#assigned">Assigned</a>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content mt-3">
            <!-- Pending Requests -->
            <div class="tab-pane fade show active" id="pending">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Requested By</th>
                                <th>Purpose</th>
                                <th>Date Needed</th>
                                <th>Time Needed</th>
                                <th>Requested Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $sql = "SELECT id, requested_by, purpose, date_vehicle_needed, time_vehicle_needed, requested_date, status FROM tbl_vehicle_rollouts WHERE admin_vehicle_id IS NULL AND status = 'Pending' ORDER BY requested_date DESC";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $count = 1;

                                if ($stmt->rowCount() > 0) {
                                    foreach ($requests as $row) {
                                        echo "<tr>
                                                <td>{$count}</td>
                                                <td>{$row['requested_by']}</td>
                                                <td>{$row['purpose']}</td>
                                                <td>{$row['date_vehicle_needed']}</td>
                                                <td>{$row['time_vehicle_needed']}</td>
                                                <td>{$row['requested_date']}</td>
                                                <td><span class='text-warning'>{$row['status']}</span></td>
                                                <td>
                                                    <button class='btn btn-success btn-sm edit-btn'>Edit</button>
                                                    <button class='btn btn-danger btn-sm cancel-btn' data-id='{$row['id']}'>Cancel</button>
                                                </td>
                                            </tr>";
                                        $count++;
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No pending requests</td></tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='6' class='text-danger'>Error: " . $e->getMessage() . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Cancelled Requests -->
            <div class="tab-pane fade" id="cancelled">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Requested By</th>
                                <th>Purpose</th>
                                <th>Date Needed</th>
                                <th>Time Needed</th>
                                <th>Requested Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $sql = "SELECT id, requested_by, purpose, requested_date, date_vehicle_needed, time_vehicle_needed, status FROM tbl_vehicle_rollouts WHERE status = 'Cancelled' ORDER BY requested_date DESC";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $count = 1;

                                if ($stmt->rowCount() > 0) {
                                    foreach ($requests as $row) {
                                        echo "<tr>
                                                <td>{$count}</td>
                                                <td>{$row['requested_by']}</td>
                                                <td>{$row['purpose']}</td>
                                                <td>{$row['date_vehicle_needed']}</td>
                                                <td>{$row['time_vehicle_needed']}</td>
                                                <td>{$row['requested_date']}</td>
                                                <td><span class='text-danger'>{$row['status']}</span></td>
                                            </tr>";
                                        $count++;
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No cancelled requests</td></tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='5' class='text-danger'>Error: " . $e->getMessage() . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Assigned Requests -->
            <div class="tab-pane fade" id="assigned">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Requested By</th>
                                <th>Purpose</th>
                                <th>Date Needed</th>
                                <th>Time Needed</th>
                                <th>Requested Date</th>
                                <th>Vehicle Assigned</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $sql = "SELECT s.id, s.requested_by, s.purpose, s.requested_date, s.date_vehicle_needed, s.time_vehicle_needed,
                                            (SELECT CONCAT_WS(' ', u.manufacturer_name, t.model) 
                                             FROM vehicles t 
                                             JOIN tbl_setup_vehicle_manufacturers u ON u.id = t.make 
                                             WHERE t.id = s.admin_vehicle_id) AS vehicle,
                                            s.status 
                                        FROM tbl_vehicle_rollouts s 
                                        WHERE s.admin_vehicle_id IS NOT NULL AND s.status = 'Assigned' 
                                        ORDER BY s.requested_date DESC";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $count = 1;

                                if ($stmt->rowCount() > 0) {
                                    foreach ($requests as $row) {
                                        echo "<tr>
                                                <td>{$count}</td>
                                                <td>{$row['requested_by']}</td>
                                                <td>{$row['purpose']}</td>
                                                <td>{$row['date_vehicle_needed']}</td>
                                                <td>{$row['time_vehicle_needed']}</td>
                                                <td>{$row['requested_date']}</td>
                                                <td>{$row['vehicle']}</td>
                                                <td><span class='text-success'>{$row['status']}</span></td>
                                            </tr>";
                                        $count++;
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No assigned requests</td></tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='6' class='text-danger'>Error: " . $e->getMessage() . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- End Tabs Content -->
    </div> <!-- End Card Body -->
</div> <!-- End Card -->
    </section>
</div>

<?php include 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    $("#requestForm").submit(function(e) {
        e.preventDefault();
        var requestedBy = $("#requested_by").val();
        var purpose = $("#purpose").val();
        var date_needed = $("#date_needed").val();
        var time_needed = $("#time_needed").val();

        if (requestedBy === "" || purpose === "") {
            alert("Please fill in all fields.");
            return;
        }

        $.ajax({
            url: "save_vehicle_request.php",
            method: "POST",
            data: {
                requested_by: requestedBy,
                purpose: purpose,
                date_vehicle_needed: date_needed,
                time_vehicle_needed: time_needed
            },
            success: function(response) {
                alert(response);
                location.reload();
            }
        });
    });

    // Handle Edit Request
    $(".edit-btn").click(function() {
        var row = $(this).closest("tr");
        var id = row.data("id");
        var requestedBy = row.find(".edit-requested_by").val();
        var purpose = row.find(".edit-purpose").val();

        $.ajax({
            url: "update_vehicle_request.php",
            method: "POST",
            data: {
                id: id,
                requested_by: requestedBy,
                purpose: purpose
            },
            success: function(response) {
                alert(response);
                location.reload();
            }
        });
    });

    // Handle Cancel Request
    $(".cancel-btn").on("click", function () {
        let requestId = $(this).data("id");

        if (!requestId) {
            alert("Error: Request ID not found.");
            return;
        }

        if (confirm("Are you sure you want to cancel this request?")) {
            $.ajax({
                url: "cancel_vehicle_request.php", // Create this PHP file to handle cancellation
                type: "POST",
                data: { id: requestId },
                success: function (response) {
                    if (response.trim() === "success") {
                        alert("Request cancelled successfully.");
                        location.reload(); // Refresh the page
                    } else {
                        alert("Error cancelling request: " + response);
                    }
                },
                error: function () {
                    alert("AJAX request failed.");
                }
            });
        }
    });
});
</script>

</body>
</html>
