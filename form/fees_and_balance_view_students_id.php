<?php 
include "includes/session.php";
include "includes/header.php";

$checkstudentExist = $conn->prepare("SELECT * FROM tbl_users WHERE id = :id");
$checkstudentExist->execute(['id' => $_GET['ids']]);
$countcheckstudentExist = $checkstudentExist->rowCount();

if ($countcheckstudentExist == 0) {
    header("Location: ../page_not_found.php");
    exit();
} else {
    $ftccheckstudentExist = $checkstudentExist->fetch();
    $student_id = $ftccheckstudentExist['id'];
    $student_no = $ftccheckstudentExist['alternative_id'];
    $photo = $ftccheckstudentExist['user_photo'];
    $name = $ftccheckstudentExist['lastname'] . ", " . $ftccheckstudentExist['firstname'] . " " . $ftccheckstudentExist['middlename'];
}

// Fetch transactions
$stmt = $conn->prepare("
    SELECT 
        a.id,
        a.transaction_name,
        a.total,
        a.total - COALESCE((SELECT SUM(b.amount) FROM tbl_c_payments b WHERE b.transaction_id = a.id), 0) AS remaining_balance,
        a.date_ordered
    FROM tbl_c_transactions a
    WHERE a.student_id = :student_id
");
$stmt->execute(['student_id' => $_GET['ids']]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>
<style>
    .dataTables_filter {
        display: none;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo ucwords(strtolower($name)) . "'s Profile ( " . $student_no . " )"; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Fee's and Balances</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

        <!-- Transaction Items Modal -->
    <!-- Transaction Items Modal -->
 <!-- Transaction Items Modal -->
    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaction Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Transaction Info -->
                    <p><strong>Transaction ID:</strong> <span id="modalTransactionID"></span></p>

                    <!-- Items Table -->
                    <h6>Transaction Items</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Amount (₱)</th>
                            </tr>
                        </thead>
                        <tbody id="transactionItems">
                            <tr><td colspan="3" class="text-center">No items found</td></tr>
                        </tbody>
                    </table>

                    <!-- Payment History Table -->
                    <h6>Payment History</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount Paid (₱)</th>
                                <th>Payment Date</th>
                            </tr>
                        </thead>
                        <tbody id="paymentHistory">
                            <tr><td colspan="3" class="text-center">No payments found</td></tr>
                        </tbody>
                    </table>

                    <!-- Total Amount & Remaining Balance -->
                    <p class="text-right">
                        <strong>Total Amount:</strong> ₱<span id="modalTotalAmount">0.00</span><br>
                        <strong>Remaining Balance:</strong> ₱<span id="modalRemainingBalance">0.00</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm">
                        <input type="hidden" id="paymentTransactionID">
                        <div class="form-group">
                            <label for="paymentAmount">Enter Payment Amount (₱)</label>
                            <input type="number" class="form-control" id="paymentAmount" min="1" step="0.01" required>
                            <small id="paymentError" class="text-danger"></small>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header"> 
                <img src="<?php echo $photo ?>" alt="Student Picture" width="20%">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search_id" id="search_id" placeholder="Search Transaction ID"> 
                    </div>
                    <div class="col-md-2">
                        <input type="button" class="btn btn-success" id="btn_search" value="Search">
                    </div>
                </div>         

                <div class="table-responsive mt-3">
                    <table id="tbl_1" class="table table-hover table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction #</th>
                                <th>Total Amount</th>
                                <th>Remaining Balance</th>
                                <th>Transaction Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($transactions) > 0): ?>
                                <?php foreach ($transactions as $index => $row): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($row['transaction_name']); ?></td>
                                        <td><?php echo number_format($row['total'], 2); ?></td>
                                        <td><?php echo number_format($row['remaining_balance'], 2); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['date_ordered'])); ?></td>
                                        <td>
                                            <button class="btn btn-info btn-sm">View</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center">No data available</td></tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Transaction #</th>
                                <th>Total Amount</th>
                                <th>Remaining Balance</th>
                                <th>Transaction Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <!-- /.card-body -->
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
    let student_id = "<?php echo $_GET['ids']; ?>";

    // Load transactions on page load
    loadTransactions(student_id, '');

    // Search button event listener
    $('#btn_search').click(function () {
        let transaction_id = $('#search_id').val().trim();
        loadTransactions(student_id, transaction_id);
    });

    $('#paymentForm').submit(function (e) {
        e.preventDefault();

        let transaction_id = $('#paymentTransactionID').val();
        let amount = parseFloat($('#paymentAmount').val());
        let maxAmount = parseFloat($('#paymentAmount').attr('max'));

        // Validation: Ensure amount is within allowed range
        if (amount <= 0 || amount > maxAmount) {
            $('#paymentError').text('Invalid amount. Ensure it is within the remaining balance.');
            return;
        } else {
            $('#paymentError').text('');
        }

        $.ajax({
            url: 'save_payment.php',
            type: 'POST',
            data: { transaction_id: transaction_id, amount: amount },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#paymentModal').modal('hide');
                    alert('Payment successfully added!');
                    loadTransactions(student_id, ''); // Refresh table
                } else {
                    alert('Error saving payment!');
                }
            }
        });
    });

    function loadTransactions(student_id, transaction_id) {
        $.ajax({
            url: 'fetch_transactions.php',
            type: 'GET',
            data: { student_id: student_id, transaction_id: transaction_id },
            dataType: 'json',
            success: function (data) {
                let tableBody = $('#tbl_1 tbody');
                tableBody.empty();

                if (data.length > 0) {
                    $.each(data, function (index, row) {
                        tableBody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${row.transaction_name}</td>
                                <td>₱${parseFloat(row.total).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                                <td>₱${parseFloat(row.remaining_balance).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                                <td>${row.date_ordered}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm viewTransaction" data-id="${row.id}"  data-transactionname="${row.transaction_name}" data-total="${row.total}" data-balance="${row.remaining_balance}">View</button>
                                    <button class="btn btn-success btn-sm addPayment" data-id="${row.id}" data-balance="${row.remaining_balance}">Add Payment</button>
                                </td>
                            </tr>
                        `);
                    });

                    // Attach event listener for view buttons
                    $('.viewTransaction').click(function () {
                        let transaction_id = $(this).data('id');
                        let transaction_name = $(this).data('transactionname');
                        let totalAmount = parseFloat($(this).data('total'));
                        let remainingBalance = parseFloat($(this).data('balance'));

                        // Set Transaction ID & Totals in Modal
                        $('#modalTransactionID').text(transaction_name);
                        $('#modalTotalAmount').text(totalAmount.toLocaleString('en-PH', { minimumFractionDigits: 2 }));
                        $('#modalRemainingBalance').text(remainingBalance.toLocaleString('en-PH', { minimumFractionDigits: 2 }));

                        // Load transaction items & payment history
                        loadTransactionItems(transaction_id);
                        loadPaymentHistory(transaction_id);
                        $('#transactionModal').modal('show');
                        });$('.viewTransaction').click(function () {
                        let transaction_id = $(this).data('id');
                        let totalAmount = parseFloat($(this).data('total'));
                        let remainingBalance = parseFloat($(this).data('balance'));

                        $('#modalTransactionID').text(transaction_name);
                        $('#modalTotalAmount').text(totalAmount.toLocaleString('en-PH', { minimumFractionDigits: 2 }));
                        $('#modalRemainingBalance').text(remainingBalance.toLocaleString('en-PH', { minimumFractionDigits: 2 }));

                        loadTransactionItems(transaction_id);
                        loadPaymentHistory(transaction_id);
                        $('#transactionModal').modal('show');
                    });

                    $('.addPayment').click(function () {
                        let transaction_id = $(this).data('id');
                        let remainingBalance = parseFloat($(this).data('balance'));

                        $('#paymentTransactionID').val(transaction_id);
                        $('#paymentAmount').val('');
                        $('#paymentAmount').attr('max', remainingBalance);
                        $('#paymentModal').modal('show');
                    });
                } else {
                    tableBody.append(`<tr><td colspan="6" class="text-center">No data available</td></tr>`);
                }
            }
        });
    }

    function loadTransactionItems(transaction_id) {
        $.ajax({
            url: 'fetch_transaction_items.php',
            type: 'GET',
            data: { transaction_id: transaction_id },
            dataType: 'json',
            success: function (data) {
                let itemsBody = $('#transactionItems');
                itemsBody.empty();

                if (data.length > 0) {
                    $.each(data, function (index, item) {
                        itemsBody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.item_name}</td>
                                <td>₱${parseFloat(item.amount).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                            </tr>
                        `);
                    });
                } else {
                    itemsBody.append(`<tr><td colspan="3" class="text-center">No items found</td></tr>`);
                }
            }
        });
    }

    function loadPaymentHistory(transaction_id) {
        $.ajax({
            url: 'fetch_payment_history.php',
            type: 'GET',
            data: { transaction_id: transaction_id },
            dataType: 'json',
            success: function (data) {
                let historyBody = $('#paymentHistory');
                historyBody.empty();

                if (data.length > 0) {
                    $.each(data, function (index, payment) {
                        historyBody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>₱${parseFloat(payment.amount).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                                <td>${payment.payed_date}</td>
                            </tr>
                        `);
                    });
                } else {
                    historyBody.append(`<tr><td colspan="3" class="text-center">No payments found</td></tr>`);
                }
            }
        });
    }
});
</script>
