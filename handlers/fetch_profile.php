<?php
session_start();
header('Content-Type: application/json');

if (!isset($_POST['person_id'], $_POST['role'])) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit();
}

require_once '../db_connection.php';  // make sure it connects to dbms_project

$person_id = intval($_POST['person_id']);
$role = $_POST['role'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=dbms_project", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get base person info
    $stmt = $pdo->prepare("SELECT name, phone, email FROM person WHERE person_id = ?");
    $stmt->execute([$person_id]);
    $person = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$person) {
        echo json_encode(['success' => false, 'error' => 'Person not found']);
        exit();
    }

    // Get role-specific info
    $role_table = [
        'farmer' => 'farmer',
        'vendor' => 'vendor',
        'delivery_guy' => 'delivery_guy'
    ];

    if (!isset($role_table[$role])) {
        echo json_encode(['success' => false, 'error' => 'Invalid role']);
        exit();
    }

    $role_stmt = $pdo->prepare("SELECT * FROM {$role_table[$role]} WHERE person_id = ?");
    $role_stmt->execute([$person_id]);
    $role_info = $role_stmt->fetch(PDO::FETCH_ASSOC);

    // Merge and return
    echo json_encode(['success' => true, 'user' => array_merge($person, $role_info)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
