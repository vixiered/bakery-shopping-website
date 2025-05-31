<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");  // redirect if not logged in
    exit();
}
$role = $_SESSION['role'] ?? 'guest';

$user_id = $_SESSION['user_id'];

$sql = "SELECT username, email,birth,phone, adress FROM person WHERE IDP = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $email = $row['email'];
    $phone = $row['phone'];
    $birth = $row['birth'];
    $adress = $row['adress'];
} else {
    $username = "Not found";
    $email = "";
    $phone = "";
    $birth = "";
    $adress = "";
}

$conn->close();
?>
<?php include 'paths.php'; ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="pics/Opera Instantané_2025-01-12_174652_www.png" type="image/x-icon">
    <title>vixie's</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styleclassic.css">
    <link rel="stylesheet" href="stylelogin.css">
    <link rel="stylesheet" href="styleprofile.css">
</head> 
<body class="back">
    <div class="awning">
        <div class="label">
            <a href="<?= $homePath ?>">
                <div class="logo">
                    vixie's
                </div>
            </a> 
            <div class="browsing">
                        <?php if ($role === 'admin'): ?>
                         <a href="../adminPages/manageorders.html" class="cart"></a>
                        <?php else: ?>
                          <a href="cartdisplay.php" class="cart"></a>
                         <?php endif; ?> 
                        <a href="<?= $profilePath ?>" class="user"></a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="hi"><div class="✦">✦</div> Welcome, <?= htmlspecialchars($username) ?> <div class="✦">✦</div> </div><br>
        <form style="width: 100%;" method="POST" action="updateprofile.php">
        <div class="profile">
            <div class="right">
                <div>
                    <div class="pict"></div><br>
                    <button type="button" class="edit" onclick="enableEditing()">Edit profile</button><br>
                    <button type="submit" class="edit" style="display:none;" id="saveBtn">Save Changes</button><br>
                    <button type="button" class="out" id="out" onclick="logout()">Log out</button><br>
                </div>
            </div>

            <div class="lign"></div>

            <div class="left">
                <div>
                    <div>
                        <label>Username :</label>
                        <input type="text" class="put" value="<?= htmlspecialchars($username) ?>" name="username" disabled>
                    </div><br>
                    <div>
                        <label>Email :</label>
                        <input type="text" class="put" value="<?= htmlspecialchars($email) ?>" name="email" disabled>
                    </div><br>
                    <div>
                        <label>Birthday :</label>
                        <input type="date" class="put" value="<?= htmlspecialchars($birth) ?>" name="birth" disabled>
                    </div><br>                    
                    <div>
                        <label>Adress :</label>
                        <input type="text" class="put" value="<?= htmlspecialchars($adress) ?>" name="adress" disabled>
                    </div><br>
                    <div>
                        <label>Phone number :</label>
                        <input type="number" class="put" value="<?= htmlspecialchars($phone) ?>" name="phone" disabled>
                    </div><br>
                </div>
             </form>
            </div>
        </div>
        
    </div>
    <footer >
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
                <li><a href="" >📷 Instagram</a></li>
                <li><a href="" >📘 Facebook</a></li>
                <li><a href="" >📍 Google Maps</a></li>
            </ul>
        </div>
    </footer>  
    <script>
let originalValues = {};

window.onload = function () {
    document.querySelectorAll("form input").forEach(input => {
        originalValues[input.name] = input.value;
    });
};

function enableEditing() {
    const inputs = document.querySelectorAll("form input");
    inputs.forEach(input => input.disabled = false);
    document.getElementById("saveBtn").style.display = "inline";
    document.getElementById("out").style.display = "none";

}

function logout() {
    window.location.href = 'logout.php';
}

// Handle empty inputs on form submit
document.querySelector("form").addEventListener("submit", function (e) {
    const inputs = this.querySelectorAll("input");
    inputs.forEach(input => {
        if (input.value.trim() === "") {
            input.value = originalValues[input.name] || "";
        }
    });
});
</script>


</body>
</html>