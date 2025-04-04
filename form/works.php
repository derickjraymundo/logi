<?php
include "includes/session.php";
include "includes/header.php";
include "includes/sidebar.php";
include "includes/topbar.php";

// Fetch current work assignments
$queryCurrent = "
    SELECT 
        db.id, db.booking_id, db.froms, db.tos, db.booking_date, db.booking_status, db.booking_remarks,
        GROUP_CONCAT(CONCAT(h.lastname, ' ', h.firstname, ' ', COALESCE(h.middlename, '')) SEPARATOR ', ') AS helpers
    FROM tbl_driver_book db
    LEFT JOIN tbl_helpers h ON db.booking_id = h.booking_id
    WHERE db.driver_id = ? AND db.booking_status IN('1','0')
    GROUP BY db.booking_id
";
$stmtCurrent = $conn->prepare($queryCurrent);
$stmtCurrent->execute([$_SESSION['SESS_USER_ID']]);
$currentWork = $stmtCurrent->fetchAll(PDO::FETCH_ASSOC);

// Fetch past work assignments
$queryPast = "
    SELECT 
        db.booking_id, db.froms, db.tos, db.booking_date, db.booking_status, db.booking_remarks,
        GROUP_CONCAT(CONCAT(h.lastname, ' ', h.firstname, ' ', COALESCE(h.middlename, '')) SEPARATOR ', ') AS helpers
    FROM tbl_driver_book db
    LEFT JOIN tbl_helpers h ON db.booking_id = h.booking_id
    WHERE db.driver_id = ? AND db.booking_status NOT IN('1','0')
    GROUP BY db.booking_id
";
$stmtPast = $conn->prepare($queryPast);
$stmtPast->execute([$_SESSION['SESS_USER_ID']]);
$pastWork = $stmtPast->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Driver Work Assignments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Work Assignments</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <!-- Current Work Assignments -->
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Current Work Assignments</h3>
            </div>
            <div class="card-body">
            <table class="table table-bordered">
    <thead>
        <tr>
            <th>Booking ID</th>
            <!-- <th>From</th> -->
            <th>To</th>
            <th>Booking Date</th>
            <th>Remarks</th>
            <th>Helpers</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($currentWork)) : ?>
            <?php foreach ($currentWork as $work) : ?>
                <tr>
                    <td><?= $work['booking_id'] ?></td>
                    <!-- <td><?= $work['froms'] ?></td> -->
                    <td><?= $work['tos'] ?></td>
                    <td><?= $work['booking_date'] ?></td>
                    <td><?= $work['booking_remarks'] ?></td>
                    <td><?= $work['helpers'] ?: 'No helpers assigned' ?></td>
                    <td>
                        <?php if ($work['booking_status'] == 0) : ?>
                            <!-- If status is 0, show Accept & Cancel buttons -->
                            <button class="btn btn-primary btn-sm update-status" data-id="<?= $work['id'] ?>" data-status="1">Accept</button>
                            <button class="btn btn-danger btn-sm update-status" data-id="<?= $work['id'] ?>" data-status="2">Cancel</button>
                        <?php elseif ($work['booking_status'] == 1) : ?>
                            <!-- If status is 1 (Accepted), show Done & Cancel buttons -->
                            <button class="btn btn-success btn-sm update-status" data-id="<?= $work['id'] ?>" data-status="3">Done</button>
                            <button class="btn btn-danger btn-sm update-status" data-id="<?= $work['id'] ?>" data-status="2">Cancel</button>
                        <?php elseif ($work['booking_status'] == 3) : ?>
                            <!-- If status is 3 (Done), disable buttons -->
                            <span class="badge bg-success">Completed</span>
                        <?php elseif ($work['booking_status'] == 2) : ?>
                            <!-- If status is 2 (Canceled), disable buttons -->
                            <span class="badge bg-danger">Canceled</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr><td colspan="7" class="text-center">No active work assignments</td></tr>
        <?php endif; ?>
    </tbody>
</table>

            </div>
        </div>

        <!-- Past Work Assignments -->
        <div class="card">
            <div class="card-header bg-secondary">
                <h3 class="card-title">Past Work Assignments</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <!-- <th>From</th> -->
                            <th>To</th>
                            <th>Booking Date</th>
                            <th>Remarks</th>
                            <th>Helpers</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pastWork)) : ?>
                            <?php foreach ($pastWork as $work) : ?>
                                <tr>
                                    <td><?= $work['booking_id'] ?></td>
                                    <!-- <td><?= $work['froms'] ?></td> -->
                                    <td><?= $work['tos'] ?></td>
                                    <td><?= $work['booking_date'] ?></td>
                                    <td><?= $work['booking_remarks'] ?></td>
                                    <td><?= $work['helpers'] ?: 'No helpers assigned' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="6" class="text-center">No past work assignments</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".update-status").forEach(button => {
            button.addEventListener("click", function() {
                let updateBook = "";
                let bookingId = this.getAttribute("data-id");
                let status = this.getAttribute("data-status");

                if (confirm("Are you sure you want to update this booking?")) {

                    if(status == 1) {

                        if ("geolocation" in navigator) {
    navigator.geolocation.watchPosition(
        (position) => {
            const latitude = position.coords.latitude.toFixed(6); // Limit to 6 decimal places
            const longitude = position.coords.longitude.toFixed(6);
            const accuracy = position.coords.accuracy.toFixed(2); // Get accuracy in meters

            console.log(`Latitude: ${latitude}, Longitude: ${longitude}, Accuracy: ${accuracy}m`);

                                        // Send location update via AJAX
                                        $.ajax({
                                            url: "update_booking_status.php",
                                            method: "POST",
                                            dataType: "json",
                                            data: {
                                                updateBook, 
                                                bookingId, 
                                                status, 
                                                latitude, 
                                                longitude
                                            },
                                            success: function(response) {
                                                alert(response[2]);
                                                if (response[0] === "success") {
                                                    location.reload();
                                                }
                                            },
                                            error: function(xhr, status, error) {
                                                console.error("AJAX Error: ", error);
                                            }
                                        });

                                    },
                                    (error) => {
                                        console.error("Error getting location: ", error.message);
                                    },
                                    {
                                        enableHighAccuracy: true, // Get high accuracy location
                                        timeout: 10000, // 10 seconds timeout
                                        maximumAge: 0 // No cache, always get fresh data
                                    }
                                );
                            } else {
                            console.error("Geolocation is not supported by this browser.");
                        }


                    }else {
                        $.ajax({
                            url:"update_booking_status.php",
                            method : "post",
                            dataType : "json",
                            data : {
                                updateBook, bookingId, status
                            },
                            success : function(response) {
                                alert(response[2]);
                            }
                        });

                    }

             

                    // fetch("update_booking_status.php", {
                    //     method: "POST",
                    //     headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    //     body: `id=${bookingId}&status=${status}`
                    // })
                    // .then(response => response.json())
                    // .then(data => {
                    //     if (data.success) {
                    //         alert("Booking updated successfully!");
                    //         location.reload();
                    //     } else {
                    //         alert("Error: " + data.error);
                    //     }
                    // })
                    // .catch(error => alert("Error updating booking!"));
                }
            });
        });
    });
</script>
