<?php
session_start();
require_once '../config/db.php'; // קובץ שמכיל $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $menuItemId = $_POST['menu_item_id'] ?? null;
    $userId = $_POST['user_id'] ?? null;

    if (!$menuItemId || !$userId) {
        exit("Missing data");
    }

    // שליפת ההזמנה הפתוחה
    $stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND status = 'InCart' LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($orderId);
    $stmt->fetch();
    $stmt->close();

    if (!$orderId) {
        // אין הזמנה פתוחה? צור חדשה
        $stmt = $conn->prepare("INSERT INTO orders (user_id, status, total_price) VALUES (?, 'InCart', 0)");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        $_SESSION['order_id'] = $orderId;
    }

    if ($action === "add") {
        // האם המוצר כבר בעגלה?
        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE order_id = ? AND menu_item_id = ?");
        $stmt->bind_param("ii", $orderId, $menuItemId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // קיים? עדכן כמות
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE order_id = ? AND menu_item_id = ?");
            $stmt->bind_param("ii", $orderId, $menuItemId);
            $stmt->execute();
        } else {
            // לא קיים? הוסף חדש
            $stmt = $conn->prepare("INSERT INTO cart (order_id, menu_item_id, quantity) VALUES (?, ?, 1)");
            $stmt->bind_param("ii", $orderId, $menuItemId);
            $stmt->execute();
        }
    }
}
