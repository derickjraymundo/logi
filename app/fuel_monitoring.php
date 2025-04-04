<?php
include "includes/session.php";
include "includes/header.php";
include "includes/sidebar.php";
include "includes/topbar.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = $_POST['id'] ?? '';
    $transaction_id = $_POST['transaction_id'];
    $driver_id = $_POST['driver_id'];
    $before_arrived = $_POST['before_arrived'];
    $after_arrived = $_POST['after_arrived'];

    if (!empty($id)) {
        // Update
        $query = "UPDATE tbl_fuel_monitoring SET before_arrived=?, after_arrived=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$before_arrived, $after_arrived, $id]);
    } else {
        // Insert
        $transaction_id = "TXN" . time(); // Example: TXN1711738745

        // Insert new record
        $query = "INSERT INTO tbl_fuel_monitoring (transaction_id, driver_id, vehicle_id, before_arrived, after_arrived, transaction_date) VALUES (?, ?,
            (SELECT id FROM vehicles WHERE driver_id = $driver_id ORDER BY id DESC LIMIT 1),
             ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->execute([$transaction_id, $driver_id, $before_arrived, $after_arrived]);
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
      <div class="card-body">
      <div class="row mb-3">
    <div class="col-md-4">
        <input type="text" id="search_transaction" class="form-control" placeholder="Search by Transaction ID">
    </div>
    <div class="col-md-3">
        <input type="date" id="start_date" class="form-control">
    </div>
    <div class="col-md-3">
        <input type="date" id="end_date" class="form-control">
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" onclick="filterTable()">Search</button>
    </div>
</div>


       
<table class="table table-bordered" id="fuel_table">
    <thead>
        <tr>
            <th>Driver Name</th>
            <th>Transaction ID</th>
            <th>Date</th>
            <th>Before Arrived</th>
            <th>After Arrived</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT *, 
                 (SELECT CONCAT_WS(' ', b.lastname, b.firstname) FROM tbl_users b WHERE b.id = a.driver_id) as drivername  
                  FROM tbl_fuel_monitoring a 
                  WHERE a.isDeleted = 0 
                  ORDER BY a.transaction_date DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($rows)) {
            echo "<tr><td colspan='5' class='text-center'>No Fuel Reports Yet</td></tr>";
        } else {
            foreach ($rows as $row) {
                echo "<tr>
                        <td>{$row['drivername']}</td>
                        <td class='transaction-id'>{$row['transaction_id']}</td>
                        <td class='transaction-date'>{$row['transaction_date']}</td>
                        <td>{$row['before_arrived']}</td>
                        <td>{$row['after_arrived']}</td>
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

        $("#fuel_id").val(id);
        $("#before_arrived").val(beforeArrived);
        $("#after_arrived").val(afterArrived);

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
function filterTable() {
    let transactionID = document.getElementById("search_transaction").value.toLowerCase();
    let startDate = document.getElementById("start_date").value;
    let endDate = document.getElementById("end_date").value;
    let table = document.getElementById("fuel_table");
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip table header
        let transactionCell = rows[i].getElementsByClassName("transaction-id")[0];
        let dateCell = rows[i].getElementsByClassName("transaction-date")[0];

        if (transactionCell && dateCell) {
            let transactionText = transactionCell.textContent.toLowerCase();
            let transactionDate = dateCell.textContent.trim();

            let showRow = true;

            // Filter by transaction ID
            if (transactionID && !transactionText.includes(transactionID)) {
                showRow = false;
            }

            // Filter by date range
            if (startDate || endDate) {
                let rowDate = new Date(transactionDate);
                let start = startDate ? new Date(startDate) : null;
                let end = endDate ? new Date(endDate) : null;

                if (start && rowDate < start) showRow = false;
                if (end && rowDate > end) showRow = false;
            }

            // Show or hide row based on filters
            rows[i].style.display = showRow ? "" : "none";
        }
    }
}

// Automatically filter as the user types
document.getElementById("search_transaction").addEventListener("keyup", filterTable);
document.getElementById("start_date").addEventListener("change", filterTable);
document.getElementById("end_date").addEventListener("change", filterTable);
</script>
