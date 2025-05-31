<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if (!$orderId || !$action) {
        die("Missing data.");
    }

    $conn = new mysqli("localhost", "root", "", "bakery");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($action === 'confirm') {
        $stmt = $conn->prepare("UPDATE `order` SET state = 'confirmed' WHERE IDO = ?");
    } elseif ($action === 'cancel') {
        $stmt = $conn->prepare("UPDATE `order` SET state = 'cancelled' WHERE IDO = ?");
    } else {
        die("Invalid action.");
    }

    $stmt->bind_param("i", $orderId);
    if ($stmt->execute()) {
        header("Location: manageorders.php"); // redirect back after success
        exit();
    } else {
        echo "Error updating order.";
    }
} else {
    echo "Invalid request method.";
}
?>
