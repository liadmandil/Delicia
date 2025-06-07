<?php
require_once __DIR__ . '/../models/MenuModel.php';

class MenuService {
    private $model;

    public function __construct($pdo) {
        $this->model = new MenuModel($pdo);
    }

    public function getAllMenuItems() {
        return $this->model->getAllMenuItems();
    }

    public function addMenuItem($name, $description, $price, $available, $image_url, $category) {
        if ($this->model->menuItemExists($name, $category)) {
            throw new Exception("המנה כבר קיימת בקטגוריה הזו");
        }

        return $this->model->addMenuItem($name, $description, $price, $available, $image_url, $category);
    }

    public function deleteMenuItem($id) {
        if (!$this->model->menuItemByIdExists($id)) {
            throw new Exception("המנה לא קיימת");
        }

        return $this->model->deleteMenuItem($id);
    }
}
