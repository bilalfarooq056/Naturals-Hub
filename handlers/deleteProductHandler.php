<?php
// deleteProductHandler.php

include '../db_connection.php';
session_start();

if (!isset($_SESSION['person_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to delete a product.']);
    exit;
}

$person_id = $_SESSION['person_id'];
$product_id = $_POST['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID is required.']);
    exit;
}

// Correct table name and column
$stmt = $conn->prepare("DELETE FROM product WHERE product_id = ? AND person_id = ?");
$stmt->bind_param("ii", $product_id, $person_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error deleting product from the database.']);
}

$stmt->close();
$conn->close();
?>
