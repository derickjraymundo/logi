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
                    <div class="row">
                        <div class="col-md-4 d-none">
                            <label>Vehicle ID</label>
                            <input type="text" name="vehicle_id" class="form-control" value="<?php echo $vehicleid ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label>Vehicle Part</label>
                            <select name="vehicle_parts_id" class="form-control" required>
                                <option value="">Select Part</option>
                                <?php foreach ($partsOptions as $part) { ?>
                                    <option value="<?php echo $part['id']; ?>"><?php echo $part['vehicle_parts_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Lifespan (Months)</label>
                            <input type="number" name="vehicle_parts_lifespan" class="form-control">
                        </div>
                        <input type="hidden" name="added_by" value="<?php echo $_SESSION['SESS_USER_ID']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Save</button>
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
                                        <button class="btn btn-danger btn-sm deletePart" data-id="<?php echo $part['id']; ?>">Delete</button>
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
    // Handle Form Submission
    $("#vehiclePartsForm").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: "vehicle_parts_action.php?action=save",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                location.reload();
                // location.href="vehicle_parts.php?id="+response[2];
            }
        });
    });

    // Handle Delete
    $(".deletePart").click(function () {
        if (confirm("Are you sure you want to delete this part?")) {
            let id = $(this).data("id");
            $.ajax({
                url: "vehicle_parts_action.php?action=delete&id=" + id,
                type: "GET",
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
});
</script>

</body>
</html>
