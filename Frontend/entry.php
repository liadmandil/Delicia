<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "Mandil01!!", "delicia_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$showRegister = false;
$phonePrefill = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['check_phone'])) {
        $phone = $_POST['phone'];
        $phonePrefill = $phone;

        $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userId);
            $stmt->fetch();

            $stmt2 = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND status = 'InCart'");
            $stmt2->bind_param("i", $userId);
            $stmt2->execute();
            $result = $stmt2->get_result();

            if ($row = $result->fetch_assoc()) {
                $orderId = $row['id'];
            } else {
                $stmt3 = $conn->prepare("INSERT INTO orders (user_id, status) VALUES (?, 'InCart')");
                $stmt3->bind_param("i", $userId);
                $stmt3->execute();
                $orderId = $stmt3->insert_id;
            }

            $_SESSION['user_id'] = $userId;
            $_SESSION['order_id'] = $orderId;
            header("Location: menu.php");
            exit();
        } else {
            $showRegister = true;
        }

    } elseif (isset($_POST['register_user'])) {
        $name    = $_POST['name'];
        $email   = $_POST['email'];
        $city    = $_POST['city'];
        $address = $_POST['address'];
        $phone   = $_POST['phone'];

        $stmt = $conn->prepare("INSERT INTO users (name, email, city, address, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $city, $address, $phone);
        $stmt->execute();
        $userId = $stmt->insert_id;

        $stmt2 = $conn->prepare("INSERT INTO orders (user_id, status) VALUES (?, 'InCart')");
        $stmt2->bind_param("i", $userId);
        $stmt2->execute();
        $orderId = $stmt2->insert_id;

        $_SESSION['user_id'] = $userId;
        $_SESSION['order_id'] = $orderId;

        header("Location: menu.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>כניסה ל־Delicia</title>
  <link rel="stylesheet" href="/Delicia/Delicia/Frontend/css/menu.css" />
</head>
<body>
  <div id="navbar"></div>

  <main style="max-width: 400px; margin: auto; padding: 40px;">
    <h2>הזדהות לפי מספר טלפון</h2>
    <form method="post">
      <input type="hidden" name="check_phone" value="1">
      <label>מספר טלפון:</label>
      <input type="text" name="phone" required pattern="05\d{8}" placeholder="למשל: 0521234567" value="<?= htmlspecialchars($phonePrefill) ?>">
      <button type="submit">המשך</button>
    </form>

    <div id="register-form" <?= $showRegister ? '' : 'class="hidden"' ?>>
      <h3>לא מצאנו אותך... בוא נכיר 😊</h3>
      <form method="post">
        <input type="hidden" name="register_user" value="1">

        <label>שם מלא:</label>
        <input type="text" name="name" required>

        <label>אימייל:</label>
        <input type="email" name="email" required>

        <label>עיר:</label>
        <input type="text" name="city" required>

        <label>כתובת:</label>
        <input type="text" name="address" required>

        <label>מספר טלפון:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($phonePrefill) ?>" readonly>

        <button type="submit">צור משתמש והמשך</button>
      </form>
    </div>
  </main>

  <style>
    .hidden { display: none; }
    input, button { width: 100%; margin: 6px 0; padding: 8px; }
    form { margin-bottom: 20px; }
  </style>

  <script>
    const shouldShowRegister = <?= $showRegister ? 'true' : 'false' ?>;
  </script>
  <script src="/Delicia/Delicia/Frontend/js/navbar.js"></script>
  <script src="/Delicia/Delicia/Frontend/js/entry.js"></script>
</body>
</html>
