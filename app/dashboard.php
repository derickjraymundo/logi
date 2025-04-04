
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
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <?php
                  $get_all_active_vehicles = $conn->prepare("SELECT id FROM vehicles WHERE isActive = 1");
                  $get_all_active_vehicles->execute();
                  $countget_all_active_vehicles = $get_all_active_vehicles->rowCount();

                  echo "<h3>" . $countget_all_active_vehicles . "</h3>";
                ?>


                <p>Total Active Vehicles</p>
              </div>
              <div class="icon">
                <i class="fa fa-car"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
              <?php
                  $get_all_inactive_vehicles = $conn->prepare("SELECT id FROM vehicles WHERE isActive =0 ");
                  $get_all_inactive_vehicles->execute();
                  $countget_all_inactive_vehicles = $get_all_inactive_vehicles->rowCount();

                  echo "<h3>" . $countget_all_inactive_vehicles . "</h3>";
                ?>


                <p>Total Inactive Vehicles</p>
              </div>
              <div class="icon">
                <i class="fa fa-times-circle"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                  <?php
                    $get_all_manufacturers = $conn->prepare("SELECT id FROM tbl_setup_vehicle_manufacturers WHERE isDeleted =0 ");
                    $get_all_manufacturers->execute();
                    $countget_get_all_manufacturers = $get_all_manufacturers->rowCount();

                    echo "<h3>" . $countget_all_inactive_vehicles . "</h3>";
                  ?>

                <p>Total Manufacturer's</p>
              </div>
              <div class="icon">
                <i class="fa fa-building"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
              <?php
                  $get_all_users_active = $conn->prepare("SELECT id FROM tbl_users WHERE isDeleted = 0 AND id != :id AND user_type_id = :user_type_id");
                  $get_all_users_active->execute(['id'=>$_SESSION['SESS_USER_ID'], 'user_type_id'=>2]);
                  $count_get_all_users_active = $get_all_users_active->rowCount();

                  echo "<h3>".$count_get_all_users_active."</h3>";
                ?>
              

                <p>Total Mechanics</p>
              </div>
              <div class="icon">
              
                <i class="fa fa-users"></i>
              </div>
              <a href="setup_users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>



        <?php
// Get current date
$currentDate = date('Y-m-d');

// Query to fetch bookings for the current date
$sql = "SELECT a.*,(        SELECT CONCAT_WS(' ', b.lastname, b.firstname, b.middlename) FROM 
        tbl_users b WHERE b.id = a.driver_id) as drivername FROM tbl_driver_book a WHERE DATE(a.booking_date) = :currentDate";
$stmt = $conn->prepare($sql);
$stmt->execute(['currentDate' => $currentDate]);
$bookings = $stmt->fetchAll();

// Count total rows
$totalRows = count($bookings);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Today's Transactions</h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Driver ID</th>
                                <th>Booking ID</th>
                                <!-- <th>From</th> -->
                                <th>To</th>
                                <th>Booking Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><?php echo $totalRows--; ?></td> <!-- Decrementing ID -->
                                    <td><?php echo htmlspecialchars($booking['drivername']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                                    <!-- <td><?php echo htmlspecialchars($booking['froms']); ?></td> -->
                                    <td><?php echo htmlspecialchars($booking['tos']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                                    <td>
                                        <?php
                                        $status = (int) $booking['booking_status'];
                                        $badgeClass = '';
                                        $statusText = '';

                                        switch ($status) {
                                            case 0:
                                                $badgeClass = 'badge bg-warning';
                                                $statusText = 'Pending';
                                                break;
                                            case 1:
                                                $badgeClass = 'badge bg-primary';
                                                $statusText = 'On-going';
                                                break;
                                            case 2:
                                                $badgeClass = 'badge bg-danger';
                                                $statusText = 'Cancelled';
                                                break;
                                            case 3:
                                                $badgeClass = 'badge bg-success';
                                                $statusText = 'Done';
                                                break;
                                        }
                                        ?>
                                        <span class="<?php echo $badgeClass; ?>">
                                            <?php echo $statusText; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                  <h3 class="card-title">Vehicle Parts Nearly Lifespan</h3>
              </div>

              <div class="card-body">
                <div class="table-responsive">
              <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <!-- <th>#</th> -->
                            <th>Model</th>
                            <th>License Plate</th>
                            <th>Part Name</th>
                            <th>Lifespan (Months)</th>
                            <th>Remaining Time</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                        // Fetch vehicle parts that are nearing expiration (within 60 days)
                        $stmt = $conn->prepare("
                            SELECT t.model, t.license_plate, v.id, v.vehicle_parts_lifespan, v.added_date, s.vehicle_parts_name
                            FROM tbl_v_vehicles_parts v
                            INNER JOIN tbl_setup_vehicle_parts s ON v.vehicle_parts_id = s.id
                            INNER JOIN vehicles t ON v.vehicle_id = t.id
                            WHERE v.vehicle_parts_lifespan IS NOT NULL
                            ORDER BY v.added_date ASC
                        ");
                        $stmt->execute();
                        $count = 0;
                        $hasData = false;

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $count++;

                            // Calculate expiration date
                            $expiry_date = date("Y-m-d", strtotime("+{$row['vehicle_parts_lifespan']} months", strtotime($row['added_date'])));
                            $remaining_days = floor((strtotime($expiry_date) - time()) / (60 * 60 * 24));

                            // Show only parts expiring within 60 days
                            if ($remaining_days <= 60) {
                                $hasData = true;
                        ?>
                                <tr>
                                    <!-- <td><?php echo $count; ?></td> -->
                                    <td><?php echo $row['model']; ?></td>
                                    <td><?php echo $row['license_plate']; ?></td>
                                    <td><?php echo $row['vehicle_parts_name']; ?></td>
                                    <td><?php echo $row['vehicle_parts_lifespan'] . " Months"; ?></td>
                                    <td><?php echo ($remaining_days > 0) ? $remaining_days . " Days Left" : "<span class='text-danger'>Expired</span>"; ?></td>
                                </tr>
                        <?php
                            }
                        }

                        // If no data found, show message
                        if (!$hasData) {
                            echo "<tr><td colspan='5' class='text-center text-muted'>No vehicle parts nearing expiration</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                </div>
              </div>
              </div>
          </div>


            <div class="col-md-6">
              <div class="card">
              <div class="card-header">
                  <h3 class="card-title">Vehicle Nearly Lifespan</h3>
              </div>

              <div class="card-body">
                <div class="table-responsive">
                <?php 
                  // Get the current date
                  $currentDate = new DateTime();
                  $currentDateFormatted = $currentDate->format('Y-m-d'); // Format as 'YYYY-MM-DD'

                  // Query to get vehicles with lifespan within the next 30 days
                  $query = $conn->query("
                      SELECT a.id, a.model, a.make, b.manufacturer_name, a.year, a.vehicle_type, c.vehicle_type_name, a.license_plate,
                            a.vehicle_lifespan, a.created_at
                      FROM vehicles a
                      LEFT JOIN tbl_setup_vehicle_manufacturers b ON b.id = a.make
                      LEFT JOIN tbl_setup_vehicle_types c ON c.id = a.vehicle_type
                      WHERE DATE_ADD(a.created_at, INTERVAL a.vehicle_lifespan MONTH) BETWEEN '$currentDateFormatted' AND DATE_ADD('$currentDateFormatted', INTERVAL 30 DAY)
                      ORDER BY a.created_at DESC
                    ");

                    if ($query->rowCount() > 0) {  
                    ?>
                        <table class="table table-bordered" id="vehicleTable">
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>Make</th>
                                    <th>Lifespan</th>
                                    <th>License Plate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {  
                                    $addedDate = new DateTime($row['created_at']);
                                    $lifespanMonths = (int) $row['vehicle_lifespan'];

                                    if ($lifespanMonths > 0) {
                                        $expiryDate = clone $addedDate;
                                        $expiryDate->modify("+$lifespanMonths months");
                                        $remainingDays = (new DateTime())->diff($expiryDate)->days;
                                        $remainingText = ($remainingDays > 0) ? "$remainingDays days left" : "Expired";
                                    } else {
                                        $remainingText = "N/A";
                                    }
                                ?>
                                <tr class="vehicle-row">
                                    <td><?php echo $row['model']; ?></td>
                                    <td><?php echo $row['manufacturer_name']; ?></td>
                                    <td style="background-color: red; color: white;">
                                        <?php echo $remainingText; ?>
                                    </td>
                                    <td><?php echo $row['license_plate']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="alert alert-info text-center">No vehicles expiring within 30 days.</div>
                    <?php } ?>

                </div>

              </div>
              
 
             


              </div>
            </div>
        </div>


        <div class="row">
          <div class="col-12">
       
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Vehicle Deliveries &nbsp; </h4>
                    <div class="mb-3">
                        <label for="filter" class="form-label">Filter By:</label>
                        <select id="filter" class="form-select">
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <canvas id="vehicleChart"></canvas>
                </div>
            </div>
          
          </div>
        </div>

        <div class="row">
          <?php
          function displayVehicleReservations($status, $conn) {
            $query = $conn->prepare("
                SELECT b.model, b.license_plate, a.date_requested, a.time_requested, a.request_status
                FROM tbl_vehicle_requests a
                LEFT JOIN vehicles b ON b.id = a.vehicle_id
                WHERE a.request_status = ?
            ");
            $query->execute([$status]);
        
            echo '<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Model</th>
                            <th>License Plate</th>
                            <th>Date Requested</th>
                            <th>Time Requested</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            if ($query->rowCount() > 0) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['model']}</td>
                            <td>{$row['license_plate']}</td>
                            <td>{$row['date_requested']}</td>
                            <td>{$row['time_requested']}</td>
                            <td>{$row['request_status']}</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No records found.</td></tr>";
            }
            
            echo '</tbody></table>';
        }
          ?>
              <div class="col-5">
                  <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Vehicle Reservations</h3>
                </div>
                      <div class="card-body">
                          <!-- <h4 class="card-title"> Reservation's</h4> -->
                          
                          <!-- Tabs Navigation -->
                          <ul class="nav nav-tabs" id="vehicleTabs">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#reservedTab">Reserved</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#approvedTab">Approved</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#cancelledTab">Cancelled</a>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="reservedTab">
       <div class="table-responsive">
       <?php displayVehicleReservations('Reserved', $conn); ?>
       </div>
      
    </div>
    <div class="tab-pane fade" id="approvedTab">
    <div class="table-responsive">
        <?php displayVehicleReservations('Approved', $conn); ?>
        </div>
    </div>
    <div class="tab-pane fade" id="cancelledTab">
    <div class="table-responsive">
        <?php displayVehicleReservations('Cancelled', $conn); ?>
        </div>
    </div>
</div>

                      </div>
                  </div>
              </div>


              <div class="col-7">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Fuel Monitoring</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Driver</th>
                        <th>Vehicle</th>
                        <th>Transaction Date</th>
                        <th>Before Arrival</th>
                        <th>After Arrival</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $conn->query("
                        SELECT 
                            a.id,
                            a.transaction_id,
                            (SELECT CONCAT_WS(' ', b.lastname, b.firstname) FROM tbl_users b WHERE b.id = a.driver_id LIMIT 1) AS drivername,
                            (SELECT CONCAT_WS(' ', c.model, '- (License: ', c.license_plate, ')') FROM vehicles c WHERE c.id = a.vehicle_id) AS vehiclename,
                            a.transaction_date,
                            a.before_arrived,
                            a.after_arrived
                        FROM tbl_fuel_monitoring a
                    ");

                    if ($query->rowCount() > 0) {
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                    <td>{$row['transaction_id']}</td>
                                    <td>{$row['drivername']}</td>
                                    <td>{$row['vehiclename']}</td>
                                    <td>{$row['transaction_date']}</td>
                                    <td>{$row['before_arrived']}</td>
                                    <td>{$row['after_arrived']}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No fuel monitoring records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>


          </div>





        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recently Added Vehicles</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered tbl_recent" id="tbl_recent">
                            <thead>
                                <tr>
                                    <th>Manufacturer</th>
                                    <th>Model</th>
                                    <th>Year</th>
                                    <th>Vehicle Type</th>
                                    <th>License Plate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = $conn->prepare("SELECT
                                              a.id,
                                              b.manufacturer_name,
                                              a.model,
                                              a.year,
                                              c.vehicle_type_name,
                                              a.license_plate
                                              
                                            FROM
                                              vehicles a
                                            LEFT JOIN
                                              tbl_setup_vehicle_manufacturers b
                                              ON b.id = a.make
                                            LEFT JOIN
                                              tbl_setup_vehicle_types c
                                              ON c.id = a.vehicle_type
                                            WHERE created_at >= NOW() - INTERVAL 3 DAY
                                            ORDER BY id DESC;
                                          ");
                                $query->execute();
                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                      echo "<td>" . htmlspecialchars($row['manufacturer_name']) . "</td>";
                                      echo "<td>" . htmlspecialchars($row['model']) . "</td>";
                                      echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                                      echo "<td>" . htmlspecialchars($row['vehicle_type_name']) . "</td>";
                                      echo "<td>" . htmlspecialchars($row['license_plate']) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>


        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Top Performers</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="date" id="start_date" class="form-control" placeholder="Start Date">
                        </div>
                        <div class="col-md-4">
                            <input type="date" id="end_date" class="form-control" placeholder="End Date">
                        </div>
                        <div class="col-md-2">
                            <select id="month" class="form-control">
                                <option value="">Select Month</option>
                                <?php for ($m = 1; $m <= 12; $m++) { ?>
                                    <option value="<?php echo $m; ?>"><?php echo date('F', mktime(0, 0, 0, $m, 1)); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="year" class="form-control">
                                <option value="">Select Year</option>
                                <?php for ($y = date('Y'); $y >= 2000; $y--) { ?>
                                    <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3" onclick="fetchTopPerformers()">Search</button>

                    <div class="mt-4">
                        <h5>Top 10 Employees</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Employee Name</th>
                                    <th>Performance Rating (%)</th>
                                </tr>
                            </thead>
                            <tbody id="top-performers-list">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Fuel Data</h3>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <input type="date" id="start_date2" class="form-control" placeholder="Start Date">
                    </div>
                    <div class="col-md-4">
                        <input type="date" id="end_date2" class="form-control" placeholder="End Date">
                    </div>
                    <div class="col-md-2">
                        <select id="month2" class="form-control">
                            <option value="">Select Month</option>
                            <?php for ($m = 1; $m <= 12; $m++) { ?>
                                <option value="<?php echo $m; ?>"><?php echo date('F', mktime(0, 0, 0, $m, 1)); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="year2" class="form-control">
                            <option value="">Select Year</option>
                            <?php for ($y = date('Y'); $y >= 2000; $y--) { ?>
                                <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary mt-3" onclick="fetchFuelData2()">Search</button>

                <div class="mt-4">
                    <canvas id="fuelChart"></canvas>
                </div>
            </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Booking Status</h3>
                </div>
                <div class="card-body">
                <div class="row">
            <div class="col-md-4">
              <input type="date" id="start_date3" class="form-control" placeholder="Start Date">
            </div>
            <div class="col-md-4">
              <input type="date" id="end_date3" class="form-control" placeholder="End Date">
            </div>
            <div class="col-md-2">
              <select id="month3" class="form-control">
                <option value="">Select Month</option>
                <?php for ($m = 1; $m <= 12; $m++) { ?>
                    <option value="<?php echo $m; ?>"><?php echo date('F', mktime(0, 0, 0, $m, 1)); ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-md-2">
              <select id="year3" class="form-control">
                <option value="">Select Year</option>
                <?php for ($y = date('Y'); $y >= 2000; $y--) { ?>
                    <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <button class="btn btn-primary mt-3" onclick="fetchBookingStatus3()">Search</button>
          <div class="mt-4">
            <canvas id="bookingChart"></canvas>
          </div>
            </div>
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


<script>
    $(document).ready(function() {
        let vehicleChart;
        
       

        function fetchData(filter) {
    $.ajax({
        url: "get_vehicle_requests.php",
        type: "GET",
        data: { filter: filter },
        dataType: "json",
        success: function(response) {

            let labels = response.map(item => item.date);
            let values = response.map(item => Math.floor(item.count)); // Ensure whole numbers

            if (vehicleChart) {
                vehicleChart.destroy();
            }

            let ctx = document.getElementById("vehicleChart").getContext("2d");



            vehicleChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Delivered Vehicles",
                        data: values,
                        backgroundColor: "rgba(75, 192, 192, 0.6)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1, // Force increments of 1
                                precision: 0, // Ensure only whole numbers
                                callback: function(value) {
                                    return Math.floor(value); // Display only whole numbers
                                }
                            }
                        }
                    }
                }
            });
        }
    });
}


        fetchData("weekly");

        $("#filter").change(function() {
            let filter = $(this).val();
            fetchData(filter);
        });

        $('#tbl_recent').DataTable( {
            responsive: true,
            order: [[0, 'desc']] ,
            columnDefs: [
              { targets: 0, visible: false } // Hide the first column (index 0)
            ]
        } );  
    });
    function fetchTopPerformers() {
    var start_date = document.getElementById('start_date').value;
    var end_date = document.getElementById('end_date').value;
    var month = document.getElementById('month').value;
    var year = document.getElementById('year').value;
    
    var data = '';
    if (start_date) data += '&start_date=' + start_date;
    if (end_date) data += '&end_date=' + end_date;
    if (month) data += '&month=' + month;
    if (year) data += '&year=' + year;

    fetch('top_performer.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
    .then(response => response.json())
    .then(data => {
        console.log("API Response:", data); // Debugging step

        var tableBody = document.getElementById('top-performers-list');
        tableBody.innerHTML = '';
        
        if (data.top_performers && data.top_performers.length > 0) {
            data.top_performers.forEach((employee, index) => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${employee.employee_name}</td>
                        <td>${employee.performance}%</td>
                    </tr>
                `;
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="3">No data found</td></tr>';
        }
    })
    .catch(error => {
        console.error("Error fetching top performers:", error);
    });
}
function fetchFuelData2() {
        var start_date = document.getElementById('start_date2').value;
        var end_date = document.getElementById('end_date2').value;
        var month = document.getElementById('month2').value;
        var year = document.getElementById('year2').value;
        
        var data = '';
        if (start_date) data += '&start_date=' + start_date;
        if (end_date) data += '&end_date=' + end_date;
        if (month) data += '&month=' + month;
        if (year) data += '&year=' + year;

        fetch('fuelmonitoring_dash.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: data
        })
        .then(response => response.json())
        .then(data => {
            var labels = data.map(item => item.date);
            var beforeArrived = data.map(item => item.total_before);
            var afterArrived = data.map(item => item.total_after);

            renderChart2(labels, beforeArrived, afterArrived);
        })
        .catch(error => {
            console.error("Error fetching fuel data:", error);
        });
    }

    function renderChart2(labels, beforeArrived, afterArrived) {
        var ctx = document.getElementById('fuelChart').getContext('2d');

        // Ensure previous chart instance exists before trying to destroy it
        if (window.fuelChart && typeof window.fuelChart.destroy === 'function') {
            window.fuelChart.destroy();
        }

        window.fuelChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Fuel Before Arrival (Liters)',
                        data: beforeArrived,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 0, 255, 0.2)',
                        fill: true
                    },
                    {
                        label: 'Fuel After Arrival (Liters)',
                        data: afterArrived,
                        borderColor: 'red',
                        backgroundColor: 'rgba(255, 0, 0, 0.2)',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: { display: true, text: 'Date' }
                    },
                    y: {
                        title: { display: true, text: 'Fuel (Liters)' },
                        beginAtZero: true
                    }
                }
            }
        });
    }
    function fetchBookingStatus3() {
    var start_date = document.getElementById('start_date3').value;
    var end_date = document.getElementById('end_date3').value;
    var month = document.getElementById('month3').value;
    var year = document.getElementById('year3').value;

    var params = new URLSearchParams();
    if (start_date) params.append('start_date', start_date);
    if (end_date) params.append('end_date', end_date);
    if (month) params.append('month', month);
    if (year) params.append('year', year);

    fetch('booking_chart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.successful !== undefined && data.unsuccessful !== undefined) {
            renderChart3(data.successful, data.unsuccessful);
        } else {
            console.error("Invalid response structure:", data);
        }
    })
    .catch(error => console.error("Error fetching booking data:", error));
}

function renderChart3(successful, unsuccessful) {
    var ctx = document.getElementById('bookingChart').getContext('2d');

    // Check if the chart exists before destroying it
    if (window.bookingChart instanceof Chart) {
        window.bookingChart.destroy();
    }

    // Create a new Chart instance
    window.bookingChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Successful', 'Unsuccessful'],
            datasets: [{
                label: 'Bookings',
                data: [successful, unsuccessful],
                backgroundColor: ['#28a745', '#dc3545'],
                borderColor: ['#218838', '#c82333'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

</script>