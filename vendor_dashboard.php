<?php
$hostname = "http://localhost/dbms_project";
session_start();
include 'db_connect.php';

if (!isset($_SESSION['person_id'])) {
    header("Location: $hostname/SignUp_in_page.php");
    exit;
}

$person_id = $_SESSION['person_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" href="Front_end/CSS/vendor_dashboard.css">
</head>
<body>
<header class="header">
    <div class="logo">
        <img src="Front_end/img/1.png" alt="NaturalsHub Logo" />
    </div>
    <nav class="nav">
        <a href="home_page.php" id="homeLink">Home</a>
        <a href="#aboutPage" id="aboutLink">About</a>
    </nav>

    <div class="profile-wrapper">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Profile" class="profile-icon" onclick="toggleMenu()" />
        <div id="dropdown" class="dropdown-content">
            <a href="profile.php">Profile</a>
            <a href="vendor_handlers/cart.php">View Cart / Checkout</a> 
            <a href="handlers/logout.php" class="btn">Logout</a>
        </div>
    </div>
</header>

<div class="content" id="vendorContent">
    <h3>Products to Buy</h3>
    <div class="product-list" id="productList">
        <!-- JS will inject products like this:
        <div class="product-card">
            <h4>Tomatoes</h4>
            <p>Price: ₹30/kg</p>
            <p>Available: 50 kg</p>
            <input type="number" id="qty-1" min="1" max="50" placeholder="Qty">
            <button onclick="addToCart(1, 'Tomatoes', 50)">Add to Cart</button>
        </div>
        -->
    </div>

    <h3>Customer Reviews</h3>
    <div class="review-list" id="reviewList">
        <!-- JS will inject reviews -->
    </div>
</div>

<script src="Front_end/Js/vendor_dash.js"></script>

<script>
function toggleMenu() {
    document.getElementById("dropdown").classList.toggle("show");
}
</script>
</body>
</html>
