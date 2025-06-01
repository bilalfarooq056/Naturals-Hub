<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../db_connection.php'; 

if (!isset($_SESSION['person_id'])) {
    echo "error: not logged in";
    exit;
}

$person_id = $_SESSION['person_id'];

$sql = "SELECT p.*, c.category_name 
        FROM product p
        JOIN category c ON p.category_id = c.category_id
        WHERE p.person_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $person_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>No products found.</p>";
    exit;
}

// Loop through and display the products
while ($row = $result->fetch_assoc()) {
    $img_url = !empty($row['img_url']) ? $row['img_url'] : 'default.jpg'; // Check if image URL exists, else use default

    echo '
    <div class="product-item" data-product-id="' . htmlspecialchars($row['product_id']) . '">
        <div class="product-image">
            <img src="' . htmlspecialchars($img_url) . '" alt="' . htmlspecialchars($row['product_name']) . '">
            <div class="product-details">
                <h4>' . htmlspecialchars($row['product_name']) . '</h4>
                <p>Category: ' . htmlspecialchars($row['category_name']) . '</p>
                <p>Price: PKR ' . htmlspecialchars($row['price_per_unit']) . '/' . htmlspecialchars($row['unit']) . '</p>
                <p>Quantity: ' . htmlspecialchars($row['quantity']) . ' ' . htmlspecialchars($row['unit']) . '</p>
                <p>Available From: ' . htmlspecialchars($row['availability_date']) . '</p>
                <button class="edit-btn">Edit</button>
                <button class="remove-btn">Remove</button>
            </div>
        </div>
    </div>';
}
?>
