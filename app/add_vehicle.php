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
                    <h1>Add Vehicle</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Add Vehicle</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <?php
// Display success message
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . $_SESSION['success'] . '
          </div>';
    unset($_SESSION['success']); // Clear message after displaying
}

// Display error message
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            ' . $_SESSION['error'] . '
          </div>';
    unset($_SESSION['error']); // Clear message after displaying
}
?>
        <!-- Default box -->
        <div class="card">
            <div class="card-body">
            <form action="add_vehicle_act.php" method="POST" enctype="multipart/form-data">
    <h4>Basic Vehicle Information</h4>
    <div class="form-group">
        <label>Make: (Manufacturer)</label>
        <select name="make" class="form-control" required>
            <option value="">Select Manufacturer</option>
            <?php
                $query = "SELECT id, manufacturer_name FROM tbl_setup_vehicle_manufacturers WHERE isDeleted = 0";
                $result = $conn->query($query);
                foreach ($result as $row) {
                    echo "<option value='{$row['id']}'>{$row['manufacturer_name']}</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label>Model:</label>
        <input type="text" name="model" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Year:</label>
        <input type="number" name="year" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Vehicle Type:</label>
        <select name="vehicle_type_id" class="form-control" required>
            <option value="">Select Vehicle Type</option>
            <?php
                $query = "SELECT id, vehicle_type_name FROM tbl_setup_vehicle_types WHERE isDeleted = 0";
                $result = $conn->query($query);
                foreach ($result as $row) {
                    echo "<option value='{$row['id']}'>{$row['vehicle_type_name']}</option>";
                }
            ?>
        </select>
    </div>

    <h4>Technical Specifications</h4>
    <div class="form-group">
        <label>Fuel Type:</label>
        <select name="fuel_type" class="form-control">
            <option value="Gasoline">Gasoline</option>
            <option value="Diesel">Diesel</option>
            <option value="Electric">Electric</option>
            <option value="Hybrid">Hybrid</option>
        </select>
    </div>
    <div class="form-group">
        <label>Transmission:</label>
        <select name="transmission" class="form-control">
            <option value="Manual">Manual</option>
            <option value="Automatic">Automatic</option>
            <option value="CVT">CVT</option>
        </select>
    </div>
    <div class="form-group">
        <label>Engine Capacity:</label>
        <input type="text" name="engine_capacity" class="form-control">
    </div>

    <h4>Features</h4>
    <div class="form-group">
        <label>Body Type:</label>
        <input type="text" name="body_type" class="form-control">
    </div>
    <div class="form-group">
        <label>Number of Doors:</label>
        <input type="number" name="number_of_doors" class="form-control">
    </div>
    <div class="form-group">
        <label>Number of Seats:</label>
        <input type="number" name="number_of_seats" class="form-control">
    </div>

    <h4>Safety Features</h4>
    <div class="form-check">
        <input type="checkbox" name="abs" class="form-check-input">
        <label>ABS</label>
    </div>
    <div class="form-check">
        <input type="checkbox" name="traction_control" class="form-check-input">
        <label>Traction Control</label>
    </div>

    <h4>Ownership & Registration</h4>
    <div class="form-group">
        <label>License Plate:</label>
        <input type="text" name="license_plate" class="form-control">
    </div>
    <div class="form-group">
        <label>Insurance Provider:</label>
        <input type="text" name="insurance_provider" class="form-control">
    </div>

    <h4>Vehicle Photos</h4>
    <div class="form-group">
        <input type="file" name="photos[]" class="form-control" multiple>
    </div>

    <button type="submit" class="btn btn-primary">Save Vehicle</button>
</form>


            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php include 'includes/footer.php'; ?>
</body>
</html>