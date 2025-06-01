<?php
session_start();
require '../db_connection.php';

if (!isset($_SESSION['person_id'])) {
    die("Unauthorized access.");
}

$person_id = $_SESSION['person_id'];
$full_name = isset($_POST['full_name']) ? $_POST['full_name'] : null;
$contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : null;
$address = isset($_POST['address']) ? $_POST['address'] : null;
$current_password = isset($_POST['current_password']) ? $_POST['current_password'] : null;
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : null;
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;

// Update fields if they are provided
if ($full_name) {
    $stmt = $conn->prepare("UPDATE person SET full_name = ? WHERE person_id = ?");
    $stmt->bind_param("si", $full_name, $person_id);
    $stmt->execute();
    $stmt->close();
}

if ($contact_number) {
    $stmt = $conn->prepare("UPDATE person SET contact_number = ? WHERE person_id = ?");
    $stmt->bind_param("si", $contact_number, $person_id);
    $stmt->execute();
    $stmt->close();
}

if ($address) {
    $stmt = $conn->prepare("UPDATE person SET address = ? WHERE person_id = ?");
    $stmt->bind_param("si", $address, $person_id);
    $stmt->execute();
    $stmt->close();
}

// Handle password change if current password and new password are provided
if ($current_password && $new_password && $confirm_password) {
    if ($new_password !== $confirm_password) {
        die("New passwords do not match.");
    }

    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM person WHERE person_id = ?");
    $stmt->bind_param("i", $person_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if (!$data || !password_verify($current_password, $data['password'])) {
        die("Current password is incorrect.");
    }

    // Hash new password
    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in the database
    $stmt = $conn->prepare("UPDATE person SET password = ? WHERE person_id = ?");
    $stmt->bind_param("si", $hashed_new_password, $person_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect after successful update
header("Location: ../profile.php?update=success");
exit();
?>
