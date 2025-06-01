<?php
session_start();
include '../db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['person_id'])) {
    header("Location: SignUp_in_page.php");
    exit;
}

$person_id = $_SESSION['person_id'];

// Get the product_id from the query string
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

if (!$product_id) {
    echo json_encode([]);
    exit;
}

// Fetch reviews for the specific product purchased by the current user
$query = "SELECT r.rating, r.comment, r.review_date, f.farm_name, v.business_name
          FROM review r
          LEFT JOIN farmer f ON r.farmer_id = f.person_id
          LEFT JOIN vendor v ON r.vendor_id = v.person_id
          INNER JOIN order_item oi ON oi.product_id = r.product_id
          INNER JOIN orders o ON o.order_id = oi.order_id
          WHERE r.product_id = ? AND o.person_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $product_id, $person_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all reviews into an array
$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

// Return reviews as JSON
echo json_encode($reviews);
?>
