<?php 
    include "includes/session.php";
    include "includes/header.php";
?>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Booking Status Chart</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Booking Status</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
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
          <button class="btn btn-primary mt-3" onclick="fetchBookingStatus()">Search</button>
          <div class="mt-4">
            <canvas id="bookingChart"></canvas>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
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
</body>
</html>