
<?php
session_start();
include 'paths.php';

$isLoggedIn = isset($_SESSION['user_id']);
if (!$isLoggedIn) {
    header("Location: loginhtml.php");
    exit();
}
$role = $_SESSION['role'] ?? 'guest';

$userIDP = $_SESSION['user_id']; // person ID

$conn = new mysqli("localhost", "root", "", "bakery");
if ($conn->connect_error) {
    die("DB error: " . $conn->connect_error);
}

// ✅ Get the correct customer ID from the customer table
$stmt = $conn->prepare("SELECT IDC FROM customer WHERE IDP = ?");
$stmt->bind_param("i", $userIDP);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("You must be a registered customer to see the cart.");
}
$customerIDC = $result->fetch_assoc()['IDC'];

// ✅ Now get the latest order using the correct IDC
$stmt = $conn->prepare("SELECT IDO FROM `order` WHERE IDC = ? AND status = 'pending' ORDER BY IDO DESC LIMIT 1
");
$stmt->bind_param("i", $customerIDC);
$stmt->execute();
$orderResult = $stmt->get_result();
$totalCount = 0;
$totalPrice = 0.0;
$customOrder = null;

$items = [];
if ($orderResult && $orderResult->num_rows > 0) {
    $orderID = $orderResult->fetch_assoc()['IDO'];
    $stmt = $conn->prepare("
    SELECT state, style, size, occasion, flavour, topping, diet, price 
    FROM `order` 
    WHERE IDO = ? 
      AND (state IS NULL OR state = 'pending' OR state = BINARY 'pending' OR LOWER(state) = 'pending' )
      AND style IS NOT NULL
");

$stmt->bind_param("i", $orderID);
$stmt->execute();
$customResult = $stmt->get_result();
$customOrder = $customResult->num_rows > 0 ? $customResult->fetch_assoc() : null;

    // ✅ Then get the items in that order
   $stmt = $conn->prepare("
    SELECT i.name, i.description, i.price, i.imgpath, oi.IDO, oi.IDT
    FROM ordereditem oi 
    JOIN items i ON oi.IDT = i.IDT 
    WHERE oi.IDO = ?
");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// ✅ Now calculate totals AFTER items are fetched
$totalCount = 0;
$totalPrice = 0.0;

foreach ($items as $item) {
    $totalCount++;
    $totalPrice += $item['price'];
}


}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">
    <title>vixie's</title>
    <link rel="stylesheet" href="../customer/style.css">
    <link rel="stylesheet" href="../customer/styleclassic.css">
    <link rel="stylesheet" href="../adminPages/createitemSTYLE.css">
    <link rel="stylesheet" href="cartstyle.css">
</head>
<body class="back"> 
    <div class="awning">
        <div class="label">
            <a href="<?= $homePath ?>">
                <div class="logo">vixie's</div>
            </a>
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
        <div class="cartt"><div class="✦">✦</div>Shopping cart<div class="✦">✦</div></div>
        <div class="kart">
    <?php if (empty($items) && !$customOrder ): ?>
        <p class="empty">Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
            <div class="oneitem">
                <div class="iconn" style="background-image: url('<?= $item['imgpath'] ?>');"></div>
                <div class="orinfo">
                    <div class="itemname"><?= htmlspecialchars($item['name']) ?></div>
                    <div class="descr"><?= htmlspecialchars($item['description']) ?></div>
                    <div class="prix"><?= htmlspecialchars($item['price']) ?> DA</div>
                </div>
                <form method="POST" action="deleteFromCart.php">
                       <input type="hidden" name="order_id" value="<?= $item['IDO'] ?>">
                          <input type="hidden" name="item_id" value="<?= $item['IDT'] ?>">
                        <button class="add delete" type="submit"><span>Delete</span></button>
                </form>
            </div>
        <?php endforeach; ?>
        <?php if ($customOrder): ?>
    <div class="oneitem">
        <div class="iconn" style="background-image: url('https://i.pinimg.com/736x/c7/11/32/c7113235b0bb3dfa84e7c1255d6f5fb0.jpg');"></div>
        <div class="orinfo">
            <div class="itemname"> Custom Cake</div>
            <div class="descr">
                <strong>Style:</strong> <?= htmlspecialchars($customOrder['style']) ?> |
                <strong>Size:</strong> <?= htmlspecialchars($customOrder['size']) ?> |
                <strong>Occasion:</strong> <?= htmlspecialchars($customOrder['occasion']) ?><br>
                <strong>Flavour:</strong> <?= htmlspecialchars($customOrder['flavour']) ?> |
                <strong>Topping:</strong> <?= htmlspecialchars($customOrder['topping']) ?> |
                <strong>Diet:</strong> <?= htmlspecialchars($customOrder['diet']) ?>
            </div>
            <div class="prix"><?= number_format($customOrder['price'], 2) ?> DA</div>
        </div>
        <form method="POST" action="deleteFromCart.php">
    <input type="hidden" name="order_id" value="<?= $orderID ?>">
    <input type="hidden" name="delete_custom" value="1">
    <button class="add delete" type="submit"><span>Delete</span></button>
</form>

    </div>
    <?php
        $totalCount++;
        $totalPrice += $customOrder['price'];
    ?>
<?php endif; ?>
        
    <?php endif; ?>
    
    <div class="total">
    <div class="count"><span class="to">Total items: </span><span><?= $totalCount ?></span></div>
    <div class="pricee"><span class="to">Total price:</span><span><?= number_format($totalPrice, 2) ?> DA</span> </div>
    <form method="GET" action="checkoutform.php" class="checkout-form">
    <button class="checkout add" type="submit" <?= empty($items) && !$customOrder ? 'disabled' : '' ?>>Checkout</button>
</form>


    </div>
    

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
