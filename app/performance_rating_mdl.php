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
                    <h1>Employee Performance Rating</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Performance Rating</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Search Employee Performance</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <div class="search-form">
                    <h4>Search for Employee Performance</h4>
                    <input type="text" id="employee_id" class="form-control" placeholder="Enter Employee ID">
                    <input type="date" id="start_date" class="form-control mt-2" placeholder="Start Date">
                    <input type="date" id="end_date" class="form-control mt-2" placeholder="End Date">
                    <button class="btn btn-primary mt-2" onclick="searchEmployee()">Search</button>
                </div>

                <!-- Performance Info -->
                <div id="performance-info" class="mt-4">
                    <h5>Performance Rating</h5>
                    <p id="employee-name"></p>
                    <p id="performance-rating"></p>
                    <p id="on-time-days"></p>
                    <p id="missed-days"></p>
                    <p id="late-days"></p>
                </div>

                <!-- Legends for Performance Rating -->
                <div id="performance-legend" class="mt-4">
                    <h5>Performance Rating Legend</h5>
                    <ul>
                        <li><strong>On-time Days</strong>: Number of days the employee was on time (clocked in and out within the scheduled time).</li>
                        <li><strong>Missed Days</strong>: Number of days the employee missed (no clock-in/out recorded for that day).</li>
                        <li><strong>Late Days</strong>: Number of days the employee was late (clock-in after the scheduled time or clock-out before the scheduled time).</li>
                        <li><strong>Performance Rating</strong>: A percentage based on the employee's on-time, missed, and late days. A higher percentage means better performance.</li>
                    </ul>
                </div>
            </div>
            <!-- /.card-body -->

            <!-- /.card-footer-->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include 'includes/footer.php'; ?>

<script>
    function searchEmployee() {
    var employee_id = document.getElementById('employee_id').value;
    var start_date = document.getElementById('start_date').value;
    var end_date = document.getElementById('end_date').value;

    // Prepare the data for sending
    var data = 'employee_id=' + employee_id;
    if (start_date) data += '&start_date=' + start_date;
    if (end_date) data += '&end_date=' + end_date;

    fetch('performance_rating.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            // Clear previous content before updating
            var performanceInfo = document.getElementById('performance-info');
            performanceInfo.innerHTML = "";

            // Add performance rating details
            performanceInfo.innerHTML += `<h5>Performance Rating</h5>
                <p id="employee-name">Employee: ${data.employee_name}</p>
                <p id="performance-rating">Performance: ${data.performance}%</p>
                <p id="on-time-days">On-time Days: ${data.on_time_days}</p>
                <p id="missed-days">Missed Days: ${data.missed_days}</p>
                <p id="late-days">Late Days: ${data.late_days}</p>`;

            // Display completed bookings (clearing previous results first)
            var bookingsHtml = "<h5>Completed Bookings</h5><ul>";
            data.bookings.forEach(booking => {
                bookingsHtml += `<li>Booking ID: ${booking.booking_id}, From: ${booking.froms}, To: ${booking.tos}, Date: ${booking.booking_date}, Status: ${booking.booking_status}</li>`;
            });
            bookingsHtml += "</ul>";

            performanceInfo.innerHTML += bookingsHtml;
        }
    })
    .catch(error => {
        console.error("Error fetching performance data:", error);
    });
}

</script>
</body>
</html>
