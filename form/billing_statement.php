
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
            <h1 class="m-0">Billing Statement</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->
              <li class="breadcrumb-item active">Billing Statement</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
<div class="modal fade" id="modal_billing_details" tabindex="-1" aria-labelledby="billingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Transaction Name: <span id="transaction_name"></span></h5>
                <h6>Items Purchased:</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody id="items_list">
                        <!-- Items will be loaded here -->
                    </tbody>
                </table>
                <h6>Payment History:</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Amount Paid</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody id="payments_list">
                        <!-- Payments will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <section class="content">

      <!-- Default box -->
      <div class="card">
        <!-- <div class="card-header">
          <h3 class="card-title">Title</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div> -->
        <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <input type="text" id="search_billing" class="form-control mb-2" placeholder="Search Transaction...">

          </div>
         
        </div>
     

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Transaction Name</th>
                        <th>Added Date</th>
                        <th>Total Amount</th>
                        <th>Total Balance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="billing_history">
                    <!-- Data will be inserted here via AJAX -->
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <!-- <div class="card-footer">
          Footer
        </div> -->
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

      </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include 'includes/footer.php'; ?>
</body>
</html>


<script>
 $(document).ready(function () {
    var student_id = "<?php echo $_SESSION['SESS_USER_ID']; ?>"; // Replace with dynamic student ID

    // Load billing history
    $.ajax({
        url: "fetch_billing_history.php",
        type: "POST",
        data: { student_id: student_id },
        dataType: "json",
        success: function (data) {
            var rows = "";
            
            if (data.length === 0) {
                rows = `
                    <tr>
                        <td colspan="5" class="text-center">No transactions yet.</td>
                    </tr>
                `;
            } else {
                data.forEach(function (row) {
                    rows += `
                        <tr>
                            <td>${row.transaction_name}</td>
                            <td>${row.added_date}</td>
                            <td>${parseFloat(row.total).toFixed(2)}</td>
                            <td>${parseFloat(row.total_balance).toFixed(2)}</td>
                            <td><button class="btn btn-primary btn-sm view-details" data-id="${row.id}" data-name="${row.transaction_name}">View</button></td>
                        </tr>
                    `;
                });
            }

            $("#billing_history").html(rows);
        },
        error: function () {
            $("#billing_history").html(`
                <tr>
                    <td colspan="5" class="text-center text-danger">Error loading transactions.</td>
                </tr>
            `);
        }
    });

    // Show transaction details in modal
    $(document).on("click", ".view-details", function () {
        var transaction_id = $(this).data("id");
        var transaction_name = $(this).data("name");
        $("#transaction_name").text(transaction_name);

        $.ajax({
            url: "fetch_transaction_details.php",
            type: "POST",
            data: { transaction_id: transaction_id },
            dataType: "json",
            success: function (data) {
                var items_list = "";
                data.items.forEach(function (item) {
                    items_list += `<tr><td>${item.item_name}</td><td>${parseFloat(item.amount).toFixed(2)}</td></tr>`;
                });
                $("#items_list").html(items_list);

                var payments_list = "";
                data.payments.forEach(function (payment) {
                    payments_list += `<tr><td>${parseFloat(payment.amount).toFixed(2)}</td><td>${payment.payed_date}</td></tr>`;
                });
                $("#payments_list").html(payments_list);

                $("#modal_billing_details").modal("show");
            }
        });
    });

    function loadBillingHistory() {
        $.ajax({
            url: "fetch_billing_history.php",
            type: "POST",
            data: { student_id: student_id },
            dataType: "json",
            success: function (data) {
                var rows = "";

                if (data.length === 0) {
                    rows = `
                        <tr>
                            <td colspan="5" class="text-center">No transactions yet.</td>
                        </tr>
                    `;
                } else {
                    data.forEach(function (row) {
                        rows += `
                            <tr class="billing-row">
                                <td>${row.transaction_name}</td>
                                <td>${row.added_date}</td>
                                <td>${parseFloat(row.total).toFixed(2)}</td>
                                <td>${parseFloat(row.total_balance).toFixed(2)}</td>
                                <td><button class="btn btn-primary btn-sm view-details" data-id="${row.id}" data-name="${row.transaction_name}">View</button></td>
                            </tr>
                        `;
                    });
                }

                $("#billing_history").html(rows);
            },
            error: function () {
                $("#billing_history").html(`
                    <tr>
                        <td colspan="5" class="text-center text-danger">Error loading transactions.</td>
                    </tr>
                `);
            }
        });
    }
    loadBillingHistory(); // Load transactions on page load

    $("#search_billing").on("keyup", function () {
        var searchText = $(this).val().toLowerCase();
        $(".billing-row").each(function () {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(searchText));
        });
    });
});

</script>