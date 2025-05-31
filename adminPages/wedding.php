<?php
session_start();


// Connect to the database
$conn = new mysqli("localhost", "root", "", "bakery");
$isLoggedIn = isset($_SESSION['user_id']);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$role = $_SESSION['role'] ?? 'guest';

// Fetch the section with IDS = 12325
$sql = "SELECT name, description FROM section WHERE IDS = 12327";
$result = $conn->query($sql);

// Set default values in case nothing is found
$sectionName = "Not found";
$sectionDesc = "No description available.";

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $sectionName = $row['name'];
    $sectionDesc = $row['description'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $style = $_POST['style'] ?? '';
    $size = $_POST['size'] ?? '';
    $occasion = $_POST['occasion'] ?? '';
    $flavour = $_POST['flavour'] ?? '';
    $topping = $_POST['topping'] ?? '';
    $diet = $_POST['diet'] ?? '';

    // Define base prices for each option
    $price = 0;

    $sizePrices = [
        'twelve' => 1000,
        'twentyfour' => 2200,
        'thirtytwo' => 2500,
        'thirtyeight' => 3000,
        'fiftysix' => 3500,
        'thirtysix' => 4000,
        'fourtyfour' => 4500,
        'sixtytwo' => 5000,
        'seventyfour' => 6000,
        'hundered' => 6500,
        'hunderedtwentyeight' => 7000,
        'hunderedthirty' => 7200,
        'twohubderedeight' => 7500
    ];

    $stylePrices = [
        'drip' => 100,
        'naked' => 100,
        'tall' => 350,
        'short' => 200,
        'square' => 400
    ];

    $toppingPrices = [
        'fruits' => 450,
        'chocolate' => 250,
        'frosting' => 200,
        'flowers' => 400,
        'nuts' => 200,
        'macaroons' => 400
    ];

    $dietPrices = [
        'vegan' => 500,
        'glutenfree' => 400,
        'sugarfree' => 300,
        'organic' => 600
    ];

    $flavourPrices = [
        'vanilla' => 300,
        'chocolate' => 300,
        'redvelvet' => 500,
        'straberry' => 400,
        'lemon' => 500,
        'cherry' => 800,
        'almond' => 900,
        'caramel' => 300,
        'coffee' => 300,
        'tiramisu' => 600,
    ];

    // Add prices
    if (isset($sizePrices[$size])) $price += $sizePrices[$size];
    if (isset($stylePrices[$style])) $price += $stylePrices[$style];
    if (isset($toppingPrices[$topping])) $price += $toppingPrices[$topping];
    if (isset($dietPrices[$diet])) $price += $dietPrices[$diet];
    if (isset($flavourPrices[$flavour])) $price += $flavourPrices[$flavour];


    // For simplicity, assume occasion and flavour do not change the price
    echo "<script>alert('Estimated price: DA $price');</script>";
}


$conn->close();
?>
<?php include 'adminpaths.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vixie's</title>
    <link rel="stylesheet" href="../customer/style.css">
    <link rel="stylesheet" href="../customer/styleclassic.css">
    <link rel="stylesheet" href="../customer/weddingstyle.css">
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
                          <a href="cartdisplay.php" class="cart"></a>
                         <?php endif; ?> 
                <a href="<?= $profilePath ?>" class="user"></a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="title">
            <div class="ti"><div class="✦">✦</div> <?= htmlspecialchars($sectionName) ?> <div class="✦">✦</div></div>
            <div class="desc"><?= htmlspecialchars($sectionDesc) ?></div>
        </div>

        <div class="mm">
            <form action="submitcake.php" method="POST" style="width: 99%;">
            <div class=" menuu ">
                  <div class="dropdown-section">
  <button type="button" class="dropdown-btn" onclick="toggleSection('style')"> Style</button>
  <div class="dropdown-content" id="style">
    <div class="radio-wrap">
    <label class="custom-radio "><input class="ir" type="radio" name="style" value="drip"> <span class="radio-label"><div class="ig drip"></div>Drip cake</span></label><br>
    <label class="custom-radio "><input class="ir" type="radio" name="style" value="naked"> <span class="radio-label"><div class="ig naked"></div>Naked cake</span></label><br>
    <label class="custom-radio "><input class="ir" type="radio" name="style" value="tall"> <span class="radio-label"><div class="ig tall"></div>Tall cake</span></label>
    <label class="custom-radio "><input class="ir" type="radio" name="style" value="short"> <span class="radio-label"><div class="ig short"></div>short cake</span></label>
    <label class="custom-radio "><input class="ir" type="radio" name="style" value="square"> <span class="radio-label"><div class="ig square"></div>Square cake</span></label>    
</div>
</div>
                  </div>

<div class="dropdown-section">
  <button type="button" class="dropdown-btn" onclick="toggleSection('size')"> Size</button>
  <div class="dropdown-content" id="size">
    <div class="radio-wrap">
    <label class="custom-radio "><input class="ir" type="radio" name="size" value="twelve"> <span class="radio-label"><div class="ig twelve"></div></span></label><br>
    <label class="custom-radio "><input class="ir" type="radio" name="size" value="twentyfour"> <span class="radio-label"><div class="ig twentyfour"></div></span></label><br>
    <label class="custom-radio "><input class="ir" type="radio" name="size" value="thirtytwo"> <span class="radio-label"><div class="ig thirtytwo"></div></span></label>
    <label class="custom-radio "><input class="ir" type="radio" name="size" value="thirtyeight"> <span class="radio-label"><div class="ig thirtyeight"></div></span></label>
    <label class="custom-radio "><input class="ir" type="radio" name="size" value="fiftysix"> <span class="radio-label"><div class="ig fiftysix"></div></span></label>  
     <label class="custom-radio "><input class="ir" type="radio" name="size" value="thirtysix"> <span class="radio-label"><div class="ig thirtysix"></div></span></label>
         <label class="custom-radio "><input class="ir" type="radio" name="size" value="fourtyfour"> <span class="radio-label"><div class="ig fourtyfour"></div></span></label> 
    <label class="custom-radio "><input class="ir" type="radio" name="size" value="sixtytwo"> <span class="radio-label"><div class="ig sixtytwo"></div></span></label>  
        <label class="custom-radio "><input class="ir" type="radio" name="size" value="seventyfour"> <span class="radio-label"><div class="ig seventyfour"></div></span></label>  
            <label class="custom-radio "><input class="ir" type="radio" name="size" value="hundered"> <span class="radio-label"><div class="ig hundered"></div></span></label> 
        <label class="custom-radio "><input class="ir" type="radio" name="size" value="hunderedtwentyeight"> <span class="radio-label"><div class="ig hunderedtwentyeight"></div></span></label> 
            <label class="custom-radio "><input class="ir" type="radio" name="size" value="hunderedthirty"> <span class="radio-label"><div class="ig hunderedthirty"></div></span></label> 
            <label class="custom-radio "><input class="ir" type="radio" name="size" value="twohubderedeight"> <span class="radio-label"><div class="ig twohunderedeight"></div></span></label>    
  
</div>
  </div>
</div>

<div class="dropdown-section">
  <button type="button" class="dropdown-btn" onclick="toggleSection('ocassion')"> Occasion</button>
  <div class="dropdown-content" id="ocassion">
    <div class="radio-wrap">
    <label class="custom-radio "><input class="ir" type="radio" name="occasion" value="wedding"> <span class="radio-label"><div class="ig wedding"></div>Wedding</span></label><br>
    <label class="custom-radio "><input class="ir" type="radio" name="occasion" value="birthdy"> <span class="radio-label"><div class="ig birthd"></div>Birthday</span></label><br>
    <label class="custom-radio "><input class="ir" type="radio" name="occasion" value="graduation"> <span class="radio-label"><div class="ig grad"></div>Graduation</span></label>
    <label class="custom-radio "><input class="ir" type="radio" name="occasion" value="party"> <span class="radio-label"><div class="ig party"></div>Party</span></label>
</div>
  </div>
</div>

<div class="dropdown-section">
  <button type="button" class="dropdown-btn" onclick="toggleSection('flavour')"> Flavor</button>
  <div class="dropdown-content" id="flavour">
    <div class="radio-wrap">
    <label class="custom-radio "><input class="ir" type="radio" name="flavour" value="vanilla"> <span class="radio-label"><div class="ig vanilla"></div>Vanilla</span></label><br>
    <label class="custom-radio "><input class="ir" type="radio" name="flavour" value="chocolate"> <span class="radio-label"><div class="ig choc"></div>Chocolate</span></label><br>
    <label class="custom-radio "><input class="ir" type="radio" name="flavour" value="redvelvet"> <span class="radio-label"><div class="ig red"></div>Red velvet</span></label>
    <label class="custom-radio "><input class="ir" type="radio" name="flavour" value="strawberry"> <span class="radio-label"><div class="ig straw"></div>Strawberry</span></label>
    <label class="custom-radio "><input class="ir" type="radio" name="flavour" value="lemon"> <span class="radio-label"><div class="ig lemon"></div>Lemon</span></label> 
    <label class="custom-radio "><input class="ir" type="radio" name="flavour" value="cherry"> <span class="radio-label"><div class="ig cherry"></div>Cherry</span></label>
    <label class="custom-radio "><input class="ir" type="radio" name="flavour" value="almond"> <span class="radio-label"><div class="ig almon"></div>Almond</span></label>
<label class="custom-radio "><input class="ir" type="radio" name="flavour" value="caramel"> <span class="radio-label"><div class="ig caramel"></div>Caramel</span></label>
    <label class="custom-radio "><input class="ir" type="radio" name="flavour" value="tiramisu"> <span class="radio-label"><div class="ig tiramisu"></div>Tiramisu</span></label> 
    <label class="custom-radio "><input class="ir" type="radio" name="flavour" value="coffee"> <span class="radio-label"><div class="ig coffee"></div>Coffee</span></label>    
   
    
    
    
   
</div>
  </div>
</div>

<div class="dropdown-section">
  <button type="button" class="dropdown-btn" onclick="toggleSection('topping')"> Topping</button>
  <div class="dropdown-content" id="topping">
    <div class="radio-wrap">
    <label class="custom-radio "><input class="ir" type="radio" name="topping" value="fruits"> <span class="radio-label"><div class="ig fruit"></div>Fruits</span></label><br>
    <label class="custom-radio "><input class="ir" type="radio" name="topping" value="chocolate"> <span class="radio-label"><div class="ig choco"></div>Chocolate</span></label><br>
    <label class="custom-radio "><input class="ir" type="radio" name="topping" value="frosting"> <span class="radio-label"><div class="ig frost"></div>Frosting</span></label>
    <label class="custom-radio "><input class="ir" type="radio" name="topping" value="flowers"> <span class="radio-label"><div class="ig flower"></div>Flowers</span></label>
    <label class="custom-radio "><input class="ir" type="radio" name="topping" value="nuts"> <span class="radio-label"><div class="ig nut"></div>Nuts</span></label> 
        <label class="custom-radio "><input class="ir" type="radio" name="topping" value="macaroons"> <span class="radio-label"><div class="ig maca"></div>Macaroons</span></label>    
   
   
</div>
  </div>
</div>

<div class="dropdown-section">
  <button type="button" class="dropdown-btn" onclick="toggleSection('dietary')"> Dietary Preferences</button>
  <div class="dropdown-content" id="dietary">
    <div class="radio-wrap">
    <label class="custom-radio it "><input class=" it" type="radio" name="diet" value="vegan"> <span class="radio-label">Vegan</span></label><br>
    <label class="custom-radio it "><input class=" it" type="radio" name="diet" value="glutenfree"> <span class="radio-label">Gluten free</span></label><br>
    <label class="custom-radio it "><input class=" it" type="radio" name="diet" value="sugarfree"> <span class="radio-label">Sugar free</span></label>
    <label class="custom-radio it "><input class=" it" type="radio" name="diet" value="organic"> <span class="radio-label">Organic</span></label>
</div>
  </div>
</div>

                  <div class="priccee">0 DA</div>
             </div>
            </form>
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
    <script>
function toggleSection(id) {
  const el = document.getElementById(id);
  el.style.display = el.style.display === 'block' ? 'none' : 'block';
}
</script>
<script>
const sizePrices = {
    twelve : 1000, twentyfour : 2200, thirtytwo : 2500, thirtyeight : 3000, fiftysix : 3500, thirtysix : 4000, fourtyfour : 4500, sixtytwo : 5000, seventyfour : 6000, hundered : 6500, hunderedtwentyeight : 7000, hunderedthirty : 7200, twohubderedeight : 7500
};

const stylePrices = {
   drip: 100,naked: 100, tall: 350,short: 200,square: 400
};

const toppingPrices = {
    fruits : 450,chocolate : 250, frosting : 200,  flowers : 400  ,   nuts : 200,macaroons : 400
};

const dietPrices = {
    vegan: 500, glutenfree: 400, sugarfree: 300, organic: 600
};

const flavourPrices = {
    vanilla : 300 , chocolate : 300 , redvelvet : 500 , straberry : 400 , lemon : 500 ,  cherry : 800 ,  almond : 900 ,  caramel : 300 ,  coffee : 300 ,  tiramisu : 600,
};

const priceDisplay = document.querySelector(".priccee");

function calculatePrice() {
    let price = 0;

    const size = document.querySelector("input[name='size']:checked")?.value;
    const style = document.querySelector("input[name='style']:checked")?.value;
    const topping = document.querySelector("input[name='topping']:checked")?.value;
    const diet = document.querySelector("input[name='diet']:checked")?.value;
    const flavour = document.querySelector("input[name='flavour']:checked")?.value;


    if (size && sizePrices[size]) price += sizePrices[size];
    if (style && stylePrices[style]) price += stylePrices[style];
    if (topping && toppingPrices[topping]) price += toppingPrices[topping];
    if (diet && dietPrices[diet]) price += dietPrices[diet];
    if (flavour && flavourPrices[flavour]) price += flavourPrices[flavour];


    priceDisplay.textContent = "DA " + price;
}

document.querySelectorAll("input[type='radio']").forEach(input => {
    input.addEventListener("change", calculatePrice);
});
</script>

</body>
</html>
