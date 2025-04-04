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
                    <h1>Fuel Monitoring Chart</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Fuel Monitoring</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Fuel Data</h3>
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
                <button class="btn btn-primary mt-3" onclick="fetchFuelData()">Search</button>

                <div class="mt-4">
                    <canvas id="fuelChart"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function fetchFuelData() {
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
</script>
</body>
</html>
