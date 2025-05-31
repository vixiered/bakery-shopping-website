<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: loginhtml.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "bakery");
if ($conn->connect_error) {
    die("DB error: " . $conn->connect_error);
}

$userIDP = $_SESSION['user_id'];

// Get customer IDC
$stmt = $conn->prepare("SELECT IDC FROM customer WHERE IDP = ?");
$stmt->bind_param("i", $userIDP);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Customer not found.");
}
$IDC = $result->fetch_assoc()['IDC'];

// Get latest pending order
$stmt = $conn->prepare("SELECT IDO FROM `order` WHERE IDC = ? AND status = 'pending' ORDER BY IDO DESC LIMIT 1");
$stmt->bind_param("i", $IDC);
$stmt->execute();
$orderResult = $stmt->get_result();
if ($orderResult->num_rows === 0) {
    die("No pending order found.");
}
$IDO = $orderResult->fetch_assoc()['IDO'];

// Check for regular items
$stmt = $conn->prepare("SELECT COUNT(*) as itemCount FROM ordereditem WHERE IDO = ?");
$stmt->bind_param("i", $IDO);
$stmt->execute();
$itemCheck = $stmt->get_result()->fetch_assoc();
$itemCount = $itemCheck['itemCount'];

// Check if it's a custom cake order (if any custom values are not NULL)
$stmt = $conn->prepare("SELECT style, size, occasion, flavour, topping, diet FROM `order` WHERE IDO = ?");
$stmt->bind_param("i", $IDO);
$stmt->execute();
$cakeCheck = $stmt->get_result()->fetch_assoc();
$isCake = false;
foreach ($cakeCheck as $value) {
    if (!is_null($value)) {
        $isCake = true;
        break;
    }
}

// Only confirm if cart has items or a custom cake
if ($itemCount > 0 || $isCake) {
    $stmt = $conn->prepare("UPDATE `order` SET status = 'confirmed' WHERE IDO = ?");
    $stmt->bind_param("i", $IDO);
    $stmt->execute();

    // Create new empty pending order
    $stmt = $conn->prepare("INSERT INTO `order` (IDC, status) VALUES (?, 'pending')");
    $stmt->bind_param("i", $IDC);
    $stmt->execute();

    // Redirect to confirmation page
    header("Location: home.php");
    exit();
} else {
    echo "Your order is empty. Cannot confirm checkout.";
}
?>
