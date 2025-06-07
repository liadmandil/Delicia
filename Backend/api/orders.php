<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/OrderController.php';

header('Content-Type: application/json');

$controller = new OrderController($conn);
$action = $_GET['action'] ?? 'get';


// 🔎 שליפת הזמנה לפי מזהה
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get') {
    $orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

    if ($orderId <= 0) {
        echo json_encode(['error' => 'order_id is required']);
        exit;
    }

    try {
        $result = $controller->getFullOrder($orderId);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// 🛒 הוספה לעגלה
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'addToCart') {
    $userId = $_POST['user_id'] ?? null;
    $menuItemId = $_POST['menu_item_id'] ?? null;
    $quantity = $_POST['quantity'] ?? null;

    if (!$userId || !$menuItemId || !$quantity) {
        echo json_encode(['error' => 'Missing parameters']);
        exit;
    }

    try {
        $result = $controller->addToCart($userId, $menuItemId, $quantity);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// 🧺 שליפת הזמנה פתוחה או יצירת חדשה
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'getOpenOrder') {
    $userId = $_GET['user_id'] ?? null;

    if (!$userId) {
        echo json_encode(['error' => 'user_id is required']);
        exit;
    }

    try {
        $result = $controller->getOrCreateOpenOrder($userId);
        echo json_encode(['success' => true, 'order' => $result], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// ❌ פעולה לא מוכרת
else {
    echo json_encode(['error' => 'Invalid action']);
}
