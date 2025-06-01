<?php
$hostname = "http://localhost/dbms_project";

session_start();
include 'db_connect.php'; // Assumes $conn is a MySQLi connection

// Check if person_id exists in the session
if (!isset($_SESSION['person_id'])) {
  header("Location: http://localhost/dbms_project/SignUp_in_page.php");
    exit;
}

$person_id = $_SESSION['person_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Farmer Portal</title>
    <link rel="stylesheet" href="Front_end/CSS/farmers_dashboard.css" />
</head>
<body>
<header class="header">
    <div class="logo">
        <img src="Front_end/img/1.png" alt="NaturalsHub Logo" />
    </div>
    <nav class="nav">
        <a href="home_page.php">Home</a>
        <a href="#aboutPage" id="aboutLink">About</a>
    </nav>

    <!-- Profile Dropdown -->
    <div class="profile-wrapper">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Profile" class="profile-icon" onclick="toggleMenu()" />
        <div id="dropdown" class="dropdown-content">
            <a href="profile.php">Profile</a>
            <a href="#">Settings</a>
            <a href="handlers/logout.php" class="btn">Logout</a>
        </div>
    </div>
</header>

<div class="dashboard-container">
    <!-- Sidebar Menu -->
    <aside class="sidebar">
        <ul>
            <li><a href="#" class="menu-link active" data-target="addProductSection">Add Product</a></li>
            <li><a href="#" class="menu-link" data-target="viewProductsSection">Products List</a></li>
            <li><a href="#" class="menu-link" data-target="currentorderSection">Current Order </a></li>
            <li><a href="#" class="menu-link" data-target="orderHistorySection">Order History</a></li>
            <li><a href="#" class="menu-link" data-target="reviewHistorySection">Review History</a></li>
            <li><a href="#" class="menu-link" data-target="RetailPrice">Retail Price</a></li>
        </ul>
    </aside>
    <!-- Main Content -->
    <div class="main-content">
        <!-- 1. Add Product -->
        <div id="addProductSection" class="content-section active">
            <h2>Add New Product</h2>
            <button class="toggle-btn" onclick="toggleForm()">+ Add Product</button>

            <div class="form-container" id="formContainer">
                <form id="addProductForm" method="POST" enctype="multipart/form-data">  
                    <label for="productName">Product Name</label>
                    <input type="text" id="productName" name="productName" required />

                    <label for="productCategory">Category</label>
                    <select id="productCategory" name="category" required>
                        <option value="">--Select Category--</option>
                        <option value="fruit">Fruit</option>
                        <option value="vegetable">Vegetable</option>
                        <option value="grain">Grain</option>
                    </select>

                    <label for="productPrice">Price per Kilo (PKR)</label>
                    <input type="number" id="productPrice" name="price" min="1" required />

                    <label for="productQuantity">Availabe Quantity (KG)</label>
                    <input type="number" id="productQuantity" name="quantity" min="1" required />

                    <label for="productAvailibilityDate">Availability Date</label>
                    <input type="date" id="productAvailibilityDate" name="Avalibility_Date" required />

                    <label for="productUnit">Unit</label>
                    <input type="text" id="productUnit" name="unit" value="kg" readonly> <!-- Default value kg -->


                    <label for="productDescription">Description</label>
                    <textarea id="productDescription" name="description" required></textarea>

                    <button type="submit" class="btn">Add Product</button>
                </form>
            </div>
        </div>

        <!-- 2. View Products -->
        <div id="viewProductsSection" class="content-section">
            <h2>Your Products</h2>
            <div class="product-card-list" id="productList">
                <!-- Product cards will be dynamically added here -->
            </div>
        </div>


        <!-- Other Sections -->
        <!-- 3. Current Order  -->
        <div id="currentorderSection" class="content-section">
            <h2>Curent orders</h2>
            <p>No orders yet. (You can replace this with actual data.)</p>
        </div>
        <!-- 3. Order History -->
        <div id="orderHistorySection" class="content-section">
            <h2>Order History</h2>
            <p>No orders delivered yet. (You can replace this with actual data.)</p>
        </div>

        <!-- 4. Review History -->
        <div id="reviewHistorySection" class="content-section">
            <h2>Review History</h2>
            <p>No reviews yet. (You can replace this with actual reviews.)</p>
        </div>
    </div>
</div>

<footer>
    <div class="footer"></div>
</footer>

<!-- JavaScript -->
<script src="Front_end/Js/farmers_dash.js"></script>

<script>
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
    }

    window.addEventListener('DOMContentLoaded', () => {
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    });
</script>

</script>
</body>
</html>


