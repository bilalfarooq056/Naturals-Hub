<?php
session_start(); // Ensure session starts at the top
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>NaturalsHub</title>
  <link rel="stylesheet" href="Front_end/CSS/SignUp_in_page.css" />

  <!-- Display error message -->
  <?php if (isset($_SESSION['email_error'])): ?>
      <p style="color: red;"><?php echo $_SESSION['email_error']; unset($_SESSION['email_error']); ?></p>
  <?php endif; ?>
</head>
<body>
  <!-- Background Video -->
  <video autoplay loop muted id="bgVideo">
    <source src="Front_end/img/farm.mp4" />
  </video>

  <div class="role-selection-box">
    <div id="role-text">
      <h2>Please select your role:</h2>
    </div>

    <div id="role-buttons" class="role-buttons">
      <button onclick="selectRole('farmer')">Farmer</button>
      <button onclick="selectRole('vendor')">Vendor</button>
    </div>

    <!-- Action Selection -->
    <div id="action-selection" class="form-container" style="display: none;">
      <h3>Do you want to:</h3>
      <div class="form-buttons">
        <button onclick="showForm('signup')">Sign Up</button>
        <button onclick="showForm('login')">Log In</button>
      </div>
      <button type="button" class="back-button" onclick="goBackToRole()">← Back</button>
    </div>

    <!-- Login Form -->
    <div id="login-form" class="form-container" style="display: none;">
      <h3 id="login-title">Login</h3>
      <form action="Sign_up_page_handling.php" method="post">

        <input type="hidden" name="action" value="login">
        <input type="hidden" id="login-role" name="role" /> 
        
        <label>Email:</label>
        <input type="email" name="email" required id="email">
        <?php if (isset($_SESSION['email_error'])): ?>
        <span style="color: red;" id="email-error"><?php echo $_SESSION['email_error']; unset($_SESSION['email_error']); ?></span>
        <?php endif; ?>
        <div id="email-error-container" class="error-container" style="color: red; display: none;"></div>

        <label>Password:</label>
        <input type="password" name="password" required id="password">
        <?php if (isset($_SESSION['password_error'])): ?>
        <span style="color: red;" id="password-error"><?php echo $_SESSION['password_error']; unset($_SESSION['password_error']); ?></span>
        <?php endif; ?>
        <div id="password-error-container" class="error-container" style="color: red; display: none;"></div>



        <button type="submit">Login</button>
        <button type="button" class="back-button" onclick="goBackToAction()">← Back</button>
      </form>
    </div>

    <!-- Farmer Sign Up Form -->
    <div id="farmer-form" class="form-container" style="display: none;">
      <h3>Farmer Registration</h3>
      <form action="Sign_up_page_handling.php" method="post">
        <input type="hidden" name="action" value="signup" /> 
        <input type="hidden" name="role" value="farmer" /> 

        <label>Full Name:</label>
        <input type="text" name="name" required />
        <label>Farm Name:</label>
        <input type="text" name="farm_Name" required />

        <label>Farm Size:</label>
        <input type="text" name="farm_Size" required />

        <label>Contact Number:</label>
        <input type="text" name="Contact" required />
        
        <label>Address</label>
        <input type="text" name="address" required>

        <label>Registration Date:</label>
        <input type="date" name="Registration_date" required />

        <label>Email:</label>
        <input type="email" name="email" required />

        <label>Password:</label>
        <input type="password" name="password" required />
        <span class="password-toggle" id="togglePassword">Show</span> <!-- Toggle span -->
        <span class="error-message"><?php echo $passwordErr; ?></span>

        <label>Confirm Password:</label>
        <input type="password" name="confirmPassword" required />
        <button type="submit">Register as Farmer</button>
        <button type="button" class="back-button" onclick="goBack()">← Back</button>
      </form>
    </div>

 <!-- Vendor Sign Up Form -->
<div id="vendor-form" class="form-container" style="display: none;">
  <h3>Vendor Registration</h3>
  <form action="Sign_up_page_handling.php" method="post">
    <input type="hidden" name="action" value="signup" />
    <input type="hidden" name="role" value="vendor" />

    <label>Full Name:</label>
    <input type="text" name="name" required />

    <label>Business Name:</label>
    <input type="text" name="business" required />

    <label>Business Type:</label>
    <input type="text" name="business_type" required />

    <label>Contact Number:</label>
    <input type="text" name="Contact" required />

    <label>Registration Date:</label>
    <input type="date" name="Registration_date" required />

    <label>Address</label>
    <input type="text" name="address" required>

    <label>Email:</label>
    <input type="email" name="email" required />

    <label>Password:</label>
    <input type="password" name="password" required />

    <label>Confirm Password:</label>
    <input type="password" name="confirmPassword" required />

    <button type="submit">Register as Vendor</button>
    <button type="button" class="back-button" onclick="goBack()">← Back</button>
  </form>
</div>

  </div>
  <script defer>
  let selectedRole = null;

  function selectRole(role) {
    selectedRole = role;
    document.getElementById('role-buttons').style.display = 'none';
    document.getElementById('role-text').style.display = 'none';
    document.getElementById('action-selection').style.display = 'block';
  }

  function showForm(type) {
    document.getElementById('action-selection').style.display = 'none';
    if (type === 'signup') {
      if (selectedRole === 'farmer') {
        document.getElementById('farmer-form').style.display = 'block';
      } else {
        document.getElementById('vendor-form').style.display = 'block';
      }
    } else if (type === 'login') {
      document.getElementById('login-form').style.display = 'block';
      document.getElementById('login-role').value = selectedRole;
    }
  }

  function goBack() {
    document.getElementById('farmer-form').style.display = 'none';
    document.getElementById('vendor-form').style.display = 'none';
    document.getElementById('action-selection').style.display = 'block';
  }

  function goBackToRole() {
    selectedRole = null;
    document.getElementById('action-selection').style.display = 'none';
    document.getElementById('role-buttons').style.display = 'flex';
    document.getElementById('role-text').style.display = 'block';
  }

  function goBackToAction() {
    document.getElementById('login-form').style.display = 'none';
    document.getElementById('action-selection').style.display = 'block';
  }

  // Form validation and AJAX call
  function validateForm(event) {
    event.preventDefault();  // Prevent form submission

    // Get form data
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const action = document.querySelector('input[name="action"]:checked').value;

    let errors = [];

    // Basic validation
    if (!email) errors.push('Email is required');
    if (!password) errors.push('Password is required');

    // If there are errors, display them and stop submission
    if (errors.length > 0) {
      document.getElementById('error-message').innerHTML = errors.join('<br>');
      document.getElementById('error-message').style.display = 'block';
      return;
    }

    // If no errors, send data via AJAX to PHP for database checks
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    formData.append('action', action);
    formData.append('role', selectedRole);

    fetch('login_signup_process.php', {
      method: 'POST',
      body: formData,
    })
      .then(response => response.text())
      .then(data => {
        // Show server response (e.g., error or success message)
        document.getElementById('error-message').innerHTML = data;
        document.getElementById('error-message').style.display = 'block';
      })
      .catch(error => {
        document.getElementById('error-message').innerHTML = 'An error occurred';
        document.getElementById('error-message').style.display = 'block';
      });
  }
// Form validation function (can be used for AJAX validation if needed)
function validateForm(event) {
  event.preventDefault(); // Prevent form submission
  
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirmPassword').value;

  let errors = [];

  // Validate email
  if (!email || !validateEmail(email)) {
    errors.push("Invalid email format.");
  }

  // Validate password
  if (!password || password.length < 6) {
    errors.push("Password must be at least 6 characters.");
  }

  // Validate password confirmation
  if (password !== confirmPassword) {
    errors.push("Passwords do not match.");
  }

  // Display errors
  if (errors.length > 0) {
    let errorContainer = document.getElementById('error-container');
    errorContainer.innerHTML = errors.join('<br>');
    errorContainer.style.display = 'block';
  } else {
    // Submit the form if no errors
    document.getElementById('signup-form').submit();
  }
}

// Email validation helper function
function validateEmail(email) {
  const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  return regex.test(email);
}


</script>

<script>
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');

        // Optional: Save preference in localStorage
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDark);
    }

    // Load preference
    window.addEventListener('DOMContentLoaded', () => {
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
});
</script>

</body>
</html>
