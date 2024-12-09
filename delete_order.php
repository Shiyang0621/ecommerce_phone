<?php
require 'db_connection.php';

// Check if the order ID is set
if (isset($_POST['id'])) {
    $order_id = $_POST['id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete associated order items
        $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }
        $stmt->bind_param("i", $order_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }
        $stmt->close();

        // Delete the order
        $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }
        $stmt->bind_param("i", $order_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }
        $stmt->close();

        // Commit the transaction
        $conn->commit();
    } catch (Exception $e) {
        // Rollback the transaction if something failed
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }

    // Redirect to the dashboard
    header('Location: admin_dashboard.php');
    exit();
} else {
    die("Order ID not specified.");
}
?>
