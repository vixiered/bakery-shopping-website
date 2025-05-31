<?php
session_start();

// Pull errors and old values from session
$usernameError = $_SESSION['usernameError'] ?? '';
$formError = $_SESSION['formError'] ?? '';

$old_usern = $_SESSION['old_usern'] ?? '';
$old_password = $_SESSION['old_password'] ?? '';
$old_birth = $_SESSION['old_birth'] ?? '';
$old_phone = $_SESSION['old_phone'] ?? '';
$old_email = $_SESSION['old_email'] ?? '';
$old_adress = $_SESSION['old_adress'] ?? '';

// Clear after using
unset($_SESSION['usernameError'], $_SESSION['formError']);
unset($_SESSION['old_usern'], $_SESSION['old_password'],$_SESSION['old_birth'], $_SESSION['old_email'], $_SESSION['old_adress'], $_SESSION['old_phone']);
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
                <a href="loginhtml.php" class="cart"></a>
                <a href="<?= $profilePath ?>" class="user"></a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="lg">
            <form class="log" method="POST" action="register.php">
                <h1>Creat an acccount</h1><br>
                <?php if (!empty($formError)): ?>
          <p style="color: red;"><?= htmlspecialchars($formError) ?></p>
        <?php endif; ?><br>
                <div>
                    <label>Username</label><br>
                    <input type="text" placeholder="username" class="usern" id="usern" name="usern" required
                        value="<?= htmlspecialchars($old_usern) ?>">
                    <?php if (!empty($usernameError)): ?>
                    <br><small style="color: red;"><?= $usernameError ?></small>
                    <?php endif; ?>
                </div><br>
                <div>
                    <label>Password</label><br>
                    <input type="password" placeholder="Password" class="password" value="<?= htmlspecialchars($old_password) ?>" id="password" name="password" minlength="8" required>
                </div><br>
                <div>
                    <label>Birthday</label><br>
                    <input type="date"  class="birth" value="<?= htmlspecialchars($old_birth) ?>" id="birth" name="birth" minlength="8" required>
                </div><br>
                 <div>
                    <label>Adress</label><br>
                    <input type="text" placeholder="adress" class="adress" value="<?= htmlspecialchars($old_adress) ?>" id="adress" name="adress" required>
                </div><br>
                 <div>
                    <label>Phone number</label><br>
                    <input type="tel" placeholder="Phone number" class="phone" value="<?= htmlspecialchars($old_phone) ?>" id="phone" name="phone" pattern="^(07|05|06)[0-9]{8}$" maxlength="10" required>
                </div><br>
                 <div>
                    <label>Email</label><br>
                    <input type="email" placeholder="Email" class="email" value="<?= htmlspecialchars($old_email) ?>" id="email" name="email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"  required>
                </div><br>
                <button class="in">
                        <span><em>Sign In</em> </span>
                </button><br>
                <span class="l"><a href="loginhtml.php">Already have an account?</a></span>
            </form>
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
</body>
</html>