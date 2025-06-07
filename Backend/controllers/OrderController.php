<?php
require_once __DIR__ . '/../service/OrderService.php';

class OrderController {
    private $orderService;

    public function __construct($pdo) {
        $this->orderService = new OrderService($pdo);
    }

    public function getOrCreateOpenOrder($userId) {
        return $this->orderService->getOrCreateOpenOrder($userId);
    }

    public function addToCart($userId, $menuItemId, $quantity) {
    return $this->orderService->addToCart($userId, $menuItemId, $quantity);
}

    public function deleteFromCart($orderId, $menuItemId) {
    return $this->orderService->deleteFromCart($orderId, $menuItemId);
}

}
