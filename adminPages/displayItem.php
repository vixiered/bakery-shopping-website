<?php
session_start();
include 'adminpaths.php';
$isLoggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? 'guest';

$conn = new mysqli("localhost", "root", "", "bakery");
if ($conn->connect_error) die("DB error: " . $conn->connect_error);

$itemID = $_GET['id'] ?? null;

if (!$itemID) {
    die("No item selected.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST['action'] === 'delete') {
    $conn = new mysqli("localhost", "root", "", "bakery");
    if ($conn->connect_error) die("DB error: " . $conn->connect_error);

    $itemID = $_GET['id'] ?? null;
    if ($itemID) {
        // Get the section ID before deleting
        $stmt = $conn->prepare("SELECT IDS FROM items WHERE IDT = ?");
        $stmt->bind_param("i", $itemID);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();

        if (!$item) {
            die("Item not found.");
        }

        $sectionID = $item['IDS'];

        // Map section IDs to file names
        $sectionMap = [
            '12325' => 'baked.php',
            '12326' => 'traditional.php',
            '12327' => 'wedding.php',
        ];

        $redirectFile = $sectionMap[$sectionID] ?? 'admin.php'; // fallback if unknown section

        // Delete the item
        $stmt = $conn->prepare("DELETE FROM items WHERE IDT = ?");
        $stmt->bind_param("i", $itemID);
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: $redirectFile?section=$sectionID&deleted=true");
            exit();
        } else {
            echo "Error deleting item.";
        }
    }
}




$stmt = $conn->prepare("SELECT * FROM items WHERE IDT = ?");
$stmt->bind_param("i", $itemID);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("Item not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST['action'] === 'save') {
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
                        <a href="<?= $isLoggedIn ? '../customer/profile.php' : 'createadmin.php' ?>" class="user"></a>
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
                <button type="button" class="add" id="edit"><span>Edit item</span></button>
                <button type="submit" name="action" value="delete" class="add" id="delete" >
            <span>Delete item</span>
        </button>

<button type="submit" name="action" value="save" class="add" id="save" style="display:none;">
            <span>Save changes</span>
        </button>
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
document.getElementById('edit').addEventListener('click', function (e) {
    e.preventDefault();
    const fields = document.getElementsByClassName('E');
    Array.from(fields).forEach(elem => {
        elem.disabled = false;
        elem.classList.add('p');
    });
    document.getElementById('save').style.display = 'inline-block';
    this.style.display = 'none';
    document.getElementById('itempic').classList.add('h');
});
</script>
<?php if (isset($_GET['updated'])): ?>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const fields = document.getElementsByClassName('E');
        Array.from(fields).forEach(elem => {
            elem.disabled = true;
            elem.classList.remove('p');
        });
        document.getElementById('save').style.display = 'none';
        document.getElementById('edit').style.display = 'inline-block';
    });

    
</script>
<script>
document.addEventListener('input', function (e) {
    if (e.target.matches('textarea.dd')) {
        e.target.style.height = 'auto'; // reset height
        e.target.style.height = e.target.scrollHeight + 'px'; // fit to content
    }
});
</script>


<?php endif; ?>

</body>
</html>
