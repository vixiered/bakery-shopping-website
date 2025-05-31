<?php
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "bakery");
$role = $_SESSION['role'] ?? 'guest';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch section info
$sectionName = "Not found";
$sectionDesc = "No description available.";

$sql = "SELECT name, description FROM section WHERE IDS = 12326";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $sectionName = $row['name'];
    $sectionDesc = $row['description'];
}

// Fetch items from the section
$items = [];
$sqlItems = "SELECT IDT,name, description, price, imgpath FROM items WHERE IDS = 12326";
$resultItems = $conn->query($sqlItems);

if ($resultItems && $resultItems->num_rows > 0) {
    while ($row = $resultItems->fetch_assoc()) {
        $items[] = $row;
    }
}

$conn->close();
?>
<?php include 'adminpaths.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">
    <title>vixie's</title>
    <link rel="stylesheet" href="../customer/style.css">
    <link rel="stylesheet" href="../customer/styleclassic.css">
</head>
<body class="back"> 
    <div class="awning">
        <div class="label">
            <a href="<?= $homePath ?>">
                <div class="logo">vixie's</div>
            </a>
            <div class="browsing">
                        <?php if ($role === 'admin'): ?>
                         <a href="../adminPages/manageorders.php" class="cart"></a>
                        <?php else: ?>
                          <a href="../customer/cartdisplay.php" class="cart"></a>
                         <?php endif; ?>
                        <a href="<?= $isLoggedIn ? '../customer/profile.php' : 'createadmin.php' ?>" class="user"></a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="title">
            <div class="ti"><div class="✦">✦</div> <?= htmlspecialchars($sectionName) ?> <div class="✦">✦</div></div>
            <div class="desc"><?= htmlspecialchars($sectionDesc) ?></div>
        </div>

        <div class="mm">
            <div class="menu">
  <?php foreach ($items as $item): ?>
    <a href="displayItem.php?id=<?= $item['IDT'] ?>">
      <div class="item">
        <div class="pic" style="background-image: url('<?= htmlspecialchars($item['imgpath']) ?>');"></div>
        <div class="info">
          <div class="itemname"><span><?= htmlspecialchars($item['name']) ?></span></div>
          <div class="itemdesc"><span><?= htmlspecialchars($item['description']) ?></span></div><br>
          <div class="price"><span><?= htmlspecialchars($item['price']) ?> DA</span></div>
        </div>
      </div>
    </a>
  <?php endforeach; ?>
        <a href="createitempage.php?section=12326" class="createitem"></a>
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
