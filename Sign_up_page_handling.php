<?php
ob_start(); // Prevent header issues
include 'db_connection.php';
session_start();

// Show PHP errors (for development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize error messages and variables
$emailErr = $passwordErr = $generalErr = $nameErr = $contactErr = $confirmErr = "";
$email = $password = $role = $name = $contact = $confirmPassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    $role = strtolower(trim($_POST['role'] ?? '')); // Normalize role

    // === LOGIN ===
    if ($action === "login") {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($email)) $emailErr = "Email is required";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $emailErr = "Invalid email format";

        if (empty($password)) $passwordErr = "Password is required";

        if (empty($emailErr) && empty($passwordErr)) {
            $sql = "SELECT person_id, full_name, password FROM person WHERE email = ? AND person_type = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $role);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['person_id'] = $user['person_id'];
                    $_SESSION['name'] = $user['full_name'];
                    $_SESSION['role'] = $role;

                    // Redirect based on role
                    header("Location: " . ($role === 'farmer' ? 'farmer_dashboard.php' : 'vendor_dashboard.php'));
                    exit;
                } else {
                    $passwordErr = "Incorrect password!";
                }
            } else {
                $emailErr = "No user found with this email and role.";
            }
        }
        if (!empty($emailErr)) {
            $_SESSION['email_error'] = $emailErr;
        }
        if (!empty($passwordErr)) {
            $_SESSION['password_error'] = $passwordErr;
        }
        // header("Location: SignUp_in_page.php"); // Reloads login page with errors
        exit;
        
    }

    // === SIGNUP ===
    elseif ($action === "signup") {
        $name = trim($_POST['name'] ?? '');
        $contact = trim($_POST['Contact'] ?? '');
        $registration_date = $_POST['Registration_date'] ?? date('Y-m-d');
        $address = trim($_POST['address'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        // Basic validation
        if (empty($name)) $nameErr = "Name is required";
        if (empty($contact)) $contactErr = "Contact number is required";
        if (empty($email)) $emailErr = "Email is required";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $emailErr = "Invalid email format";
        if (empty($password)) $passwordErr = "Password is required";
        if ($password !== $confirmPassword) $confirmErr = "Passwords do not match";

        // Check existing email/contact
        if (empty($emailErr) && empty($contactErr)) {
            $sql_check = "SELECT * FROM person WHERE email = ? OR contact_number = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("ss", $email, $contact);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $emailErr = "Email or contact already registered!";
            }
        }

        // Register
        if (empty($emailErr) && empty($passwordErr) && empty($confirmErr) && empty($nameErr) && empty($contactErr)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql_person = "INSERT INTO person (person_type, full_name, contact_number, email, password, registration_date, address) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_person = $conn->prepare($sql_person);
            $stmt_person->bind_param("sssssss", $role, $name, $contact, $email, $hashedPassword, $registration_date, $address);

            if ($stmt_person->execute()) {
                $person_id = $stmt_person->insert_id;

                // Insert into role-specific table
                if ($role == 'farmer') {
                    $farm_name = $_POST['farm_Name'] ?? '';
                    $farm_size = $_POST['farm_Size'] ?? '';
                    $sql_farmer = "INSERT INTO farmer (person_id, farm_name, farm_size) VALUES (?, ?, ?)";
                    $stmt_farmer = $conn->prepare($sql_farmer);
                    $stmt_farmer->bind_param("iss", $person_id, $farm_name, $farm_size);
                    $stmt_farmer->execute();
                    header("Location: farmer_dashboard.php");
                } elseif ($role == 'vendor') {
                    $business = $_POST['business'] ?? '';
                    $business_type = $_POST['business_type'] ?? '';
                    $sql_vendor = "INSERT INTO vendor (person_id, business_name, business_type) VALUES (?, ?, ?)";
                    $stmt_vendor = $conn->prepare($sql_vendor);
                    $stmt_vendor->bind_param("iss", $person_id, $business, $business_type);
                    $stmt_vendor->execute();
                    header("Location: vendor_dashboard.php");
                }
                exit;
            } else {
                $generalErr = "Error during registration.";
            }

            $stmt_person->close();
        }
    }

    $conn->close();
}
?>
