# 🌿 B2B Naturals Hub – A Farmer-Vendor Portal

B2B Naturals Hub is a web-based portal designed to connect **farmers** and **vendors** in a streamlined and transparent environment for agricultural product exchange. Developed as a **Database Management System (DBMS) project**, this platform facilitates product listing, ordering, delivery, and payment processing, ensuring fair trade and efficient business flow.

---

## 🎯 Project Objective

The aim is to simplify the B2B agricultural supply chain by allowing:
- Farmers to list fresh produce
- Vendors to browse, order, and manage product deliveries
- Seamless profile, inventory, and transaction management

---

## 🚀 Key Features

### 👨‍🌾 Farmer Module
- Register and log in as a farmer
- Add new products (with categories, quantity, price per unit, and availability date)
- View and edit listed products
- View orders placed by vendors for their products
- Update personal profile details

### 🛒 Vendor Module
- Register and log in as a vendor
- Browse available farm products
- Add products to cart and place orders
- View order history
- Update business profile

### 📦 Order & Delivery System
- Maintain order records and track order items
- Assign delivery personnel (concept included)
- Track order status and delivery status

### 💰 Payments and Reviews
- Store payment information
- Allow vendors to review farmers post-delivery (future scope)

---

## 🛠️ Tech Stack

| Layer           | Technology       |
|----------------|------------------|
| Frontend       | HTML, CSS, JavaScript |
| Backend        | PHP              |
| Database       | MySQL            |
| Server         | XAMPP (Apache + MySQL) |

---

## 📁 Folder Structure
dbms_project/
│
├── handlers/ # All PHP backend scripts (CRUD, login, profile, orders)
│ ├── add_product.php
│ ├── get_profile.php
│ ├── update_profile.php
│ └── ...more
│
├── css/ # Stylesheets
│ └── style.css
│
├── js/ # JavaScript files
│ └── vendor.js
│
├── profile.php # Shared profile page for farmers and vendors
├── farmer_dashboard.php # Farmer homepage after login
├── vendor_dashboard.php # Vendor homepage after login
├── signup_login_page.php # Combined signup/login page
├── home.php # Public landing page
├── db_connection.php # MySQL DB connection
├── README.md
└── dbms_project.sql # Database schema and sample data


---

## 🧪 How to Run Locally

1. **Clone this repository**
   ```bash
   git clone https://github.com/bilalfarooq056/b2b-naturals-hub.git
