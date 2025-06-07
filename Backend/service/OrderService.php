<?php
require_once __DIR__ . '/../models/OrderModel.php';

class OrderService {
    private $orderModel;

    public function __construct($pdo) {
        $this->orderModel = new OrderModel($pdo);
    }

    public function getFullOrder($orderId) {
        $order = $this->orderModel->getOrderById($orderId);
        if (!$order) {
            return ['error' => 'Order not found'];
        }

        $items = $this->orderModel->getCartItemsByOrderId($orderId);
        return [
            'order' => $order,
            'items' => $items
        ];
    }

    public function addToCart($userId, $menuItemId, $quantity) {
        // 1. חיפוש הזמנה פתוחה
        $order = $this->orderModel->getOpenOrderByUserId($userId);
    
        // 2. אם אין – צור הזמנה חדשה
        if (!$order) {
            $orderId = $this->orderModel->createOrder($userId);
        } else {
            $orderId = $order['id'];
        }
    
        // 3. הוסף את המוצר לעגלה (או עדכן כמות אם כבר קיים)
        $this->orderModel->addOrUpdateCartItem($orderId, $menuItemId, $quantity);
    
        // 4. שלוף מחיר המנה ועדכן את סכום ההזמנה
        $price = $this->orderModel->getMenuItemPrice($menuItemId);
        $this->orderModel->updateOrderTotalPrice($orderId, $price * $quantity);
    
        return ['success' => true, 'order_id' => $orderId];
    }


    public function deleteFromCart($orderId, $menuItemId) {
        $price = $this->orderModel->getMenuItemPrice($menuItemId);
        $this->orderModel->updateOrderTotalPrice($orderId, $price * -1);
        return $this->orderModel->deleteFromCart($orderId, $menuItemId);
    }


    public function getOrCreateOpenOrder($userId) {
    $order = $this->orderModel->getOpenOrderByUserId($userId);

    if ($order) {
        return $order;
    }

    $newOrderId = $this->orderModel->createOrder($userId);
    return $this->orderModel->getOrderById($newOrderId);
}


}
