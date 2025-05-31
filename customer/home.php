<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? 'guest';

?>

<?php include 'paths.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">
    <title>vixie's</title>
    <link rel="stylesheet" href="style.css">
    
</head> 
<body class="back">
    <div class="main1">
        <div class="banner" id="main-header">  
            <div class="awning" id="aw">
                    <div class="browsing">
                        <?php if ($role === 'admin'): ?>
                         <a href="../adminPages/manageorders.php" class="cart"></a>
                        <?php else: ?>
                          <a href="cartdisplay.php" class="cart"></a>
                         <?php endif; ?>  

                        <a href="<?= $isLoggedIn ? 'profile.php' : 'loginhtml.php' ?>" class="user"></a>
                    </div>
            </div>
               <div class="vix" id="sh"><a href="<?= $homePath ?>"><div class="vixie">V I X I E ' S</div></a></div>
        </div>
        <div class="page">
        <div class="contain">
            <!-- welcom -->
            <div class="wlcm">
                <h1>Golden crusts and sugared air, warmth and love are waiting there</h1>
            </div> 
            <!-- services -->
            <div class="service">
                <?php if ($role === 'admin'): ?>
                    <a href="../adminPages/baked.php"><div class="baked">Baked <br>Goods</div></a>
                    <a href="../adminPages/traditional.php"><div class="traditional">Traditional<br> Sweets</div></a>
                    <a href="adminsection.php?section=wedding"><div class="wedings">Wedding<br>Cakes</div></a>
                <?php else: ?>
                    <a href="baked.php"><div class="baked">Baked <br>Goods</div></a>
                    <a href="traditional.php"><div class="traditional">Traditional<br> Sweets</div></a>
                    <a href="wedding.php"><div class="wedings">Wedding<br>Cakes</div></a>
                <?php endif; ?>
            </div>
        </div>
    </div>  <!-- page -->
    </div ><!-- main -->
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
<script src="sec.js"></script> 
<script>
    window.addEventListener('scroll', function () {
      const header = document.getElementById('main-header');
      const sh= document.getElementById('sh');
      const aw= document.getElementById('aw');
      if (window.scrollY > 50) {
        header.classList.add('shrink');
        sh.classList.add('short');
        aw.classList.add('upward');
      } else {
        header.classList.remove('shrink');
        sh.classList.remove('short');
        aw.classList.remove('upward');
      }
    });

  </script>
         
</body> 
</html>