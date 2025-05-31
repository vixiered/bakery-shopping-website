<?php
session_start();
include 'adminpaths.php';

$role = $_SESSION['role'] ?? 'guest';
if ($role !== 'admin') {
    header("Location: ../loginhtml.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "bakery");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all pending orders
$orderQuery = "
    SELECT o.IDO, o.IDC, o.total,
           i.name, i.description, i.price, i.imgpath,
           o.style, o.size, o.occasion, o.flavour, o.topping, o.diet
    FROM `order` o
    LEFT JOIN ordereditem oi ON o.IDO = oi.IDO
    LEFT JOIN items i ON oi.IDT = i.IDT
    WHERE (o.state IS NULL OR o.state = 'pending')
    AND (o.style IS NOT NULL OR o.size IS NOT NULL OR o.occasion IS NOT NULL 
         OR o.flavour IS NOT NULL OR o.topping IS NOT NULL OR o.diet IS NOT NULL)
    ORDER BY o.IDO DESC
";


$result = $conn->query($orderQuery);
$orders = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderID = $row['IDO'];

        if (!isset($orders[$orderID])) {
            $orders[$orderID] = [
                'items' => [],
                'cake' => [
                    'style' => $row['style'],
                    'size' => $row['size'],
                    'occasion' => $row['occasion'],
                    'flavour' => $row['flavour'],
                    'topping' => $row['topping'],
                    'diet' => $row['diet']
                ],
                'total' => $row['total']
            ];
        }

        if (!empty($row['name'])) {
            $orders[$orderID]['items'][] = $row;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>vixie's Orders</title>
    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">
    <link rel="stylesheet" href="../customer/style.css">
    <link rel="stylesheet" href="../customer/styleclassic.css">
    <link rel="stylesheet" href="../adminPages/createitemSTYLE.css">
    <link rel="stylesheet" href="../customer/cartstyle.css">
    <link rel="stylesheet" href="manage.css">
</head>
<body class="back"> 
    <div class="awning">
        <div class="label">
            <a href="<?= $homePath ?>"><div class="logo">vixie's</div></a>
            <div class="browsing">
                <?php if ($role === 'admin'): ?>
                         <a href="../adminPages/manageorders.php" class="cart"></a>
                        <?php else: ?>
                          <a href="../customer/cartdisplay.php" class="cart"></a>
                         <?php endif; ?> 
                <a href="../customer/profile.php" class="user"></a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="cartt"><div class="✦">✦</div>Manage Orders<div class="✦">✦</div></div>
        <div class="kart">
            <?php if (!empty($orders)): ?>
<?php foreach ($orders as $orderID => $order): ?>
    <form method="POST" action="updateOrderStatus.php">
        <div class="oneitem">
            <input type="hidden" name="order_id" value="<?= $orderID ?>">
            <div class="ord">
                <?php foreach ($order['items'] as $item): ?>
                    <div class="orinfoo">
                        <div class="itemnme"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="prixx"><?= number_format($item['price'], 2) ?> DA</div>
                    </div><br>
                <?php endforeach; ?>

                <div class="tot">Total: <?= number_format($order['total'], 2) ?> DA</div>
            </div>

            <div class="customcake">
                <strong>🎂 Custom Cake:</strong><br>
                Style: <?= htmlspecialchars($order['cake']['style']) ?><br>
                Size: <?= htmlspecialchars($order['cake']['size']) ?><br>
                Occasion: <?= htmlspecialchars($order['cake']['occasion']) ?><br>
                Flavour: <?= htmlspecialchars($order['cake']['flavour']) ?><br>
                Topping: <?= htmlspecialchars($order['cake']['topping']) ?><br>
                Diet: <?= htmlspecialchars($order['cake']['diet']) ?><br>
            </div>

            <div class="manage">
                <input type="hidden" name="order_id" value="<?= $orderID ?>">
                <button class="add confirm" name="action" value="confirm" title="Confirm this order">✔ Confirm</button><br>
                <button class="add cancel" name="action" value="cancel" title="Cancel this order">✘ Cancel</button>
            </div>
        </div>
    </form>
<?php endforeach; ?>

            <?php else: ?>
                <p style="color: black;font-size:20px;font-family:'Times New Roman', Times, serif;font-style:oblique; text-align: center;">No pending orders.</p>
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
</body>
</html>
