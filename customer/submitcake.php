<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bakery");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume the customer is logged in and has an ID
$idp = $_SESSION['user_id'] ?? null;

if (!$idp) {
    echo "<script>alert('You must be logged in to place an order.'); window.location.href='../customer/loginhtml.php';</script>";
    exit;
}

// Get the IDC (customer ID) for the logged-in user
$stmt = $conn->prepare("SELECT IDC FROM customer WHERE IDP = ?");
$stmt->bind_param("i", $idp);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    echo "<script>alert('Customer profile not found.'); window.location.href='../customer/loginhtml.php';</script>";
    exit;
}

$idc = $row['IDC'];

if (!$idp) {
    echo "<script>alert('You must be logged in to place an order.'); window.location.href='../customer/loginhtml.php';</script>";
    exit;
}

$style = $_POST['style'] ?? '';
$size = $_POST['size'] ?? '';
$occasion = $_POST['occasion'] ?? '';
$flavour = $_POST['flavour'] ?? '';
$topping = $_POST['topping'] ?? '';
$diet = $_POST['diet'] ?? '';

// Validate required fields
if (!$style || !$size || !$occasion || !$flavour) {
    echo "<script>alert('Please complete all required fields.'); window.history.back();</script>";
    exit;
}

// Price calculation
    $price = 0;

    $sizePrices = [
        'twelve' => 1000,
        'twentyfour' => 2200,
        'thirtytwo' => 2500,
        'thirtyeight' => 3000,
        'fiftysix' => 3500,
        'thirtysix' => 4000,
        'fourtyfour' => 4500,
        'sixtytwo' => 5000,
        'seventyfour' => 6000,
        'hundered' => 6500,
        'hunderedtwentyeight' => 7000,
        'hunderedthirty' => 7200,
        'twohubderedeight' => 7500
    ];

    $stylePrices = [
        'drip' => 100,
        'naked' => 100,
        'tall' => 350,
        'short' => 200,
        'square' => 400
    ];

    $toppingPrices = [
        'fruits' => 450,
        'chocolate' => 250,
        'frosting' => 200,
        'flowers' => 400,
        'nuts' => 200,
        'macaroons' => 400
    ];

    $dietPrices = [
        'vegan' => 500,
        'glutenfree' => 400,
        'sugarfree' => 300,
        'organic' => 600
    ];

    $flavourPrices = [
        'vanilla' => 300,
        'chocolate' => 300,
        'redvelvet' => 500,
        'straberry' => 400,
        'lemon' => 500,
        'cherry' => 800,
        'almond' => 900,
        'caramel' => 300,
        'coffee' => 300,
        'tiramisu' => 600,
    ];

    // Add prices
    if (isset($sizePrices[$size])) $price += $sizePrices[$size];
    if (isset($stylePrices[$style])) $price += $stylePrices[$style];
    if (isset($toppingPrices[$topping])) $price += $toppingPrices[$topping];
    if (isset($dietPrices[$diet])) $price += $dietPrices[$diet];
    if (isset($flavourPrices[$flavour])) $price += $flavourPrices[$flavour];

var_dump($user_id);

// Insert into orders table
// Check for existing pending order
$stmt = $conn->prepare("SELECT IDO FROM `order` WHERE IDC = ? AND status = 'pending' ORDER BY IDO DESC LIMIT 1");
$stmt->bind_param("i", $idc);
$stmt->execute();
$result = $stmt->get_result();
$existingOrder = $result->fetch_assoc();
$stmt->close();

if ($existingOrder) {
    // Update the existing pending order with custom cake details
    $ido = $existingOrder['IDO'];

    $stmt = $conn->prepare("UPDATE `order` 
        SET style = ?, size = ?, occasion = ?, flavour = ?, topping = ?, diet = ?, price = ?
        WHERE IDO = ?");
    $stmt->bind_param("ssssssdi", $style, $size, $occasion, $flavour, $topping, $diet, $price, $ido);

    if ($stmt->execute()) {
        echo "<script>alert('Your custom cake has been added to your cart!'); window.location.href='cartdisplay.php';</script>";
    } else {
        echo "<script>alert('Failed to update your order.'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    // No existing pending order, insert a new one

$state = trim($_POST['state'] ?? 'pending'); // Don't wrap it in extra quotes!

    $stmt = $conn->prepare("INSERT INTO `order` (IDC, style, size, occasion, flavour, topping, diet, price, state, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)");
    $stmt->bind_param("issssssdss", $idc, $style, $size, $occasion, $flavour, $topping, $diet, $price, $state, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Your custom cake has been added to your cart!'); window.location.href='cartdisplay.php';</script>";
    } else {
        echo "<script>alert('Failed to place order. Please try again.'); window.history.back();</script>";
    }
    $stmt->close();
}

header("Location: cartdisplay.php");

$stmt->close();
$conn->close();
?>
