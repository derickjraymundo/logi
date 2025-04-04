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
                    <h1>View Vehicles</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">View Vehicles</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content --><!-- Lifespan Modal -->
<div class="modal fade" id="lifespanModal" tabindex="-1" aria-labelledby="lifespanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lifespanModalLabel">Update Lifespan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="vehicle_id">
                <label for="lifespanMonths">Enter Lifespan (Months):</label>
                <input type="number" id="lifespanMonths" class="form-control" min="1" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="saveLifespan">Save Changes</button>
            </div>
        </div>
    </div>
</div>

    <section class="content">
        <div class="card">
            <div class="card-body">

                <!-- Search and Filter -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search vehicle...">
                    </div>
                    <div class="col-md-4">
                        <select id="yearFilter" class="form-control">
                            <option value="">All Years</option>
                            <?php 
                            $years = $conn->query("SELECT DISTINCT year FROM vehicles ORDER BY year DESC");
                            while ($year = $years->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='".$year['year']."'>".$year['year']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button id="resetFilter" class="btn btn-secondary">Reset</button>
                    </div>
                </div>

                <?php 
                $query = $conn->query("SELECT a.id, a.model, a.make, b.manufacturer_name, a.year, a.vehicle_type, c.vehicle_type_name, a.license_plate,
                               a.vehicle_lifespan,  a.created_at
                             FROM vehicles a
                            LEFT JOIN tbl_setup_vehicle_manufacturers b ON b.id = a.make
                            LEFT JOIN tbl_setup_vehicle_types c ON c.id = a.vehicle_type
                 ORDER BY a.created_at DESC");

                if ($query->rowCount() > 0) {  
                ?>
                <table class="table table-bordered" id="vehicleTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Model</th>
                            <th>Make</th>
                            <th>Year</th>
                            <th>Lifespan</th>
                            <th>Vehicle Type</th>
                            <th>License Plate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 1;
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {  
                            $addedDate = new DateTime($row['created_at']);
                            $lifespanMonths = (int) $row['vehicle_lifespan']; // Ensure it's an integer
                            
                            if ($lifespanMonths > 0) {
                                $expiryDate = clone $addedDate;
                                $expiryDate->modify("+$lifespanMonths months");
                                $remainingDays = (new DateTime())->diff($expiryDate)->days;
                                $remainingText = ($remainingDays > 0) ? "$remainingDays days left" : "Expired";
                            } else {
                                $remainingText = "N/A"; // If lifespan is missing or invalid
                            }
                        ?>
                        <tr class="vehicle-row">
                            <td><?php echo $count++; ?></td>
                            <td><?php echo $row['model']; ?></td>
                            <td><?php echo $row['manufacturer_name']; ?></td>
                            <td class="vehicle-year"><?php echo $row['year']; ?></td>
                            <td><?php echo $remainingText; ?></td>
                            <td><?php echo $row['vehicle_type_name']; ?></td>
                            <td><?php echo $row['license_plate']; ?></td>
                            <td>
                            
                                <a href="vehicle_parts.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Vehicle Parts</a>
                                <button class="btn btn-warning btn-sm update-lifespan" data-id="<?php echo $row['id']; ?>">Lifespan</button>

                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                    <div class="alert alert-info text-center">No vehicles added yet.</div>
                <?php } ?>

            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>

<!-- JavaScript for Search and Filter -->
<script>
      $(".update-lifespan").click(function () {
        let vehicleId = $(this).data("id");
        $("#vehicle_id").val(vehicleId);
        $("#lifespanModal").modal("show");
    });

    $("#saveLifespan").click(function () {
        let vehicleId = $("#vehicle_id").val();
        let lifespanMonths = $("#lifespanMonths").val();

        if (lifespanMonths < 1) {
            alert("Please enter a valid number of months.");
            return;
        }

        $.ajax({
            url: "update_lifespan.php",
            method: "POST",
            data: { vehicle_id: vehicleId, lifespan: lifespanMonths },
            success: function (response) {
                alert(response);
                $("#lifespanModal").modal("hide");
                location.reload();
            },
            error: function () {
                alert("Failed to update lifespan.");
            }
        });
    });
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const yearFilter = document.getElementById("yearFilter");
    const resetFilter = document.getElementById("resetFilter");
    const tableRows = document.querySelectorAll(".vehicle-row");

    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const selectedYear = yearFilter.value;

        tableRows.forEach(row => {
            const model = row.children[1].textContent.toLowerCase();
            const make = row.children[2].textContent.toLowerCase();
            const year = row.children[3].textContent;
            const license = row.children[5].textContent.toLowerCase();

            const matchesSearch = model.includes(searchText) || make.includes(searchText) || license.includes(searchText);
            const matchesYear = selectedYear === "" || year === selectedYear;

            if (matchesSearch && matchesYear) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    searchInput.addEventListener("input", filterTable);
    yearFilter.addEventListener("change", filterTable);
    resetFilter.addEventListener("click", function() {
        searchInput.value = "";
        yearFilter.value = "";
        filterTable();
    });
});
</script>