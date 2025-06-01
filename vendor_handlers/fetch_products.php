<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db_connection.php';  // Correct path to db_connect.php

// Query to get the products from all farmers/vendors
$query = "
    SELECT p.product_name, p.price_per_unit, p.quantity, p.unit, f.farm_name, v.business_name
    FROM product p
    LEFT JOIN farmer f ON p.person_id = f.person_id
    LEFT JOIN vendor v ON p.person_id = v.person_id
    WHERE p.availability_date <= NOW()
    ORDER BY p.product_name
";

$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    // Return data as JSON
    echo json_encode($products);
} else {
    // If the query failed, return an error
    echo json_encode(["error" => "Failed to retrieve products."]);
}
?>
