<?php
include "includes/session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $items = $_POST['items'];
    $quantities = $_POST['quantities'];
    $errors = [];

    // Step 1: Validate that items and quantities are provided
    if (empty($items) || empty($quantities)) {
        $_SESSION['error'] = "Please select at least one item with a valid quantity.";
        header("Location: request_item.php");
        exit();
    }

    // Get the user who requested
    $user_id = $_SESSION['SESS_USER_ID']; 

    // Step 2: Start a database transaction
    $conn->beginTransaction(); 

    // Step 3: Generate a unique transaction ID
    $date = date("Ymd");

    // Check if there are any previous requests today, to ensure unique transaction ID
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM tbl_requests WHERE request_date = CURDATE()");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $request_count = $row['count'] + 1;
    $transaction_id = "REQ-$date-" . str_pad($request_count, 5, "0", STR_PAD_LEFT);

    // Check if the transaction ID already exists, and increment if necessary
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM tbl_requests WHERE transaction_id = ?");
    $stmt->execute([$transaction_id]);
    $existing_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    if ($existing_count > 0) {
        // If a duplicate exists, increment the transaction count until unique
        $i = 1;
        do {
            $transaction_id = "REQ-$date-" . str_pad($request_count + $i, 5, "0", STR_PAD_LEFT);
            $stmt->execute([$transaction_id]);
            $existing_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            $i++;
        } while ($existing_count > 0);
    }

    // Step 4: Check stock before inserting the request
    foreach ($items as $key => $item_id) {
        $quantity = $quantities[$key];

        // Get item name and check warehouse inventory
        $stmt = $conn->prepare("SELECT i.item_name, w.item_remaining_stock, w.isDeleted 
                                FROM tbl_warehouse_inventory w 
                                JOIN tbl_items i ON w.item_id = i.id 
                                WHERE w.item_id = ?");
        $stmt->execute([$item_id]);
        $inventory = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$inventory) {
            $errors[] = "Item ID $item_id not found in inventory.";
        } elseif ($inventory['isDeleted'] == 1) {
            $errors[] = "Item '{$inventory['item_name']}' is no longer available.";
        } elseif ($inventory['item_remaining_stock'] < $quantity) {
            $errors[] = "Not enough stock for item <b>{$inventory['item_name']}</b>. Available: {$inventory['item_remaining_stock']}, Requested: $quantity.";
        }
    }

    // If there are errors, roll back and show error message
    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        $conn->rollBack();  // Rollback the transaction to avoid partial entries
        header("Location: request_item.php");
        exit();
    }

    // Step 5: Insert into tbl_requests
    $stmt = $conn->prepare("INSERT INTO tbl_requests (transaction_id, user_id, request_date) VALUES (?, ?, NOW())");
    $stmt->execute([$transaction_id, $user_id]);
    $request_id = $conn->lastInsertId(); // Get the ID of the newly inserted request

    // Step 6: Insert request items and update stock
    $stmt = $conn->prepare("INSERT INTO tbl_request_items (request_id, item_id, quantity) VALUES (?, ?, ?)");

    foreach ($items as $key => $item_id) {
        $quantity = $quantities[$key];

        // Insert each item into the request_items table
        $stmt->execute([$request_id, $item_id, $quantity]);

        // Deduct stock
        $updateStock = $conn->prepare("UPDATE tbl_warehouse_inventory SET item_remaining_stock = item_remaining_stock - ? WHERE item_id = ?");
        $updateStock->execute([$quantity, $item_id]);
    }

    // Step 7: Commit the transaction
    $conn->commit();
    $_SESSION['success'] = "Request submitted successfully! Transaction ID: $transaction_id";
    header("Location: request_item.php");
    exit();
}
?>
