<?php
session_start();
include 'paths.php';
$isLoggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? 'guest';

$conn = new mysqli("localhost", "root", "", "bakery");
if ($conn->connect_error) die("DB error: " . $conn->connect_error);

$itemID = $_GET['id'] ?? null;

if (!$itemID) {
    die("No item selected.");
}

$stmt = $conn->prepare("SELECT * FROM items WHERE IDT = ?");
$stmt->bind_param("i", $itemID);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("Item not found.");
}


if ($_SERVER["REQUEST_METHOD"] === "POST") { 
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_to_cart'])) {
    if (!$isLoggedIn) {
        header("Location: loginhtml.php");
        exit();
    }

 $userIDP = $_SESSION['user_id'];

// Get customer ID
$stmt = $conn->prepare("SELECT IDC FROM customer WHERE IDP = ?");
$stmt->bind_param("i", $userIDP);
$stmt->execute();
$customerResult = $stmt->get_result();

if ($customerResult->num_rows === 0) {
    die("You must be a registered customer to order.");
}
$customerIDC = $customerResult->fetch_assoc()['IDC'];

// Check if there is already an active cart
$stmt = $conn->prepare("SELECT IDO FROM `order` WHERE IDC = ? AND status = 'cart' LIMIT 1");
$stmt->bind_param("i", $customerIDC);
$stmt->execute();
$orderResult = $stmt->get_result();

if ($orderResult->num_rows > 0) {
    $orderID = $orderResult->fetch_assoc()['IDO'];
} else {
    // No cart yet, create one
    $emptyItemList = '';
// ✅ First check if there's already a pending order
$stmt = $conn->prepare("SELECT IDO FROM `order` WHERE IDC = ? AND status = 'pending' ORDER BY IDO DESC LIMIT 1");
$stmt->bind_param("i", $customerIDC);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $orderID = $result->fetch_assoc()['IDO'];
} else {
    // ✅ No existing order, create a new one
    $stmt = $conn->prepare("INSERT INTO `order` (items, total, IDC, status) VALUES ('', 0, ?, 'pending')");
    $stmt->bind_param("i", $customerIDC);
    $stmt->execute();
    $orderID = $conn->insert_id;
}
    
}

// Add item to ordereditem table
$itemID = $_GET['id'];
$quantity = 1;

$stmt = $conn->prepare("INSERT INTO ordereditem (IDT, IDO, quantity) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $itemID, $orderID, $quantity);
$stmt->execute();

// Update total and item list
// 1. Get all items in the cart
$stmt = $conn->prepare("
    SELECT i.name, i.price, oi.quantity
    FROM ordereditem oi
    JOIN items i ON oi.IDT = i.IDT
    WHERE oi.IDO = ?
");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$itemsList = [];

while ($row = $result->fetch_assoc()) {
    $total += $row['price'] * $row['quantity'];
    $itemsList[] = $row['name'];
}

$itemsString = implode(", ", $itemsList);

// 2. Update order summary
$stmt = $conn->prepare("UPDATE `order` SET total = ?, items = ? WHERE IDO = ?");
$stmt->bind_param("dsi", $total, $itemsString, $orderID);
$stmt->execute();

header("Location: cartdisplay.php");
exit();


}

 } 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['name'])) {
    $conn = new mysqli("localhost", "root", "", "bakery");
    if ($conn->connect_error) die("DB error: " . $conn->connect_error);

    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = str_replace(" DA", "", $_POST['price']); // Clean DA
    $itemID = $_GET['id']; // Get item ID from URL

    // Optional: handle image upload
    if (isset($_FILES['itempic']) && $_FILES['itempic']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["itempic"]["name"]);
        move_uploaded_file($_FILES["itempic"]["tmp_name"], $target_file);
        $imgPath = $target_file;

        $stmt = $conn->prepare("UPDATE items SET name = ?, description = ?, price = ?, imgpath = ? WHERE IDT = ?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $imgPath, $itemID);
    } else {
        $stmt = $conn->prepare("UPDATE items SET name = ?, description = ?, price = ? WHERE IDT = ?");
        $stmt->bind_param("ssdi", $name, $description, $price, $itemID);
    }

    if ($stmt->execute()) {
        header("Location: displayItem.php?id=$itemID&updated=true");
        exit();
    } else {
        echo "Error updating item.";
    }

    $stmt->close();
    $conn->close();
}


$stmt->close();
$conn->close();
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
        <form class="makeitem" method="POST" action="displayItem.php?id=<?= $itemID ?>" enctype="multipart/form-data">
            <div class="leftside">
                <label for="itempic" id="itempic" class="E itempic" style="background-image: url('<?= $item['imgpath'] ?>');background-size:100%;"></label>
                <input type="file" class="E" id="itempic" style="display: none;" name="itempic" accept=".jpg, .png, .jpeg,"   disabled>
            </div>
            <div class="rightside">
                <input type="text" name="name" class="E name" placeholder="Item name" value="<?= htmlspecialchars($item['name']) ?>"  required disabled><br>
                <textarea type="text" name="description" class="E descr dd" placeholder="Item description"   required disabled><?= htmlspecialchars($item['description']) ?></textarea><br>
                <input type="text" name="price" class="E prix" placeholder="Item price in DA" value="<?= htmlspecialchars($item['price']) ?> DA"  required disabled><br>
                <button type="submit" class="add" name="add_to_cart" value="1"><span>Add item to cart</span></button>

            </div>
        </form>
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
