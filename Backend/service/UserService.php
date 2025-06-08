<?php
require_once __DIR__ . '/../models/UserModel.php';

class UserService {
    private $model;

    public function __construct($pdo) {
        $this->model = new UserModel($pdo);
    }

    public function getAllUsers() {
        return $this->model->getAllUsers();
    }

    public function addUser($name, $city, $address, $phone, $email, $role) {
        if ($this->model->userPhoneExists($phone)) {
            throw new Exception("המנה כבר קיימת בקטגוריה הזו");
        }

        return $this->model->addUser($name, $city, $address, $phone, $email, $role);
    }

    public function deleteUser($id) {
        if (!$this->model->userExists($id)) {
            throw new Exception("המשתמש לא קיים");
        }

        return $this->model->deleteUser($id);
    }
}
