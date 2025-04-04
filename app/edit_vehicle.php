<?php 
    include "includes/session.php";
    include "includes/header.php";
    
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        $_SESSION['error'] = "Invalid vehicle ID.";
        header("Location: vehicles.php");
        exit();
    }
    
    $vehicle_id = $_GET['id'];
    
    // Fetch existing vehicle details
    $query = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
    $query->execute([$vehicle_id]);
    $vehicle = $query->fetch(PDO::FETCH_ASSOC);
    
    if (!$vehicle) {
        $_SESSION['error'] = "Vehicle not found.";
        header("Location: vehicles.php");
        exit();
    }
?>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Vehicle</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Edit Vehicle</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
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
    <section class="content">
        <div class="card">
            <div class="card-body">
                <form action="edit_vehicle_act.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
                    <h4>Basic Vehicle Information</h4>
                    <div class="form-group">
                        <label>Make: (Manufacturer)</label>
                        <select name="make" class="form-control" required>
                            <option value="">Select Manufacturer</option>
                            <?php
                                $query = "SELECT id, manufacturer_name FROM tbl_setup_vehicle_manufacturers WHERE isDeleted = 0";
                                $result = $conn->query($query);
                                foreach ($result as $row) {
                                    $selected = ($row['id'] == $vehicle['make']) ? 'selected' : '';
                                    echo "<option value='{$row['id']}' $selected>{$row['manufacturer_name']}</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Model:</label>
                        <input type="text" name="model" class="form-control" value="<?php echo $vehicle['model']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Year:</label>
                        <input type="number" name="year" class="form-control" value="<?php echo $vehicle['year']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Vehicle Type:</label>
                        <select name="vehicle_type_id" class="form-control" required>
                            <option value="">Select Vehicle Type</option>
                            <?php
                                $query = "SELECT id, vehicle_type_name FROM tbl_setup_vehicle_types WHERE isDeleted = 0";
                                $result = $conn->query($query);
                                foreach ($result as $row) {
                                    $selected = ($row['id'] == $vehicle['vehicle_type']) ? 'selected' : '';
                                    echo "<option value='{$row['id']}' $selected>{$row['vehicle_type_name']}</option>";
                                }
                            ?>
                        </select>
                    </div>
                    
                    <h4>Technical Specifications</h4>
                    <div class="form-group">
                        <label>Fuel Type:</label>
                        <select name="fuel_type" class="form-control">
                            <option value="Gasoline" <?php echo ($vehicle['fuel_type'] == 'Gasoline') ? 'selected' : ''; ?>>Gasoline</option>
                            <option value="Diesel" <?php echo ($vehicle['fuel_type'] == 'Diesel') ? 'selected' : ''; ?>>Diesel</option>
                            <option value="Electric" <?php echo ($vehicle['fuel_type'] == 'Electric') ? 'selected' : ''; ?>>Electric</option>
                            <option value="Hybrid" <?php echo ($vehicle['fuel_type'] == 'Hybrid') ? 'selected' : ''; ?>>Hybrid</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Transmission:</label>
                        <select name="transmission" class="form-control">
                            <option value="Manual" <?php echo ($vehicle['transmission'] == 'Manual') ? 'selected' : ''; ?>>Manual</option>
                            <option value="Automatic" <?php echo ($vehicle['transmission'] == 'Automatic') ? 'selected' : ''; ?>>Automatic</option>
                            <option value="CVT" <?php echo ($vehicle['transmission'] == 'CVT') ? 'selected' : ''; ?>>CVT</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Engine Capacity:</label>
                        <input type="text" name="engine_capacity" class="form-control" value="<?php echo $vehicle['engine_capacity']; ?>">
                    </div>
                    
                    <h4>Ownership & Registration</h4>
                    <div class="form-group">
                        <label>License Plate:</label>
                        <input type="text" name="license_plate" class="form-control" value="<?php echo $vehicle['license_plate']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Insurance Provider:</label>
                        <input type="text" name="insurance_provider" class="form-control" value="<?php echo $vehicle['insurance_provider']; ?>">
                    </div>
                    
                    <h4>Vehicle Photos</h4>
                    <div class="form-group">
                        <label>Existing Photos:</label>
                        <div>
                            <?php
                           $photoQuery = $conn->prepare("SELECT photos FROM vehicles WHERE id = ?");
                           $photoQuery->execute([$vehicle_id]);
                           $photoResult = $photoQuery->fetch(PDO::FETCH_ASSOC);
                           
                           if ($photoResult && !empty($photoResult['photos'])) {
                               $photos = json_decode($photoResult['photos'], true); // Decode JSON data
                           
                               if (json_last_error() !== JSON_ERROR_NONE) {
                                   echo "<p>Error decoding JSON: " . json_last_error_msg() . "</p>";
                               } elseif (is_array($photos) && count($photos) > 0) {
                                   foreach ($photos as $photo) {
                                       echo "<div style='display: inline-block; margin: 5px;'>
                                               <img src='../images/vehicles/{$photo}' width='100' height='100' style='border:1px solid #ccc;'>
                                               <input type='checkbox' name='delete_photos[]' value='{$photo}'> Delete
                                             </div>";
                                   }
                               } else {
                                   echo "<p>No photos uploaded.</p>";
                               }
                           } else {
                               echo "<p>No photos uploaded.</p>";
                           }
                           ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Upload New Photos:</label>
                        <input type="file" name="new_photos[]" class="form-control" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Vehicle</button>
                </form>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>