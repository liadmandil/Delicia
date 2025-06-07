<?php
require_once __DIR__ . '/../service/MenuService.php';

class MenuController {
    private $menuService;

    public function __construct($pdo) {
        $this->menuService = new MenuService($pdo);
    }

    public function getMenu(): mixed {
        return $this->menuService->getAllMenuItems();
    }

    public function addMenuItem($name, $description, $price, $available, $image_url, $category) {
        return $this->menuService->addMenuItem($name, $description, $price, $available, $image_url, $category);
    }

    public function deleteMenuItem($id) {
        return $this->menuService->deleteMenuItem($id);
    }
}
