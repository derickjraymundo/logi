<?php
include "includes/session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $items = $_POST['items'];
    $quantities = $_POST['quantities'];
    $errors = [];

    if (empty($items) || empty($quantities)) {
        $_SESSION['error'] = "Please select at least one item with a valid quantity.";
        header("Location: request_item.php");
        exit();
    }

    $conn->beginTransaction(); // Start transaction

    foreach ($items as $key => $item_id) {
        $quantity = $quantities[$key];

        // Fetch item name and warehouse inventory details
        $stmt = $conn->prepare("SELECT i.item_name, w.item_remaining_stock, w.isDeleted 
                                FROM tbl_warehouse_inventory w
                                JOIN tbl_items i ON w.item_id = i.id
                                WHERE w.item_id = ?");
        $stmt->execute([$item_id]);
        $inventory = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$inventory) {
            $errors[] = "Item not found.";
        } elseif ($inventory['isDeleted'] == 1) {
            $errors[] = "Item <b>{$inventory['item_name']}</b> is no longer available.";
        } elseif ($inventory['item_remaining_stock'] < $quantity) {
            $errors[] = "Not enough stock for item <b>{$inventory['item_name']}</b>. Available: {$inventory['item_remaining_stock']}, Requested: $quantity.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        $conn->rollBack();
        header("Location: request_item.php");
        exit();
    }

    // Save the request if all checks pass
    $stmt = $conn->prepare("INSERT INTO tbl_requests (item_id, quantity) VALUES (?, ?)");
    foreach ($items as $key => $item_id) {
        $quantity = $quantities[$key];
        $stmt->execute([$item_id, $quantity]);

        // Deduct stock
        $updateStock = $conn->prepare("UPDATE tbl_warehouse_inventory SET item_remaining_stock = item_remaining_stock - ? WHERE item_id = ?");
        $updateStock->execute([$quantity, $item_id]);
    }

    $conn->commit();
    $_SESSION['success'] = "Request submitted successfully!";
    header("Location: request_item.php");
    exit();
}
?>
