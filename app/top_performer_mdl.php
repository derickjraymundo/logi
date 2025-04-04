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
                    <h1>Top Performers</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Top Performers</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
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
    </section>
</div>

<?php include 'includes/footer.php'; ?>

<script>
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
            var tableBody = document.getElementById('top-performers-list');
            tableBody.innerHTML = '';
            
            if (data.length > 0) {
                data.forEach((employee, index) => {
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
</script>
</body>
</html>
