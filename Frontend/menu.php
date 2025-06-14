<?php
// --------------------
// 🔹 1. הצגת שגיאות לפיתוח
// --------------------
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
// 🔹 4. שליפת טלפון לפי user_id
// --------------------
function getUserPhone($conn, $userId) {
    if (!$userId) return null;
    $phone = null;
    $stmt = $conn->prepare("SELECT phone FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($phone);
    $stmt->fetch();
    return $phone;
}

// --------------------
// 🔹 5. שליפת עגלה לפי order_id
// --------------------
function fetchCartItems($conn, $orderId) {
    $items = [];
    if (!$orderId) return $items;

    $query = "
        SELECT c.menu_item_id, c.quantity, m.name, m.price
        FROM cart c
        JOIN menu m ON c.menu_item_id = m.id
        WHERE c.order_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}

// --------------------
// 🔹 6. בדיקה אם יש למשתמש הזמנה פתוחה
// --------------------
function findOpenOrder($conn, $userId) {
    $query = "SELECT id FROM orders WHERE user_id = ? AND status = 'InCart' LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();
    $orderId = null;
    $stmt->bind_result($orderId);
    if ($stmt->fetch()) {
        return $orderId;
    }
    return null;
}

// --------------------
// 🔹 7. יצירת הזמנה חדשה
// --------------------
function createNewOrder($conn, $userId) {
    $query = "INSERT INTO orders (user_id, status) VALUES (?, 'InCart')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        return $stmt->insert_id;
    }
    return null;
}

// --------------------
// 🔹 8. זיהוי משתמש והזמנה
// --------------------
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die("🔒 אין משתמש מחובר.");
}

// בדוק אם יש ORDER פתוח ב-DB (לא רק ב-SESSION)
$orderId = $_SESSION['order_id'] ?? findOpenOrder($conn, $userId);

// אם אין – צור חדש
if (!$orderId) {
    $orderId = createNewOrder($conn, $userId);
    echo "<script>console.log('🆕 נוצרה הזמנה חדשה: $orderId');</script>";
} else {
    echo "<script>console.log('🔁 הוזנה הזמנה קיימת: $orderId');</script>";
}
$_SESSION['order_id'] = $orderId;

// --------------------
// 🔹 9. שליפת הנתונים בפועל
// --------------------
$menuItems = fetchMenuItems($conn);
$cartItems = fetchCartItems($conn, $orderId);
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
      <div class="menu-grid"></div>
    </section>

    <!-- עגלת קניות -->
    <aside id="cart-section">
      <h2 class="section-title">עגלת הזמנות</h2>
      <ul class="cart-items"></ul>
      <p class="cart-total">סה"כ: ₪<span>0</span></p>
      <button class="checkout-button">סיום הזמנה</button>
      <div class="order-form-container" style="display:none;"></div>
    </aside>
  </main>
  <iframe name="hiddenFrame" style="display: none;"></iframe>


  <!-- 🔸 העברת נתונים ל-JavaScript -->
  <script>
    const menuItems = <?= json_encode($menuItems, JSON_UNESCAPED_UNICODE); ?>;
    const cartFromDB = <?= json_encode($cartItems, JSON_UNESCAPED_UNICODE); ?>;
    const user_id = <?= json_encode($userId); ?>;
    const user_phone = <?= json_encode(getUserPhone($conn, $userId)); ?>;
  </script>

  <!-- 🔸 JavaScript -->
  <script src="js/navbar.js"></script>
  <script src="js/menu.js"></script>
</body>
</html>
