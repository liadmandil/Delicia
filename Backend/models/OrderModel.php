<?php
class OrderModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getOrderById($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCartItemsByOrderId($orderId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                c.menu_item_id,
                c.quantity,
                m.name AS menu_name,
                m.description,
                m.price,
                m.image_url
            FROM cart c
            JOIN menu m ON c.menu_item_id = m.id
            WHERE c.order_id = ?

        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOpenOrderByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = ? AND status = 'InCart' LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrder($userId) {
        $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total_price, status, delivery_address) VALUES (?, 0, 'InCart', NULL)");
        $stmt->execute([$userId]);
        return $this->pdo->lastInsertId();
    }

    public function addOrUpdateCartItem($orderId, $menuItemId, $quantity) {
    // האם קיים פריט כזה כבר בעגלה?
        $stmt = $this->pdo->prepare("SELECT quantity FROM cart WHERE order_id = ? AND menu_item_id = ?");
        $stmt->execute([$orderId, $menuItemId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            $stmt = $this->pdo->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE order_id = ? AND menu_item_id = ?");
            $stmt->execute([$newQty, $orderId, $menuItemId]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO cart (order_id, menu_item_id, quantity, updated_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$orderId, $menuItemId, $quantity]);
        }
    }


    public function getMenuItemPrice($menuItemId) {
        $stmt = $this->pdo->prepare("SELECT price FROM menu WHERE id = ?");
        $stmt->execute([$menuItemId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['price'] : 0;
    }


    public function updateOrderTotalPrice($orderId, $addAmount) {
        $stmt = $this->pdo->prepare("UPDATE orders SET total_price = total_price + ? WHERE id = ?");
        $stmt->execute([$addAmount, $orderId]);
    }


    public function deleteFromCart($orderId, $menuItemId) {
    // שלוף את הכמות הנוכחית
    $stmt = $this->pdo->prepare("SELECT quantity FROM cart WHERE order_id = ? AND menu_item_id = ?");
    $stmt->execute([$orderId, $menuItemId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) return false;

    if ($row['quantity'] > 1) {
        // הפחתה של 1 מהכמות
        $stmt = $this->pdo->prepare("UPDATE cart SET quantity = quantity - 1, updated_at = NOW() WHERE order_id = ? AND menu_item_id = ?");
        $stmt->execute([$orderId, $menuItemId]);
    } else {
        // אם הכמות הייתה 1 — מחק את השורה כולה
        $stmt = $this->pdo->prepare("DELETE FROM cart WHERE order_id = ? AND menu_item_id = ?");
        $stmt->execute([$orderId, $menuItemId]);
    }

    return true;
}


    public function removeOneFromCart($orderId, $menuItemId) {
    // שלוף את הכמות הנוכחית
    $stmt = $this->pdo->prepare("SELECT quantity FROM cart WHERE order_id = ? AND menu_item_id = ?");
    $stmt->execute([$orderId, $menuItemId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) return false;

    if ($row['quantity'] > 1) {
        // הפחתה של 1
        $stmt = $this->pdo->prepare("UPDATE cart SET quantity = quantity - 1 WHERE order_id = ? AND menu_item_id = ?");
        $stmt->execute([$orderId, $menuItemId]);
    } else {
        // מחיקה לגמרי
        $this->deleteFromCart($orderId, $menuItemId);
    }

    return true;
}


public function getOrCreateOpenOrder($userId) {
    $order = $this->getOpenOrderByUserId($userId);

    if ($order) {
        return $order;
    }

    $newOrderId = $this->createOrder($userId);
    return $this->getOrderById($newOrderId);
}


}
