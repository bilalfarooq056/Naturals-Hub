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

$orderItems = [];
if ($order_id) {
    $itemQuery = "SELECT oi.*, p.product_name, p.unit FROM order_item oi
                  JOIN product p ON oi.product_id = p.product_id
                  WHERE oi.order_id = ?";
    $itemStmt = $conn->prepare($itemQuery);
    $itemStmt->bind_param("i", $order_id);
    $itemStmt->execute();
    $orderItems = $itemStmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="Front_end/CSS/checkout.css">
</head>
<body>
<header class="header">
</header>

<div class="checkout-container">
    <h2>Checkout</h2>

    <?php if ($order_id && count($orderItems) > 0): ?>
        <h3>Your Order:</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= $item['quantity'] ?> <?= htmlspecialchars($item['unit']) ?></td>
                        <td>₹<?= $item['price'] / $item['quantity'] ?></td>
                        <td>₹<?= $item['price'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-amount">
            <h3>Total: ₹<?= $order['total_amount'] ?></h3>
        </div>

        <form action="handlers/place_order.php" method="POST">
            <h3>Shipping Information:</h3>
            <textarea name="shipping_address" placeholder="Enter your address" required></textarea>
            
            <button type="submit">Place Order</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty. Please add items to the cart first.</p>
    <?php endif; ?>
</div>

</body>
</html>
