<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['person_id'])) {
    header("Location: ../SignUp_in_page.php");
    exit;
}

$person_id = $_SESSION['person_id'];

// Fetch pending order
$orderQuery = "SELECT * FROM orders WHERE person_id = ? AND order_status = 'pending'";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("i", $person_id);
$stmt->execute();
$orderResult = $stmt->get_result();

$order = $orderResult->fetch_assoc();
$order_id = $order['order_id'] ?? null;
$total_amount = $order['total_amount'] ?? 0;

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
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
            background-color: #f9f9f9;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #4CAF50;
        }
        table {
            width: 80%;
            margin: 1rem auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 16px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .total-row td {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .empty-message {
            text-align: center;
            margin-top: 3rem;
            font-size: 1.2rem;
            color: #777;
        }
        .checkout-btn {
            display: block;
            width: fit-content;
            margin: 2rem auto;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        .checkout-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Your Cart</h2>

<?php if (!$order_id || count($orderItems) === 0): ?>
    <p class="empty-message">Your cart is empty.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Price (₹)</th>
        </tr>
        <?php foreach ($orderItems as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><?= (int)$item['quantity'] ?></td>
                <td><?= htmlspecialchars($item['unit']) ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="total-row">
            <td colspan="3">Total</td>
            <td>₹<?= number_format($total_amount, 2) ?></td>
        </tr>
    </table>

    <form action="handlers/place_order.php" method="POST">
        <input type="hidden" name="order_id" value="<?= (int)$order_id ?>">
        <button type="submit" class="checkout-btn">Place Order</button>
    </form>
<?php endif; ?>

</body>
</html>
