<?php 
    include "includes/session.php";
    include "includes/header.php";
?>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Vehicle Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Vehicle Request</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-body">
            <!-- Tabs for Pending, Approved, Cancelled -->
            <ul class="nav nav-tabs" id="requestTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#pendingTab">Pending Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#approvedTab">Approved Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#cancelledTab">Cancelled Requests</a>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <!-- Pending Requests Table -->
                <div class="tab-pane fade show active" id="pendingTab">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Request ID</th>
                                <th>Vehicle</th>
                                <th>Date Requested</th>
                                <th>Time Requested</th>
                                <th>Remarks</th>
                                <th>Requested By</th>
                                <th>Requested Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = $conn->query("SELECT * ,(select model from vehicles where id = tbl_vehicle_requests.vehicle_id) as requestedvehicle FROM tbl_vehicle_requests WHERE request_status = 'Pending'");
                            if ($query->rowCount() > 0) {
                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['request_id']}</td>
                                        <td>{$row['requestedvehicle']}</td>
                                        <td>{$row['date_requested']}</td>
                                        <td>{$row['time_requested']}</td>
                                        <td>{$row['remarks']}</td>
                                        <td>{$row['requested_by']}</td>
                                        <td>{$row['requested_date']}</td>
                                        <td>
                                            <button class='btn btn-success btn-sm approve-btn' data-id='{$row['id']}'>Approve</button>
                                            <button class='btn btn-danger btn-sm cancel-btn' data-id='{$row['id']}'>Cancel</button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                // If no pending requests, display a single row with a message
                                echo "<tr>
                                    <td colspan='9' class='text-center text-muted'>No pending request yet</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


                <!-- Approved Requests Table -->
                <div class="tab-pane fade" id="approvedTab">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Request ID</th>
                                <th>Vehicle</th>
                                <th>Date Requested</th>
                                <th>Time Requested</th>
                                <th>Remarks</th>
                                <th>Requested By</th>
                                <th>Requested Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = $conn->query("SELECT *,(select model from vehicles where id = tbl_vehicle_requests.vehicle_id) as requestedvehicle FROM tbl_vehicle_requests WHERE request_status = 'Approved'");
                            if ($query->rowCount() > 0) {
                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['request_id']}</td>
                                        <td>{$row['requestedvehicle']}</td>
                                        <td>{$row['date_requested']}</td>
                                        <td>{$row['time_requested']}</td>
                                        <td>{$row['remarks']}</td>
                                        <td>{$row['requested_by']}</td>
                                        <td>{$row['requested_date']}</td>
                                    </tr>";
                                }
                            } else {
                                // If no approved requests, display a single row with a message
                                echo "<tr>
                                    <td colspan='8' class='text-center text-muted'>No approved request yet</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Cancelled Requests Table -->
                <div class="tab-pane fade" id="cancelledTab">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Request ID</th>
                                <th>Vehicle</th>
                                <th>Date Requested</th>
                                <th>Time Requested</th>
                                <th>Remarks</th>
                                <th>Requested By</th>
                                <th>Requested Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = $conn->query("SELECT *,(select model from vehicles where id = tbl_vehicle_requests.vehicle_id) as requestedvehicle FROM tbl_vehicle_requests WHERE request_status = 'Cancelled'");
                            if ($query->rowCount() > 0) {
                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['request_id']}</td>
                                        <td>{$row['requestedvehicle']}</td>
                                        <td>{$row['date_requested']}</td>
                                        <td>{$row['time_requested']}</td>
                                        <td>{$row['remarks']}</td>
                                        <td>{$row['requested_by']}</td>
                                        <td>{$row['requested_date']}</td>
                                    </tr>";
                                }
                            } else {
                                // If no data, display a single row with a message
                                echo "<tr>
                                    <td colspan='8' class='text-center text-muted'>No cancelled request yet</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include 'includes/footer.php'; ?>
</body>
</html>

<script>
$(document).ready(function () {
    // Approve Request
    $(".approve-btn").click(function () {
        let requestId = $(this).data("id");
        if (confirm("Are you sure you want to approve this request?")) {
            $.post("process_request.php", { action: "approve", id: requestId }, function (response) {
                location.reload();
            });
        }
    });

    // Cancel Request
    $(".cancel-btn").click(function () {
        let requestId = $(this).data("id");
        if (confirm("Are you sure you want to cancel this request?")) {
            $.post("process_request.php", { action: "cancel", id: requestId }, function (response) {
                location.reload();
            });
        }
    });
});
</script>
