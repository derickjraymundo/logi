
<?php 
    include "includes/session.php";
    include "includes/header.php";
?>


<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->
              <li class="breadcrumb-item active">Dashboard<span id="sptestla"></span></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Make a Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <input type="hidden" id="transaction_id" name="transaction_id">
                    <div class="form-group">
                        <label>Amount Due</label>
                        <input type="text" class="form-control" id="amount_due" readonly>
                    </div>
                    <div class="form-group">
                        <label>Amount to Pay</label>
                        <input type="number" class="form-control" id="amount_paid" name="amount_paid" min="1" required>
                    </div>
                    <div id="paypal-button-container"></div>

                    <!-- <button type="submit" class="btn btn-success">Proceed to Pay</button> -->
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <?php
                  $get_total_pendingorder_today = $conn->prepare("SELECT id FROM tbl_driver_book WHERE booking_status = 0 AND driver_id = :driver_id AND DATE(booking_date) = CURDATE()");
                  $get_total_pendingorder_today->execute(['driver_id'=>$_SESSION['SESS_USER_ID']]);
                  $countget_total_pendingorder_today = $get_total_pendingorder_today->rowCount();

                  echo "<h3> " .$countget_total_pendingorder_today. "</h3>";
                ?>


                <p>Today Pending Booking</p>
              </div>
              <div class="icon">
                <i class="ion ion-cash"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
    
                  <?php
                    $get_total_order_today = $conn->prepare("SELECT id FROM tbl_driver_book WHERE booking_status = 3 AND driver_id = :driver_id AND DATE(booking_date) = CURDATE()");
                    $get_total_order_today->execute(['driver_id'=>$_SESSION['SESS_USER_ID']]);
                    $countget_total_order_today = $get_total_order_today->rowCount();

                    echo "<h3> " .$countget_total_order_today. "</h3>";
                  ?>

    
              

                <p>Today's Total Booking Done</p>
              </div>
              <div class="icon">
                <i class="ion ion-arrow-graph-up-right"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
          
              <?php
                    $get_total_order_today_cancelled = $conn->prepare("SELECT id FROM tbl_driver_book WHERE booking_status = 2 AND driver_id = :driver_id AND DATE(booking_date) = CURDATE()");
                    $get_total_order_today_cancelled->execute(['driver_id'=>$_SESSION['SESS_USER_ID']]);
                    $countget_total_order_today_cancelled = $get_total_order_today_cancelled->rowCount();

                    echo "<h3> " .$countget_total_order_today_cancelled. "</h3>";
                  ?>

    

                <p>Today's Total Cancelled</p>
              </div>
              <div class="icon">
                <i class="ion ion-pin"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

        </div>

        
        <di<div class="card">
    <div class="card-header">
        <h3 class="card-title">Today's Transaction</h3>
    </div>
    <div class="card-body">
    <?php

$query = "SELECT 
            id, 
            booking_id, 
            `froms`, 
            `tos`, 
            booking_date, 
            booking_status
            -- CASE 
            --     WHEN booking_status = 0 THEN 'Pending' 
            --     WHEN booking_status = 1 THEN 'Ongoing' 
            --     WHEN booking_status = 2 THEN 'Cancelled' 
            --     WHEN booking_status = 3 THEN 'Done' 
            --     ELSE 'Unknown' 
            -- END AS booking_status
          FROM tbl_driver_book
          WHERE DATE(booking_date) = CURDATE()";

$stmt = $conn->prepare($query);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <!-- <th>Driver</th> -->
            <th>Book ID</th>
            <th>From</th>
            <th>To</th>
            <th>Booking Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
        if (count($bookings) > 0) {
            $count = 1;
            foreach ($bookings as $row) {
                // Determine Bootstrap badge class based on status
                $badgeClass = "";
                $statusText = "";

                switch ($row['booking_status']) {
                    case 0:
                        $badgeClass = "badge bg-warning text-dark"; // Pending
                        $statusText = "Pending";
                        break;
                    case 1:
                        $badgeClass = "badge bg-primary"; // Ongoing
                        $statusText = "Ongoing";
                        break;
                    case 2:
                        $badgeClass = "badge bg-danger"; // Cancelled
                        $statusText = "Cancelled";
                        break;
                    case 3:
                        $badgeClass = "badge bg-success"; // Done
                        $statusText = "Done";
                        break;
                    default:
                        $badgeClass = "badge bg-secondary"; // Unknown
                        $statusText = "Unknown";
                }

                echo "<tr>
                        <td>{$count}</td>
                        <td>{$row['booking_id']}</td>
                        <td>{$row['froms']}</td>
                        <td>{$row['tos']}</td>
                        <td>{$row['booking_date']}</td>
                        <td><span class='$badgeClass'>$statusText</span></td>
                      </tr>";
                $count++;
            }
        } else {
            echo "<tr><td colspan='7' class='text-center'>No bookings found for today.</td></tr>";
        }
        ?>
    </tbody>
</table>
</div>
    </div>
</div>


        <!-- /.row -->
        <!-- Main row -->

        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include 'includes/footer.php'; ?>
</body>
</html>

<script src="https://www.paypal.com/sdk/js?client-id=AWLXjGmvT6APusbWeoLzhY_JhImpT7K8ZcFYPZdp7juBIOXx1H7rQ7vYZyrGvJtSyU4o2faHmHasj-AQ&currency=PHP"></script>

<script>
  $('#tbl_recent').DataTable( {
    responsive: true,
    order: [[0, 'desc']] ,
    columnDefs: [
      { targets: 0, visible: false } // Hide the first column (index 0)
    ]
} );


</script>

  <script>
  $(document).ready(function() {
    $('.btn-pay').click(function() {
        let transaction_id = $(this).data('id');
        let amount_due = $(this).data('amount');

        $('#transaction_id').val(transaction_id);
        $('#amount_due').val(amount_due);
        $('#paymentModal').modal('show');

        // Render PayPal Button
        $('#paypal-button-container').html(''); // Clear previous buttons
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: amount_due
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    let formData = {
                        transaction_id: transaction_id,
                        amount_paid: amount_due,
                        payment_status: 'Completed',
                        payer_email: details.payer.email_address,
                        payer_name: details.payer.name.given_name + " " + details.payer.name.surname,
                        paypal_order_id: details.id
                    };

                    $.ajax({
                        url: 'process_payment.php',
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            alert(response);
                            $('#paymentModal').modal('hide');
                            location.reload();
                        }
                    });
                });
            }
        }).render('#paypal-button-container');
    });
});
</script>
