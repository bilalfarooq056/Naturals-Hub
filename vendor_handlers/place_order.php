<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['person_id'])) {
    header("Location: SignUp_in_page.php");
    exit;
}

$person_id = $_SESSION['person_id'];
$orderQuery = "SELECT * FROM orders WHERE person_id = ? AND order_status = 'pending'";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("i", $person_id);
$stmt->execute();
$orderResult = $stmt->get_result();

$order = $orderResult->fetch_assoc();
$order_id = $order['order_id'] ?? null;

if (!$order_id) {
    header("Location: cart.php");
    exit;
}

$shipping_address = $_POST['shipping_address'] ?? '';
$order_status = 'placed';
$updateOrderQuery = "UPDATE orders SET shipping_address = ?, order_status = ? WHERE order_id = ?";
$stmt = $conn->prepare($updateOrderQuery);
if ($stmt) {
    $stmt->bind_param('ssi', $shipping_address, $order_status, $order_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Order placed successfully']);
    } else {
        echo json_encode(['error' => 'Failed to place order']);
    }
} else {
    echo json_encode(['error' => 'Failed to prepare update query']);
}
?>
