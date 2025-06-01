<?php
session_start();
include '../db_connection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

if (!isset($_SESSION['person_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$person_id = $_SESSION['person_id'];

// Debug incoming POST
if (!isset($_POST['product_id'], $_POST['quantity'])) {
    echo json_encode(['error' => 'Missing product_id or quantity']);
    exit;
}

$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];

if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode(['error' => 'Invalid product or quantity']);
    exit;
}

// Check for pending order
$query = "SELECT * FROM orders WHERE person_id = ? AND order_status = 'pending'";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $person_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $order_date = date('Y-m-d');
    $order_status = 'pending';
    $total_amount = 0;

    $insert_order_query = "INSERT INTO orders (person_id, order_date, total_amount, order_status) VALUES (?, ?, ?, ?)";
    $insert_order_stmt = $conn->prepare($insert_order_query);
    $insert_order_stmt->bind_param('isds', $person_id, $order_date, $total_amount, $order_status);
    if ($insert_order_stmt->execute()) {
        $order_id = $insert_order_stmt->insert_id;
    } else {
        echo json_encode(['error' => 'Failed to create order.']);
        exit;
    }
} else {
    $order = $result->fetch_assoc();
    $order_id = $order['order_id'];
}

// Check product availability
$product_query = "SELECT * FROM product WHERE product_id = ?";
$product_stmt = $conn->prepare($product_query);
$product_stmt->bind_param('i', $product_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if ($product_result->num_rows === 0) {
    echo json_encode(['error' => 'Product not found']);
    exit;
}

$product = $product_result->fetch_assoc();
$price_per_unit = $product['price_per_unit'];
$available_quantity = (int)$product['quantity'];

if ($quantity > $available_quantity) {
    echo json_encode(['error' => 'Not enough stock available.']);
    exit;
}

$price = $price_per_unit * $quantity;

// Check if product already in order
$check_item_query = "SELECT quantity FROM order_item WHERE order_id = ? AND product_id = ?";
$check_item_stmt = $conn->prepare($check_item_query);
$check_item_stmt->bind_param('ii', $order_id, $product_id);
$check_item_stmt->execute();
$check_result = $check_item_stmt->get_result();

if ($check_result->num_rows > 0) {
    $existing = $check_result->fetch_assoc();
    $new_quantity = $existing['quantity'] + $quantity;
    $new_price = $price_per_unit * $new_quantity;

    $update_item_query = "UPDATE order_item SET quantity = ?, price = ? WHERE order_id = ? AND product_id = ?";
    $update_item_stmt = $conn->prepare($update_item_query);
    $update_item_stmt->bind_param('idii', $new_quantity, $new_price, $order_id, $product_id);
    $update_item_stmt->execute();

    $price_difference = $price_per_unit * $quantity;
} else {
    $insert_item_query = "INSERT INTO order_item (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $insert_item_stmt = $conn->prepare($insert_item_query);
    $insert_item_stmt->bind_param('iiid', $order_id, $product_id, $quantity, $price);
    $insert_item_stmt->execute();

    $price_difference = $price;
}

// Update total order price
$update_order_query = "UPDATE orders SET total_amount = total_amount + ? WHERE order_id = ?";
$update_order_stmt = $conn->prepare($update_order_query);
$update_order_stmt->bind_param('di', $price_difference, $order_id);
$update_order_stmt->execute();

// Update product quantity
$update_product_query = "UPDATE product SET quantity = quantity - ? WHERE product_id = ?";
$update_product_stmt = $conn->prepare($update_product_query);
$update_product_stmt->bind_param('ii', $quantity, $product_id);
$update_product_stmt->execute();

echo json_encode(['success' => 'Product added/updated in cart successfully!']);
?>
