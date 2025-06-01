<?php
// updateProductHandler.php

include '../db_connection.php'; // Include database connection

session_start();

// Check if the user is logged in
if (!isset($_SESSION['person_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Get the product data from the POST request
$product_id = $_POST['product_id'];
$product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
$price = floatval($_POST['price']);
$quantity = intval($_POST['quantity']);
$product_description = mysqli_real_escape_string($conn, $_POST['product_description']);

$person_id = $_SESSION['person_id'];

// Update the product in the database
$query = "UPDATE product SET product_name = ?, price_per_unit = ?, quantity = ?, description = ? WHERE product_id = ? AND person_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sdiisi", $product_name, $price, $quantity, $product_description, $product_id, $person_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error updating product']);
}

$stmt->close();
$conn->close();
?>
