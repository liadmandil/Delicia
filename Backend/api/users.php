<?php
require_once  '../config/db.php';
require_once  '/../controllers/UserController.php';

header('Content-Type: application/json');
$controller = new UserController($conn);

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $menuItems = $controller->getUsers();
            echo json_encode(["success" => true, "data" => $menuItems]);
            break;

        case 'POST':
            $name = $_GET['name'] ?? null;
            $city = $_GET['city'] ?? '';
            $address = $_GET['address'] ?? null;
            $phone = $_GET['phone'] ?? 1;
            $email = $_GET['email'] ?? '';
            $role = $_GET['role'] ?? '';

            if (!$name || !$phone) throw new Exception("Missing required fields: name or phone");

            $controller->addUser($name, $city, $address, $phone, $email, $role);
            echo json_encode(["success" => true, "message" => "User added successfully"]);
            break;

        case 'DELETE':
            parse_str(file_get_contents("php://input"), $_DELETE);
            $id = $_DELETE['id'] ?? null;

            if (!$id) throw new Exception("Missing ID");

            $controller->deleteUser($id);
            echo json_encode(["success" => true, "message" => "User deleted successfully"]);
            break;

        default:
            http_response_code(405);
            echo json_encode(["success" => false, "message" => "Method Not Allowed"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
