<?php
include "includes/session.php";
include "includes/header.php";
?>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<!-- Fetch Vehicle Parts for Dropdown -->
<?php
$vehicleid = $_GET['id'];

$stmt = $conn->prepare("SELECT id, vehicle_parts_name FROM tbl_setup_vehicle_parts WHERE isDeleted = 0");
$stmt->execute();
$partsOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Existing Vehicle Parts Data
$vehiclePartsQuery = $conn->prepare("
    SELECT v.id, v.vehicle_id, v.vehicle_parts_id, v.vehicle_parts_lifespan, v.added_date, 
           p.vehicle_parts_name, t.model
    FROM tbl_v_vehicles_parts v
    JOIN tbl_setup_vehicle_parts p ON v.vehicle_parts_id = p.id
    JOIN vehicles t on v.vehicle_id = t.id
    WHERE v.vehicle_id = '$vehicleid'
");
$vehiclePartsQuery->execute();
$vehicleParts = $vehiclePartsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php if (!empty($vehicleParts)) {
    echo "<h1>" . htmlspecialchars($vehicleParts[0]['model']) . "</h1>";
} else {
    echo "<h1>No Parts Found</h1>";
}?></h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-body">
                <!-- Add Form -->
                <form id="vehiclePartsForm">
                    <input type="hidden" name="id" id="vehiclePartId">
                    <div class="row">
                        <div class="col-md-4 d-none">
                            <label>Vehicle ID</label>
                            <input type="text" name="vehicle_id" class="form-control" value="<?php echo $vehicleid ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label>Vehicle Part</label>
                            <select name="vehicle_parts_id" id="vehicle_parts_id" class="form-control" required>
                                <option value="">Select Part</option>
                                <?php foreach ($partsOptions as $part) { ?>
                                    <option value="<?php echo $part['id']; ?>"><?php echo $part['vehicle_parts_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Lifespan (Months)</label>
                            <input type="number" name="vehicle_parts_lifespan" id="vehicle_parts_lifespan" class="form-control">
                        </div>
                        <input type="hidden" name="added_by" value="<?php echo $_SESSION['SESS_USER_ID']; ?>">
                    </div>
                    <button type="submit" id="saveBtn" class="btn btn-primary mt-3">Save</button>
                    <button type="button" id="updateBtn" class="btn btn-success mt-3 d-none">Update</button>
                </form>

                <!-- Vehicle Parts Table -->
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Part Name</th>
                            <th>Lifespan (Months)</th>
                            <th>Remaining Days</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="partsTableBody">
                        <?php if (empty($vehicleParts)) { ?>
                            <tr>
                                <td colspan="5" class="text-center">No Vehicle Parts Yet</td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($vehicleParts as $index => $part) { 
                                $addedDate = new DateTime($part['added_date']);
                                $lifespanMonths = $part['vehicle_parts_lifespan'];
                                $expiryDate = clone $addedDate;
                                $expiryDate->modify("+$lifespanMonths months");
                                $remainingDays = (new DateTime())->diff($expiryDate)->days;
                                $remainingText = ($remainingDays > 0) ? "$remainingDays days left" : "Expired";
                            ?>
                                <tr id="row_<?php echo $part['id']; ?>">
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo $part['vehicle_parts_name']; ?></td>
                                    <td><?php echo $part['vehicle_parts_lifespan'] ?? 'N/A'; ?></td>
                                    <td><?php echo $remainingText; ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm updatePart" data-id="<?php echo $part['id']; ?>">Edit Lifespan</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
<script>
$(document).ready(function () {
    $(".updatePart").click(function () {
        let partId = $(this).data("id"); 

        $.ajax({
            url: "fetch_vehicle_part.php", // Create this PHP file to fetch single part data
            type: "POST",
            data: { id: partId },
            dataType: "json",
            success: function (response) {
                // console.log(response);
                if (response.status === "success") {
                    $("#vehiclePartId").val(response.data.id);
                    $("#vehicle_parts_id").val(response.data.vehicle_parts_id);
                    $("#vehicle_parts_lifespan").val(response.data.vehicle_parts_lifespan);

                    // Hide Save button and show Update button
                    $("#saveBtn").addClass("d-none");
                    $("#updateBtn").removeClass("d-none");
                }
            }
        });
    });

    $("#updateBtn").click(function () {
        let formData = $("#vehiclePartsForm").serialize();

        $.ajax({
            url: "update_vehicle_part.php", // Create this PHP file to update part data
            type: "POST",
            data: formData,
            success: function (response) {
                if (response === "success") {
                    alert("Vehicle part updated successfully!");
                    location.reload();
                } else {
                    alert("Failed to update vehicle part.");
                }
            }
        });
    });
});

</script>

</body>
</html>
