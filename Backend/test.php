<?php
require_once './config/db.php';
require_once './models/OrderModel.php';
require_once './service/OrderService.php'; 
require_once './controllers/MenuController.php';
require_once './models/MenuModel.php';
require_once './controllers/OrderController.php'; // נוספה שורה זו

echo "<h2>✅ ההתחברות ל־MySQL הצליחה!</h2>";

try {
    // === שליפת מנות קיימות ===
    echo "<hr><h3>📋 מנות זמינות:</h3>";

    $menuController = new MenuController($conn);
    $menuItems = $menuController->getMenu();

    echo "<ul>";
    foreach ($menuItems as $item) {
        echo "<li><strong>{$item['id']}</strong> - {$item['name']} ({$item['category']}) - {$item['price']} ₪</li>";
    }
    echo "</ul>";

    // === הוספת מנה חדשה ===
    echo "<hr><h3>➕ בדיקת הוספה לתפריט:</h3>";

    $newItem = [
        "name" => "מוקפץ בדיקה",
        "description" => "מנה לבדיקה מתוך test.php",
        "price" => 29.90,
        "available" => 1,
        "image_url" => "images/test_dish.jpg",
        "category" => "מנות ווק"
    ];

    try {
        $menuController->addMenuItem(
            $newItem['name'],
            $newItem['description'],
            $newItem['price'],
            $newItem['available'],
            $newItem['image_url'],
            $newItem['category']
        );
        echo "<p style='color:green;'>✅ מנה חדשה נוספה: {$newItem['name']}</p>";
    } catch (Exception $e) {
        echo "<p style='color:red;'>❌ שגיאה בהוספה: " . $e->getMessage() . "</p>";
    }

    // === מחיקת המנה שהוספנו (אם קיימת) ===
    echo "<hr><h3>🗑️ בדיקת מחיקה מהתפריט:</h3>";

    $menuItems = $menuController->getMenu(); // נטען שוב
    $toDeleteId = null;

    foreach ($menuItems as $item) {
        if ($item['name'] === $newItem['name'] && $item['category'] === $newItem['category']) {
            $toDeleteId = $item['id'];
            break;
        }
    }

    if ($toDeleteId) {
        try {
            $menuController->deleteMenuItem($toDeleteId);
            echo "<p style='color:green;'>✅ המנה עם ID $toDeleteId נמחקה מהתפריט</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;'>❌ שגיאה במחיקה: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color:orange;'>⚠️ לא נמצאה המנה לבדיקה למחיקה</p>";
    }

    // === בדיקת הזמנות (קיים אצלך) ===
    echo "<hr><h3>📦 בדיקת הזמנות:</h3>";

    $orderService = new OrderService($conn);
    for ($orderId = 21; $orderId <= 23; $orderId++) {
        echo "<h4>הזמנה מספר $orderId:</h4>";
        $data = $orderService->getFullOrder($orderId);

        if (isset($data['error'])) {
            echo "<p style='color:red;'>שגיאה: {$data['error']}</p>";
        } else {
            echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
        }
    }

    // === בדיקת addToCart ===
    echo "<hr><h3>🛒 בדיקת addToCart:</h3>";

    $userId = 1;
    $menuItemId = $menuItems[0]['id']; // מנה קיימת
    $quantity = 1;

    $addResult = $orderService->addToCart($userId, $menuItemId, $quantity);
    echo "<p>✅ פריט נוסף להזמנה פתוחה של משתמש $userId:</p>";
    echo "<pre>" . json_encode($addResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

    // === מחיקה מהעגלה ===
    echo "<hr><h3>🧺 מחיקה מהעגלה:</h3>";

    $orderIdToDeleteFrom = $addResult['order_id'];
    $menuItemIdToDelete = $menuItemId;

    $deleteResult = $orderService->deleteFromCart($orderIdToDeleteFrom, $menuItemIdToDelete);
    echo "<p>ניסיון מחיקה של מנה $menuItemIdToDelete מהזמנה $orderIdToDeleteFrom:</p>";
    echo $deleteResult ? "<span style='color:green;'>✅ נמחק בהצלחה</span>" : "<span style='color:red;'>❌ לא נמחק</span>";

    
    // === מצב עדכני להזמנה ===
    $updatedOrder = $orderService->getFullOrder($orderIdToDeleteFrom);
    echo "<h4>📦 מצב עדכני להזמנה לאחר מחיקה:</h4>";
    echo "<pre>" . json_encode($updatedOrder, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";


    // === בדיקת הזמנה פתוחה או יצירת חדשה ===
    echo "<hr><h3>📬 בדיקת הזמנה פתוחה של המשתמש (getOrCreateOpenOrder):</h3>";

    $orderController = new OrderController($conn);

    try {
        $openOrder = $orderController->getOrCreateOpenOrder(2);
        echo "<p>📦 2:</p>";
        echo "<pre>" . json_encode($openOrder, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    } catch (Exception $e) {
        echo "<p style='color:red;'>❌ שגיאה בשליפת הזמנה פתוחה: {$e->getMessage()}</p>";
    }

} catch (Exception $e) {
    echo "<h2>❌ שגיאה:</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
?>
