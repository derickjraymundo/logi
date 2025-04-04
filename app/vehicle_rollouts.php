<?php 
    include "includes/session.php";
    include "includes/header.php";
?>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Vehicle Rollouts</h1>
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
            <div class="card-body">
            <div class="row mb-3">
    <div class="col-md-3">
        <label>Start Date</label>
        <input type="date" class="form-control" id="startDate">
    </div>
    <div class="col-md-3">
        <label>End Date</label>
        <input type="date" class="form-control" id="endDate">
    </div>
    <div class="col-md-3">
        <label>Vehicle Type</label>
        <select class="form-control" id="vehicleType">
            <option value="">All</option>
            <?php
            $sql = "SELECT id, vehicle_type_name FROM tbl_setup_vehicle_types WHERE isDeleted = 0";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['vehicle_type_name']}'>{$row['vehicle_type_name']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-3">
        <label>Search</label>
        <input type="text" class="form-control" id="searchInput" placeholder="Search...">
    </div>
</div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="vehicleRolloutTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Requested By</th>
                                <th>Purpose</th>
                                <th>Date & Time Vehicle Needed</th>
                                <th>Requested Date</th>
                                <th>Vehicle Type</th>
                                <th>Vehicle ID</th>
                                <th>Remarks</th>
                                <th>Replied Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                try {
                                    $sql = "SELECT s.*, 
                                    (SELECT r.vehicle_type_name FROM tbl_setup_vehicle_types r WHERE r.id = s.admin_vehicle_type ) as vehicle_type_name,
                                    (SELECT  CONCAT_WS(' ', (SELECT u.manufacturer_name FROM tbl_setup_vehicle_manufacturers u WHERE u.id = t.make), '- (', t.model, ')' )   FROM `vehicles` t WHERE t.id = s.admin_vehicle_id ) AS vehicle
                                    
                                    FROM tbl_vehicle_rollouts s ORDER BY s.requested_date DESC";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $rollouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    if ($stmt->rowCount() > 0) {
                                        foreach ($rollouts as $row) {
                                            echo "<tr>
                                                    <td>{$row['id']}</td>
                                                    <td>{$row['requested_by']}</td>
                                                    <td>{$row['purpose']}</td>
                                                    <td>{$row['date_vehicle_needed']} {$row['time_vehicle_needed']}</td>
                                                    <td>{$row['requested_date']}</td>
                                                    <td>{$row['vehicle_type_name']}</td>
                                                    <td>{$row['vehicle']}</td>
                                                    <td>{$row['admin_remarks']}</td>
                                                    <td>{$row['admin_replied_date']}</td>
                                                    <td>";
                                            
                                            if (empty($row['admin_vehicle_id'])) {
                                                echo "<button class='btn btn-primary btn-sm assign-vehicle' data-id='{$row['id']}'>Assign Vehicle</button>";
                                            } else {
                                                echo "<span class='text-success'>Assigned</span>";
                                            }

                                            echo "</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
                                    }
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='9' class='text-center text-danger'>Error: " . $e->getMessage() . "</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal for Assigning Vehicle -->
<div class="modal fade" id="assignVehicleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Vehicle</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="assignVehicleForm">
                    <input type="hidden" id="rollout_id">
                    
                    <!-- Vehicle Type Dropdown -->
                    <div class="form-group">
                        <label>Vehicle Type</label>
                        <select class="form-control" id="vehicle_type">
                            <option value="">Select Vehicle Type</option>
                        </select>
                    </div>

                    <!-- Vehicle Dropdown -->
                    <div class="form-group">
                        <label>Vehicle</label>
                        <select class="form-control" id="vehicle">
                            <option value="">Select Vehicle</option>
                        </select>
                    </div>

                    <!-- Remarks Input -->
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" id="remarks" rows="3"></textarea>
                    </div>

                    <button type="button" class="btn btn-success" id="saveAssignment">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>


<script>
$(document).ready(function() {
    $(".assign-vehicle").click(function() {
        var rollout_id = $(this).data("id");
        $("#rollout_id").val(rollout_id);

        // Load Vehicle Types
        $.ajax({
            url: "fetch_vehicle_types.php",
            method: "GET",
            success: function(data) {
                $("#vehicle_type").html(data);
            }
        });

        $("#assignVehicleModal").modal("show");
    });

    // Load Vehicles when Vehicle Type is Selected
    $("#vehicle_type").change(function() {
        var vehicle_type_id = $(this).val();

        $.ajax({
            url: "fetch_vehicles.php",
            method: "POST",
            data: { vehicle_type: vehicle_type_id },
            success: function(data) {
                $("#vehicle").html(data);
            }
        });
    });

    // Save Vehicle Assignment
    $("#saveAssignment").click(function() {
        var rollout_id = $("#rollout_id").val();
        var vehicle_type = $("#vehicle").val();
        var vehicle_id = $("#vehicle").val();
        var remarks = $("#remarks").val();

        if (vehicle_id === "" || remarks === "") {
            alert("Please select a vehicle and enter remarks.");
            return;
        }

        $.ajax({
            url: "update_vehicle_rollout.php",
            method: "POST",
            data: {
                rollout_id: rollout_id,
                vehicle_type : vehicle_type,
                vehicle_id: vehicle_id,
                remarks: remarks
            },
            success: function(response) {
                alert(response);
                location.reload();
            }
        });
    });

    $("#startDate, #endDate").on("change", function () {
        filterTable();
    });

    $("#vehicleType").on("change", function () {
        filterTable();
    });

    $("#searchInput").on("keyup", function () {
        filterTable();
    });

    function filterTable() {
        var startDate = new Date($("#startDate").val());
        var endDate = new Date($("#endDate").val());
        var vehicleType = $("#vehicleType").val().toLowerCase().trim();
        var searchQuery = $("#searchInput").val().toLowerCase().trim();

        $("#vehicleRolloutTable tbody tr").each(function () {
            var requestedDateText = $(this).find("td:eq(3)").text().trim(); // Requested Date Column
            var requestedDate = new Date(requestedDateText); // Convert to Date
            var vehicleTypeText = $(this).find("td:eq(5)").text().toLowerCase().trim(); // Vehicle Type Column
            var rowText = $(this).text().toLowerCase().trim(); // Full Row Text

            var dateMatch = true;
            var typeMatch = true;
            var searchMatch = true;

            // Date Range Filtering
            if (!isNaN(startDate) && !isNaN(endDate)) {
                dateMatch = (requestedDate >= startDate && requestedDate <= endDate);
            }

            // Vehicle Type Filtering
            if (vehicleType) {
                typeMatch = vehicleTypeText.includes(vehicleType);
            }

            // Search Filtering
            if (searchQuery) {
                searchMatch = rowText.includes(searchQuery);
            }

            // Show/Hide Row Based on Filters
            $(this).toggle(dateMatch && typeMatch && searchMatch);
        });
    }
});
</script>
</body>
</html>
