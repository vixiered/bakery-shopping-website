<?php
session_start();
include 'paths.php';

$isLoggedIn = isset($_SESSION['user_id']);
if (!$isLoggedIn) {
    header("Location: loginhtml.php");
    exit();
}
$role = $_SESSION['role'] ?? 'guest';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$userIDP = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "bakery");
if ($conn->connect_error) {
    die("DB error: " . $conn->connect_error);
}

// Step 1: Get IDC
$stmt = $conn->prepare("SELECT IDC FROM customer WHERE IDP = ?");
$stmt->bind_param("i", $userIDP);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("You must be a registered customer to see the cart.");
}
$customerIDC = $result->fetch_assoc()['IDC'];

// Step 2: Get latest pending order
// Initialization
$items = [];
$totalCount = 0;
$totalPrice = 0.0;
$orderID = null;
$customOrder = null;

// Step 2: Get latest pending order
$stmt = $conn->prepare("SELECT IDO FROM `order` WHERE IDC = ? AND status = 'pending' ORDER BY IDO DESC LIMIT 1");
$stmt->bind_param("i", $customerIDC);
$stmt->execute();
$orderResult = $stmt->get_result();

if ($orderResult && $orderResult->num_rows > 0) {
    $orderID = $orderResult->fetch_assoc()['IDO'];

    // Step 3: Fetch items
    $stmt = $conn->prepare("
        SELECT i.name, i.description, i.price, i.imgpath, oi.IDO, oi.IDT
        FROM ordereditem oi 
        JOIN items i ON oi.IDT = i.IDT 
        WHERE oi.IDO = ?
    ");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $itemResult = $stmt->get_result();
    $items = $itemResult->fetch_all(MYSQLI_ASSOC);

    // Step 4: Totals
    foreach ($items as $item) {
        $totalCount++;
        $totalPrice += $item['price'];
    }

    // OPTIONAL: Step 5: Fetch custom cake fields, if any
    $stmt = $conn->prepare("
        SELECT style, size, occasion, flavour, topping, diet 
        FROM  `order`
        WHERE IDO = ?
    ");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $customResult = $stmt->get_result();
    if ($customResult->num_rows > 0) {
    $customOrder = $customResult->fetch_assoc();

    // Add custom cake to total count and price
    $totalCount++;

    // Optional: basic price logic (adjust as needed)
    $baseCakePrice = 0;

    // Add surcharges if needed (based on options)
    $style = strtolower($customOrder['style']);
    $size = strtolower($customOrder['size']);
    $flavour = strtolower($customOrder['flavour']);
    $topping = strtolower($customOrder['topping']);
    $diet = strtolower($customOrder['diet']);


    if ($size === 'twelve') $baseCakePrice += 1000;
    elseif ($size === 'twentyfour') $baseCakePrice += 2200;
    elseif ($size === 'thirtytwo') $baseCakePrice += 2500;
    elseif ($size === 'thirtyeight') $baseCakePrice += 3000;
    elseif ($size === 'fiftysix') $baseCakePrice += 3500;
    elseif ($size === 'thirtysix') $baseCakePrice += 4000;
    elseif ($size === 'fourtyfour') $baseCakePrice += 4500;
    elseif ($size === 'sixtytwo') $baseCakePrice += 5000;
    elseif ($size === 'seventyfour') $baseCakePrice += 6000;
    elseif ($size === 'hundered') $baseCakePrice += 6500;
    elseif ($size === 'hunderedtwentyeight') $baseCakePrice += 7000;
    elseif ($size === 'hunderedthirty') $baseCakePrice += 7200;
    elseif ($size === 'twohubderedeight') $baseCakePrice += 7500;


    if ($diet === 'glutenfree')  $baseCakePrice += 400;
    elseif( $diet === 'vegan') $baseCakePrice += 500;
    elseif( $diet === 'sugarfree') $baseCakePrice += 00;
    elseif( $diet === 'organic') $baseCakePrice += 600;

    if ($style === 'drip') $baseCakePrice += 100;
    elseif ($style === 'naked') $baseCakePrice += 100;
    elseif ($style === 'tall') $baseCakePrice += 350;
    elseif ($style === 'short') $baseCakePrice += 200;
    elseif ($style === 'square') $baseCakePrice += 400;

    if ($topping === 'fruits') $baseCakePrice += 450;
    elseif ($topping === 'chocolate') $baseCakePrice += 250;
    elseif ($topping === 'frosting') $baseCakePrice += 200;
    elseif ($topping === 'flowers') $baseCakePrice += 400;
    elseif ($topping === 'nuts') $baseCakePrice += 200;
    elseif ($topping === 'macaroons') $baseCakePrice += 400;

    if ($flavour === 'vanilla') $baseCakePrice += 300;
    elseif ($flavour === 'chocolate') $baseCakePrice += 300;
    elseif ($flavour === 'redvelvet') $baseCakePrice += 500;
    elseif ($flavour === 'straberry') $baseCakePrice += 400;
    elseif ($flavour === 'lemon') $baseCakePrice += 500;
    elseif ($flavour === 'cherry') $baseCakePrice += 800;
    elseif ($flavour === 'almond') $baseCakePrice += 900;
    elseif ($flavour === 'caramel') $baseCakePrice += 300;
    elseif ($flavour === 'coffee') $baseCakePrice += 300;
    elseif ($flavour === 'tiramisu') $baseCakePrice += 600;


    $totalPrice += $baseCakePrice;
}

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vixie's</title>
    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">
    <link rel="stylesheet" href="../customer/style.css">
    <link rel="stylesheet" href="../customer/styleclassic.css">
    <link rel="stylesheet" href="../adminPages/createitemSTYLE.css">
    <link rel="stylesheet" href="cartstyle.css">
</head>
<body class="back">
    <div class="awning">
        <div class="label">
            <a href="<?= $homePath ?>"><div class="logo">vixie's</div></a>
            <div class="browsing">
                <?php if ($role === 'admin'): ?>
                    <a href="../adminPages/manageorders.html" class="cart"></a>
                <?php else: ?>
                    <a href="cartdisplay.php" class="cart"></a>
                <?php endif; ?>
                <a href="<?= $isLoggedIn ? '../customer/profile.php' : 'loginhtml.php' ?>" class="user"></a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="cartt"><div class="✦">✦</div>Checkout<div class="✦">✦</div></div>
        <div class="kart">
            <?php if (empty($items) && !$customOrder): ?>
                <p class="empty">Your cart is empty. Add items before checking out!</p>
            <?php else: ?>
                <form method="POST" action="checkout.php" class="checkout-form kerf" onsubmit="return confirm('Are you sure you want to place the order?');">
                    <p class="summary-text">
                        You're about to place an order with <?= $totalCount ?> item(s)
                        totaling <?= number_format($totalPrice, 2) ?> DA.
                    </p><br>

                    <input type="hidden" name="order_id" value="<?= $orderID ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <label for="payment_method">Payment Method:</label><br>
                    <select name="payment_method" id="payment_method" class="drip" required>
                        <option value="" disabled selected>Select one</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="online">Online Payment</option>
                    </select><br>

                    <label for="delivery_option">Delivery:</label><br>
                    <select name="delivery_option" id="delivery_option" class="drip" required>
                        <option value="" disabled selected>Select one</option>
                        <option value="pickup">Pickup</option>
                        <option value="delivery">Delivery</option>
                    </select><br>

                    <button class="checkout add" type="submit" <?= empty($items) && !$customOrder ? 'disabled' : '' ?>>Checkout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="footer">
            <ul>
                <h2>Contact us:</h2>
                <li>📍 123 Flour St, Annaba</li>
                <li>📞 (+213) 756-789-049</li>
                <li>✉️ vixie'sTeam@vixiesbakery.com</li>
            </ul>
            <ul>
                <h2>Opening Hours</h2>
                <li>Mon–Thu: 7am – 6pm</li>
                <li>Sat: 9am – 4pm</li>
                <li>Fri: Closed</li>
            </ul>
            <ul>
                <h2>Follow Us</h2>
                <li><a href="#">📷 Instagram</a></li>
                <li><a href="#">📘 Facebook</a></li>
                <li><a href="#">📍 Google Maps</a></li>
            </ul>
        </div>
    </footer>
    <script src="<?= $navbuttonjs ?>"></script>
</body>
</html>
