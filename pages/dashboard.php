
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
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-12">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <?php
                  $getalltotalcars = $conn->prepare("SELECT id FROM vehicles WHERE isActive =:isActive");
                  $getalltotalcars->execute(['isActive'=>1]);
                  $countgetalltotalcars = $getalltotalcars->rowCount();

                  echo "<h3> ". $countgetalltotalcars . "</h3>";
                ?>


                <p>Total Active Vehices</p>
              </div>
              <div class="icon">
                <i class="fa fa-car"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-12">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <?php
                  $getalltotalcarsInactive = $conn->prepare("SELECT id FROM vehicles WHERE isActive =:isActive");
                  $getalltotalcarsInactive->execute(['isActive'=>0]);
                  $countgetalltotalcarsInactive = $getalltotalcarsInactive->rowCount();

                  echo "<h3> ". $countgetalltotalcarsInactive . "</h3>";
                ?>
              <p>Total InActive Vehices</p>
              </div>
              <div class="icon">
                <i class="fa fa-times-circle"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-12">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                  <?php 
                  $get_all_partsnearly_lifespan = $conn->prepare("SELECT COUNT(*) AS nearly_expired_count
                  FROM tbl_v_vehicles_parts
                  WHERE vehicle_parts_lifespan IS NOT NULL 
                  AND DATE_ADD(added_date, INTERVAL vehicle_parts_lifespan MONTH) <= DATE_ADD(NOW(), INTERVAL 60 DAY)");
                    $get_all_partsnearly_lifespan->execute();
                  $count_get_all_partsnearly_lifespan = $get_all_partsnearly_lifespan->fetch();

                  echo "<h3>".$count_get_all_partsnearly_lifespan['nearly_expired_count']."</h3>";
         
                  ?>

                <p>Parts Nearly Lifespan (6O DAYS)</p>
              </div>
              <div class="icon">
                <i class="fa fa-cog"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-12">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                  <?php 
                  $get_all_vehiclenearly_lifespan = $conn->prepare("SELECT COUNT(*) AS nearly_expired_count
                  FROM vehicles
                  WHERE vehicle_lifespan IS NOT NULL 
                  AND DATE_ADD(created_at, INTERVAL vehicle_lifespan MONTH) <= DATE_ADD(NOW(), INTERVAL 60 DAY)");
                    $get_all_vehiclenearly_lifespan->execute();
                  $count_get_all_vehiclenearly_lifespan = $get_all_vehiclenearly_lifespan->fetch();

                  echo "<h3>".$count_get_all_vehiclenearly_lifespan['nearly_expired_count']."</h3>";
         
                  ?>

                <p>Vehicle Nearly Lifespan (6O DAYS)</p>
              </div>
              <div class="icon">
                <i class="fa fa-times"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>

        <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Transactions</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered tbl_recent" id="tbl_recent">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Transaction ID</th>
                            <th>OR NO.</th>
                            <th>Payment Option</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Total</th>
                            <th>Paid Amount</th>
                            <th>Remaining</th>
                            <th>Change</th>
                            <th>Added Date</th>
                        </tr>
                    </thead>
                    <tbody>
                      
                        <!-- $query = $conn->prepare("SELECT
                                a.id,
                                b.transaction_name,
                                a.or_number,
                                b.payment_option,
                                c.user_photo,
                                CONCAT_WS(' ', c.lastname, c.firstname) AS fullname,
                                a.total,
                                a.amount,
                                a.change_amount,
                                a.payed_date as added_date
                            FROM
                                tbl_c_payments a
                            LEFT JOIN tbl_c_transactions b ON a.transaction_id = b.id
                            LEFT JOIN tbl_users c ON b.student_id = c.id
                                
                                WHERE a.added_by = :added_by AND DATE(a.payed_date) = CURDATE() AND b.status != 'Voided'
                                                ORDER BY a.payed_date DESC
                                                LIMIT 15");
                        $query->execute(['added_by'=>$_SESSION['SESS_USER_ID']]);
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                              $remaining = $row['total'] - $row['amount'];
                              echo "<tr>";
                              echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                              echo "<td>" . htmlspecialchars($row['transaction_name']) . "</td>";
                              echo "<td>" . htmlspecialchars($row['or_number']) . "</td>";
                              
                              echo "<td>";
                              if ($row['payment_option'] == 'full') {
                                  echo "Full Payment";
                              } elseif ($row['payment_option'] == 'installment') {
                                  echo "Installment";
                              } else {
                                  echo "Invalid Payment Option";
                              }
                              echo "</td>";
                              echo "<td><img src='" . htmlspecialchars($row['user_photo']) . "' alt='User Photo' width='50' height='50'></td>";
                              echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                              echo "<td>&#8369; " . number_format($row['total'], 2) . "</td>";
                              echo "<td>&#8369; " . number_format($row['amount'], 2) . "</td>";
                              echo "<td>&#8369; " . number_format( ($remaining < 0) ? 0.00 : $remaining, 2) . "</td>";
                              echo "<td>&#8369; " . number_format($row['change_amount'], 2) . "</td>";
                              echo "<td>" . htmlspecialchars($row['added_date']) . "</td>";
                            echo "</tr>";
                        } -->
                     
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
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


<script>
  $('#tbl_recent').DataTable( {
    responsive: true,
    order: [[0, 'desc']] ,
    columnDefs: [
      { targets: 0, visible: false } // Hide the first column (index 0)
    ]
} );
</script>