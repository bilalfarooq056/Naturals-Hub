<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>NaturalsHub</title>
  <link rel="stylesheet" href="Front_end/CSS/home.css">
</head>
<body>


<!-- Header Section -->
<div class="header">
    <img src="Front_end/img/logo_1-removebg-preview.png" alt="NaturalsHub Logo" class="logo" />
    
    <nav class="nav">
      <a href="#" id="homeLink">Home</a>
      <a href="#aboutPage" id="aboutLink">About</a>
      <a href="#" id="farmerLink">Farmer Portal</a>
      <a href="#" id="vendorLink">Vendor Portal</a>
    </nav>
    
    <div class="login-container">
      <button id="loginButton" class="btn">Login</button>
    </div>
  </div>
  

<!-- Home Content Section -->
<div class="content" id="homeContent">
  <h2>Welcome to NaturalsHub!</h2>
  <p>Connecting farmers and vendors seamlessly.</p>

  <h3>Explore Our Products</h3>
  <div class="products">
    <div class="product-item">
      <div class="product-image">
        <img src="path-to-image/apple.jpg" alt="Fresh Apples">
        <div class="product-details">
          <p>Price: $3/kg</p>
          <p>Description: Fresh apples directly from the farm.</p>
        </div>
      </div>
      <h4>Fresh Apples</h4>
      <button class="btn">Add to Cart</button>
    </div>

    <div class="product-item">
      <div class="product-image">
        <img src="path-to-image/tomato.jpg" alt="Organic Tomatoes">
        <div class="product-details">
          <p>Price: $2/kg</p>
          <p>Description: Grown organically for the best quality.</p>
        </div>
      </div>
      <h4>Organic Tomatoes</h4>
      <button class="btn">Add to Cart</button>
    </div>

    <div class="product-item">
      <div class="product-image">
        <img src="path-to-image/orange.jpg" alt="Fresh Oranges">
        <div class="product-details">
          <p>Price: $4/kg</p>
          <p>Description: Juicy and sweet oranges, freshly picked.</p>
        </div>
      </div>
      <h4>Fresh Oranges</h4>
      <button class="btn">Add to Cart</button>
    </div>
  </div>

  <p>To purchase, please <a href="/Front_end/HTML/SignUp_in_page.html" id="loginLink">log in</a>.</p>
</div>


  <!-- About Page Section (Visible only when user clicks "About" link) -->
  <div class="content" id="aboutPage" style="display: none;">
    <h2>About NaturalsHub</h2>
    <p>NaturalsHub is a platform connecting farmers and vendors. We provide fresh produce directly from farmers to the markets, ensuring quality and freshness for all buyers.</p>

    <h3>Leave a Message</h3>
    <form id="messageForm">
      <label for="message">Your Message:</label><br>
      <textarea id="message" name="message" rows="4" cols="50"></textarea><br>
      <button type="submit" class="btn">Submit</button>
    </form>
  </div>

  <!-- JavaScript to show/hide sections based on navigation -->
  <script>
    document.getElementById('homeLink').addEventListener('click', function() {
      document.getElementById('homeContent').style.display = 'block';
      document.getElementById('aboutPage').style.display = 'none';
    });

    document.getElementById('aboutLink').addEventListener('click', function() {
      document.getElementById('aboutPage').style.display = 'block';
      document.getElementById('homeContent').style.display = 'none';
    });
  </script>

  <!-- JavaScript Link -->
  <script src="/Front_end/Js/script.js"></script>
</body>
</html>
