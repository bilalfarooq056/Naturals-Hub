<?php
// get_order_history_vendor.php

include '../db_connection.php'; // Include your database connection

session_start();

if (!isset($_SESSION['person_id'])) {
    echo json_encode(['error' => 'You must be logged in to view order history.']);
    exit;
}

$person_id = $_SESSION['person_id'];

// Fetch orders for this vendor
$stmt = $conn->prepare("SELECT orders.order_id, orders.order_date, order_items.product_name, order_items.quantity, order_items.price, orders.farmer_id FROM orders JOIN order_items ON orders.order_id = order_items.order_id WHERE orders.vendor_id = ?");
$stmt->bind_param("i", $person_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    echo json_encode($orders);
} else {
    echo json_encode(['error' => 'No orders found for this vendor.']);
}

$stmt->close();
$conn->close();
?>
