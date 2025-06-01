<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['person_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $order_item_id = $data['order_item_id'];

    $delete_query = "DELETE FROM order_item WHERE order_item_id = ?";
    $stmt = $conn->prepare($delete_query);
    if ($stmt) {
        $stmt->bind_param('i', $order_item_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Item removed from cart']);
        } else {
            echo json_encode(['error' => 'Failed to remove item']);
        }
    } else {
        echo json_encode(['error' => 'SQL prepare failed']);
    }
}
?>
