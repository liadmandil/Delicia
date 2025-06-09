<?php
// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle preflight (OPTIONS) requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once  '../config/db.php';
require_once  '../controllers/MenuController.php';

header('Content-Type: application/json');
$controller = new MenuController($conn);

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $menuItems = $controller->getMenu();
            echo json_encode(["success" => true, "data" => $menuItems]);
            break;

        case 'POST':
            $name = $_GET['name'] ?? null;
            $description = $_GET['description'] ?? '';
            $price = $_GET['price'] ?? null;
            $available = $_GET['available'] ?? 1;
            $image_url = $_GET['image_url'] ?? '';
            $category = $_GET['category'] ?? '';

            if (!$name || !$price) throw new Exception("Missing required fields: name or price");

            $controller->addMenuItem($name, $description, $price, $available, $image_url, $category);
            echo json_encode(["success" => true, "message" => "Menu item added successfully"]);
            break;

        case 'DELETE':
            parse_str(file_get_contents("php://input"), $_DELETE);
            $id = $_DELETE['id'] ?? null;

            if (!$id) throw new Exception("Missing ID");

            $controller->deleteMenuItem($id);
            echo json_encode(["success" => true, "message" => "Menu item deleted successfully"]);
            break;

        default:
            http_response_code(405);
            echo json_encode(["success" => false, "message" => "Method Not Allowed"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
