<?php
require_once __DIR__ . '/../service/UserService.php';

class UserController {
    private $userService;

    public function __construct($pdo) {
        $this->userService = new UserService($pdo);
    }

    public function getUsers(): mixed {
        return $this->userService->getAllUsers();
    }

    public function addUser($name, $city, $address, $phone, $email, $role) {
        return $this->userService->addUser($name, $city, $address, $phone, $email, $role);
    }

    public function deleteUser($id) {
        return $this->userService->deleteUser($id);
    }
}
