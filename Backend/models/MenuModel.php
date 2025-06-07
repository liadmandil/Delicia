<?php

class MenuModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllMenuItems() {
        $stmt = $this->pdo->query("SELECT * FROM menu");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMenuItem($name, $description, $price, $available, $image_url, $category) {
        $stmt = $this->pdo->prepare("INSERT INTO menu (name, description, price, available, image_url, category) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $description, $price, $available, $image_url, $category]);
    }

    public function deleteMenuItem($id) {
        $stmt = $this->pdo->prepare("DELETE FROM menu WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function menuItemExists($name, $category) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM menu WHERE name = ? AND category = ?");
        $stmt->execute([$name, $category]);
        return $stmt->fetchColumn() > 0;
    }

    public function menuItemByIdExists($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM menu WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}
