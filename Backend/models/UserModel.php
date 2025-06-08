<?php

class UserModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUser($name, $city, $address, $phone, $email, $role) {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, city, address, phone, email, role) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $city, $address, $phone, $email, $role]);
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function userPhoneExists($phone) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        return $stmt->fetchColumn() > 0;
    }

    public function userExists($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}
