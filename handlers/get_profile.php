<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../db_connection.php';
header('Content-Type: application/json');

if (!isset($_SESSION['person_id']) || !isset($_SESSION['role'])) {
    echo json_encode(['success' => false, 'message' => 'Session expired or missing parameters']);
    exit();
}

$person_id = $_SESSION['person_id'];
$role = $_SESSION['role'];  // The role (e.g., farmer, vendor) from the session

// Query to fetch the person's basic details (full name, contact, email, address)
$query = "SELECT person_id, full_name, contact_number, email, address FROM person WHERE person_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $person_id);
$stmt->execute();
$person = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$person) {
    echo json_encode(['success' => false, 'message' => 'Person not found']);
    exit();
}

// Handle role-specific data (e.g., farmer or vendor)
$role_table = $role === 'farmer' ? 'farmer' : ($role === 'vendor' ? 'vendor' : null);

if (!$role_table) {
    echo json_encode(['success' => false, 'message' => 'Invalid role']);
    exit();
}

// Query to fetch additional role-specific details
$query = "SELECT * FROM $role_table WHERE person_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $person_id);
$stmt->execute();
$role_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Merge the basic person details with the role-specific data
$profile = array_merge($person, $role_data ?? []);

// Return the profile data in JSON format
echo json_encode(['success' => true, 'profile' => $profile]);
