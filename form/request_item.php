<?php 
    include "includes/session.php";
    include "includes/header.php";
    include "includes/sidebar.php";
    include "includes/topbar.php";

    // Fetch items from the database
    $stmt = $conn->prepare("SELECT id, item_name FROM tbl_items WHERE isDeleted = 0");
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch transaction history for the logged-in user
    $user_id = $_SESSION['SESS_USER_ID'] ; // Assuming you store user_id in session
  
// Get filter values
$filter_transaction = isset($_GET['transaction_id']) ? trim($_GET['transaction_id']) : '';
$filter_date = isset($_GET['request_date']) ? trim($_GET['request_date']) : '';

$sql = "
    SELECT 
        r.transaction_id, 
        r.request_date, 
        i.item_name, 
        ri.quantity
    FROM tbl_requests r
    JOIN tbl_request_items ri ON r.id = ri.request_id
    JOIN tbl_items i ON ri.item_id = i.id
    WHERE r.user_id = ?
";

// Add filters if provided
$params = [$user_id];

if (!empty($filter_transaction)) {
    $sql .= " AND r.transaction_id LIKE ?";
    $params[] = "%$filter_transaction%";
}

if (!empty($filter_date)) {
    $sql .= " AND DATE(r.request_date) = ?";
    $params[] = $filter_date;
}

$sql .= " ORDER BY r.request_date DESC";

$history_stmt = $conn->prepare($sql);
$history_stmt->execute($params);
$transactions = $history_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Request Item</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Request Item</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    
    <section class="content">
          <!-- Display Success or Error Messages -->
          <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create a Request</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="process_request.php">
                    <table class="table table-bordered" id="requestTable">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="requestBody">
                            <tr>
                                <td>
                                    <select name="items[]" class="form-control item-select" required>
                                        <option value="">Select Item</option>
                                        <?php foreach ($items as $item): ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['item_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="number" name="quantities[]" class="form-control" min="1" required></td>
                                <td><button type="button" class="btn btn-danger removeRow">Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-success mt-2" id="addRow">Add Row</button>
                    <button type="submit" class="btn btn-primary mt-2">Submit Request</button>
                </form>
            </div>
        </div>

        <!-- Transaction History Table -->
        <div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Your Past Requests</h3>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label for="transaction_id">Transaction ID:</label>
                    <input type="text" name="transaction_id" id="transaction_id" class="form-control" 
                        value="<?= htmlspecialchars($filter_transaction) ?>" placeholder="Enter Transaction ID">
                </div>
                <div class="col-md-4">
                    <label for="request_date">Request Date:</label>
                    <input type="date" name="request_date" id="request_date" class="form-control" 
                        value="<?= htmlspecialchars($filter_date) ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="request_item.php" class="btn btn-danger ml-2">Clear</a>
                </div>
            </div>
        </form>

        <!-- Transaction History Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Request Date</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?= htmlspecialchars($transaction['transaction_id']) ?></td>
                            <td><?= htmlspecialchars($transaction['request_date']) ?></td>
                            <td><?= htmlspecialchars($transaction['item_name']) ?></td>
                            <td><?= htmlspecialchars($transaction['quantity']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">No transaction history found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $("#addRow").click(function () {
        let newRow = `
            <tr>
                <td>
                    <select name="items[]" class="form-control item-select" required>
                        <option value="">Select Item</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= $item['item_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="number" name="quantities[]" class="form-control" min="1" required></td>
                <td><button type="button" class="btn btn-danger removeRow">Delete</button></td>
            </tr>`;
        $("#requestBody").append(newRow);
    });

    $(document).on("click", ".removeRow", function () {
        $(this).closest("tr").remove();
    });

    // Prevent duplicate item selection
    $(document).on("change", ".item-select", function () {
        let selectedItems = [];
        $(".item-select").each(function () {
            let val = $(this).val();
            if (val) selectedItems.push(val);
        });

        $(".item-select").each(function () {
            $(this).find("option").each(function () {
                if ($(this).val() !== "" && selectedItems.includes($(this).val()) && $(this).parent().val() !== $(this).val()) {
                    $(this).prop("disabled", true);
                } else {
                    $(this).prop("disabled", false);
                }
            });
        });
    });
});
</script>
</body>
</html>
