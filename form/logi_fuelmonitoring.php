<?php
include "includes/session.php";
include "includes/header.php";
include "includes/sidebar.php";
include "includes/topbar.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = $_POST['id'] ?? '';
    $transaction_id = $_POST['transaction_id'];
    $driver_id = $_POST['driver_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $before_arrived = $_POST['before_arrived'];
    $after_arrived = $_POST['after_arrived'];
    $date_arrived = $_POST['date_arrived'];
    $time_arrived = $_POST['time_arrived'];

    if (!empty($id)) {
        // Update
        $query = "UPDATE tbl_fuel_monitoring SET vehicle_id =?, before_arrived=?, after_arrived=?, transaction_date = ?  WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$vehicle_id, $before_arrived, $after_arrived, $date_arrived ." " . $time_arrived, $id]);
    } else {
        // Insert
        $transaction_id = "TXN" . time(); // Example: TXN1711738745

        // Insert new record
        $query = "INSERT INTO tbl_fuel_monitoring (transaction_id, driver_id, vehicle_id, before_arrived, after_arrived, transaction_date) VALUES (?, ?,
             ?,
             ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$transaction_id, $driver_id, $vehicle_id, $before_arrived, $after_arrived, $date_arrived. " ".$time_arrived]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
   
    
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'] ?? '';

    if (!empty($id)) {
        $query = "UPDATE  tbl_fuel_monitoring SET isDeleted = 1 WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
    }
    exit;
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Fuel Monitoring</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="card">
      <div class="card-header">
        <button class="btn btn-primary float-left" data-toggle="modal" data-target="#fuelModal" id="addFuelBtn">Add Fuel Transaction</button>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
        
              <th>Transaction ID</th>
              <th>Vehicle</th>
              <th>Date</th>
              <th>Before Arrived</th>
              <th>After Arrived</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $query = "SELECT a.*, (SELECT CONCAT_WS(' ', b.model, ' - (', (SELECT c.manufacturer_name FROM tbl_setup_vehicle_manufacturers c WHERE c.id = b.make  ),')' )  FROM vehicles b WHERE b.id = a.vehicle_id ) as vehicle_details FROM tbl_fuel_monitoring a WHERE a.driver_id = :driver_id AND a.isDeleted = :isDeleted ORDER BY a.transaction_date DESC";
            $stmt = $conn->prepare($query);
            $stmt->execute(['driver_id'=>$_SESSION['SESS_USER_ID'] , 'isDeleted'=>0]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($rows)) {
              echo "<tr><td colspan='7' class='text-center'>No Fuel Reports Yet</td></tr>";
            } else {
              foreach ($rows as $row) {
                $dateArrived = date("Y-m-d", strtotime($row['transaction_date'])); // Extracts Date (YYYY-MM-DD)
                $timeArrived = date("H:i:s", strtotime($row['transaction_date'])); // Extracts Time (HH:MM:SS)

                
                echo "<tr>
             
                        <td>{$row['transaction_id']}</td>
                        <td>{$row['vehicle_details']}</td>
                        <td>{$row['transaction_date']}</td>
                        <td>{$row['before_arrived']}</td>
                        <td>{$row['after_arrived']}</td>
                        <td>
                          <button class='btn btn-warning btn-sm editBtn' data-id='{$row['id']}' data-transaction='{$row['transaction_id']}'                    
                          data-datearrived='{$dateArrived}' 
                          data-timearrived='{$timeArrived}'  
                          data-before='{$row['before_arrived']}' data-after='{$row['after_arrived']}' data-vehicleid='{$row['vehicle_id']}' >Edit</button>
                          <button class='btn btn-danger btn-sm deleteBtn' data-id='{$row['id']}'>Delete</button>
                        </td>
                      </tr>";
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

<!-- Single Modal for Add/Edit Fuel -->
<div class="modal fade" id="fuelModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="fuelModalTitle">Add Fuel Transaction</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="fuelForm">
          <input type="hidden" name="id" id="fuel_id">
          <input type="hidden" name="driver_id" id="driver_id" value="<?php echo $_SESSION['SESS_USER_ID']; ?>">
          <input type="hidden" name="transaction_id" id="transaction_id">


          <div class="form-group">
            <label>Vehicle</label>
            <select name="vehicle_id" id="vehicle_id" class="form-control" required>
                <option value="">Select Vehicle</option>
                <?php
                // Fetch vehicles for the logged-in driver
                $query = "SELECT a.id, CONCAT_WS(' ', b.manufacturer_name, '(', a.model, ')') AS vehicle
                          FROM vehicles a
                          LEFT JOIN tbl_setup_vehicle_manufacturers b ON a.make = b.id
                          WHERE a.driver_id = :driver_id";
                $stmt = $conn->prepare($query);
                $stmt->execute(['driver_id' => $_SESSION['SESS_USER_ID']]);
                $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($vehicles as $vehicle) {
                    echo "<option value='{$vehicle['id']}'>{$vehicle['vehicle']}</option>";
                }
                ?>
            </select>
        </div>

          <div class="form-group">
            <label>Date</label>
            <input type="date" class="form-control" name="date_arrived" id="date_arrived" required>
          </div>

          <div class="form-group">
            <label>Time</label>
            <input type="time"  class="form-control" name="time_arrived" id="time_arrived" required>
          </div>

          <div class="form-group">
            <label>Before Arrived (L)</label>
            <input type="number" step="0.01" class="form-control" name="before_arrived" id="before_arrived" required>
          </div>
          <div class="form-group">
            <label>After Arrived (L)</label>
            <input type="number" step="0.01" class="form-control" name="after_arrived" id="after_arrived" required>
          </div>
          <button type="submit" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<script>
$(document).ready(function() {
    // Open Add Fuel Transaction Modal
    $("#addFuelBtn").click(function() {
        $("#fuelForm")[0].reset();
        $("#fuel_id").val('');
        $("#fuelModalTitle").text("Add Fuel Transaction");
        $("#fuelModal").modal("show");
    });

    // Submit Form (Add or Update)
    $("#fuelForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "", // Current page handles both insert and update
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                location.reload(); // Reload to show updated data
            }
        });
    });

    // Open Edit Modal
    $(".editBtn").click(function() {
        let id = $(this).data("id");
        let beforeArrived = $(this).data("before");
        let afterArrived = $(this).data("after");
        let dateArrived = $(this).data("datearrived");
        let timeArrived = $(this).data("timearrived");
        let vehicleid = $(this).data("vehicleid");
        

        $("#fuel_id").val(id);
        $("#before_arrived").val(beforeArrived);
        $("#after_arrived").val(afterArrived);
        $("#date_arrived").val(dateArrived);
        $("#time_arrived").val(timeArrived);
        $("#vehicle_id").val(vehicleid);



        $("#fuelModalTitle").text("Edit Fuel Transaction");
        $("#fuelModal").modal("show");
    });
    // Delete Transaction
    $(".deleteBtn").click(function() {
        if (confirm("Are you sure you want to delete this transaction?")) {
            let id = $(this).data("id");

            $.ajax({
                url: "", // Current page handles delete
                type: "DELETE",
                data: { id: id },
                success: function(response) {
                    location.reload(); // Reload to reflect deletion
                }
            });
        }
    });
});
</script>
