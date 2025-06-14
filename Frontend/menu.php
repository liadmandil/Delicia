<?php
// --------------------
// 🔹 1. הצגת שגיאות לפיתוח
// --------------------
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log'); // log to local file
session_start();

// --------------------
// 🔹 2. התחברות למסד הנתונים
// --------------------
$conn = new mysqli("localhost", "root", "1234", "delicia_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --------------------
// 🔹 3. פונקציית שליפת תפריט
// --------------------
function fetchMenuItems($conn) {
    $result = $conn->query("SELECT id, name, description, price FROM menu");
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}

// --------------------
// 🔹 4. יצירת הזמנה חדשה
// --------------------
function createNewOrder($conn, $phone, $name, $email, $totalPrice, $deliveryAddress) {
    $query = "INSERT INTO orders (phone, name, email, created_at, total_price, delivery_address) VALUES (?, ?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssds", $phone, $name, $email, $totalPrice, $deliveryAddress);
    if (!$stmt->execute()) {
        error_log("❌ Failed to insert item $menuItemId for order $orderId: " . $stmt->error);
    }
    return $stmt->insert_id;
}

// ▶ handle the POST from the hidden iframe
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['phone'])) {

    $phone   =       $_POST['phone'];
    $name    =       $_POST['name'];
    $email   =       $_POST['email'];
    $total   = (float)$_POST['totalPrice'];
    $address =       $_POST['deliveryAddress'];

    error_log("itemIds: " . $_POST['itemIds']);

    $orderId = createNewOrder($conn, $phone, $name, $email, $total, $address);
    if (!$orderId) {
        error_log("❌ createNewOrder failed: " . $conn->error);
        exit;
    }

    $itemPairs = explode(',', $_POST['itemIds']); // e.g., "55:1,49:1"
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?, ?, ?)");

    if (!$stmt) {
        error_log("❌ Prepare failed: " . $conn->error);
        exit;
    }

    foreach ($itemPairs as $pair) {
        list($menuItemId, $quantity) = explode(':', $pair);
        $stmt->bind_param("iii", $orderId, $menuItemId, $quantity);
        if (!$stmt->execute()) {
            error_log("❌ Insert failed for item $menuItemId: " . $stmt->error);
        } else {
            error_log("✅ Inserted item $menuItemId x$quantity into order_items");
        }
    }
    // stop so the iframe stays blank
    exit;
}

// --------------------
// 🔹 5. שליפת הנתונים בפועל
// --------------------
$menuItems = fetchMenuItems($conn);
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Menu</title>

  <!-- עיצוב וגופנים -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/menu.css"/>
</head>
<body>
  <!-- 🔸 תפריט ניווט -->
  <div id="navbar"></div>

  <!-- 🔸 גוף העמוד -->
  <main id="main-content" style="padding: 100px 20px 40px;">
    
    <!-- תפריט -->
    <section id="menu-section">
      <h2 class="section-title">התפריט שלנו</h2>
      <div class="menu-grid">
        <?php foreach ($menuItems as $item): ?>
            <div class="menu-item">
                <h3><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></h3>
                <p><?= nl2br(htmlspecialchars($item['description'], ENT_QUOTES)) ?></p>
                <div class="price">₪<?= number_format($item['price'], 2) ?></div>
                <button data-id="<?= htmlspecialchars($item['id'], ENT_QUOTES) ?>">הוסף לסל</button>
            </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- עגלת קניות -->
    <div class="cart-container">
      <aside id="cart-section">
        <h2 class="section-title">עגלת הזמנות</h2>
        <ul class="cart-items"></ul>
        <p class="cart-total">סה"כ: ₪<span>0</span></p>
        <button class="checkout-button">סיום הזמנה</button>
        <div class="order-form-container" style="display:none;"></div>
      </aside>
    </div>
  </main>
  <iframe name="hiddenFrame" style="display: none;"></iframe>


  <!-- 🔸 העברת נתונים ל-JavaScript -->
  <script>
    const menuItems = <?= json_encode($menuItems, JSON_UNESCAPED_UNICODE); ?>;
  </script>

  <!-- 🔸 JavaScript -->
  <script src="js/navbar.js"></script>
  <script src="js/menu.js"></script>
</body>
</html>
