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
            <form class="log" method="POST" action="login.php">
                <h1>Login</h1><br>
                <?php
session_start();
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<?php if ($error): ?>
    <div style="color: red; margin-bottom: 10px;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

                <div>
                    <label>Username</label><br>
                    <input type="text" placeholder="username" class="usern" id="usern" name="usern" required>
                </div><br>
                <div>
                    <label>Password</label><br>
                    <input type="password" placeholder="Password" class="password" id="password" name="password" minlength="8" required>
                </div>
                <button class="in">
                        <span>Log In</span>
                </button><br>
                <span class="l"><a href="signin.php">Don't have an account?</a></span>
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