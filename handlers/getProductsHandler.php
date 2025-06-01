<?php
session_start();
include '../db_connection.php'; // Assumes the connection to the database is correct

// Ensure user is logged in
if (!isset($_SESSION['person_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

$person_id = $_SESSION['person_id'];

// Fetch products added by the logged-in farmer
$stmt = $conn->prepare("SELECT p.product_id, p.product_name, p.price_per_unit, p.availability_date, p.img_url, c.category_name
                        FROM product p
                        JOIN category c ON p.category_id = c.category_id
                        WHERE p.person_id = ?");
$stmt->bind_param("i", $person_id);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode(['status' => 'success', 'products' => $products]);

$stmt->close();
?>
