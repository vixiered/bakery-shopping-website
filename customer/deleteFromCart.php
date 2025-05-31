<?php
session_start();

$conn = new mysqli("localhost", "root", "", "bakery");
if ($conn->connect_error) {
    die("DB error: " . $conn->connect_error);
}

if (isset($_POST['delete_custom']) && $_POST['delete_custom'] == '1') {
    // Custom cake deletion
    if (!isset($_POST['order_id'])) {
        die("Invalid custom cake request.");
    }

    $orderID = $_POST['order_id'];
    $stmt = $conn->prepare("UPDATE `order` SET style = NULL, size = NULL, occasion = NULL, flavour = NULL, diet = NULL, topping = NULL, price = NULL WHERE IDO = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();

} elseif (isset($_POST['order_id']) && isset($_POST['item_id'])) {
    // Normal item deletion
    $orderID = $_POST['order_id'];
    $itemID = $_POST['item_id'];

    $stmt = $conn->prepare("DELETE FROM ordereditem WHERE IDO = ? AND IDT = ?");
    $stmt->bind_param("ii", $orderID, $itemID);
    $stmt->execute();
} else {
    die("Invalid request.");
}

header("Location: cartdisplay.php");
exit();
