
<?php
session_start();
$sectionID = $_GET['section'] ?? null;

if (!$sectionID) {
    die("No section selected.");
}
$role = $_SESSION['role'] ?? 'guest';

// Then use $sectionID to save the new item with that IDS in the database.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $imagePath = '';

    // Handle image upload
    if (isset($_FILES['itempic']) && $_FILES['itempic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir);
        $filename = basename($_FILES['itempic']['name']);
        $targetFile = $uploadDir . time() . '_' . $filename;

        if (move_uploaded_file($_FILES['itempic']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    // Save to DB
    $conn = new mysqli("localhost", "root", "", "bakery");
    if ($conn->connect_error) die("DB error: " . $conn->connect_error);

    $stmt = $conn->prepare("INSERT INTO items (name, description, price, imgpath, IDS) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", $name, $description, $price, $imagePath, $sectionID);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    // Redirect back to section page after creation
    switch ($sectionID) {
    case 12325:
        $redirectPage = "baked.php";
        break;
    case 12326:
        $redirectPage = "traditional.php";
        break;
    case 12327:
            $redirectPage = "wedding.php";
        break;
    default:
        $redirectPage = "home.php"; // fallback page
}
header("Location: $redirectPage");
exit;
 // change to your section page
    exit;
}

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
    <link rel="stylesheet" href="createitemSTYLE.css">
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
                        <a href="<?= $isLoggedIn ? 'profile.php' : 'loginhtml.php' ?>" class="user"></a>
            </div>
        </div>
    </div>

    <div class="container">
        <form class="makeitem" method="POST" action="?section=<?= $sectionID ?>" enctype="multipart/form-data">
            <div class="leftside">
                <label for="itempic" class="itempic"></label>
                <input type="file" id="itempic" style="display: none;" name="itempic" accept=".jpg, .png, .jpeg,"  value="">
            </div>
            <div class="rightside">
                <input type="text" name="name" class="p name" placeholder="Item name" value=""  required><br>
                <textarea type="text" name="description" class="p descr" placeholder="Item description" value=""  required></textarea><br>
                <input type="text" name="price" class="p prix" placeholder="Item price in DA" value=""  required><br>
                <button class="add"><span>Create item</span></button>
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
    <script>
document.getElementById('itempic').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (event) {
            document.querySelector('.itempic').style.backgroundImage = `url('${event.target.result}')`;
            document.querySelector('.itempic').style.backgroundSize = `100%`;
        };
        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>
