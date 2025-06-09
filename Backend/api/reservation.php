<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../service/ReservationService.php';

header('Content-Type: application/json');

$service = new ReservationService($conn);

// ניתוח סוג הבקשה
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['table_id'], $data['user_id'], $data['reservation_date'], $data['reservation_time'])) {
            http_response_code(400);
            echo json_encode(["error" => "Missing required fields"]);
            exit;
        }

        $response = $service->createReservation(
            $data['table_id'],
            $data['user_id'],
            $data['reservation_date'],
            $data['reservation_time']
        );

        http_response_code($response["success"] ? 200 : 400);
        echo json_encode($response);
        break;

    case 'GET':
        // בדיקת זמינות שולחן לפי תאריך, שעה וכמות אנשים
        if (isset($_GET['check_availability'])) {
            if (!isset($_GET['date'], $_GET['time'], $_GET['people'])) {
                http_response_code(400);
                echo json_encode(["error" => "Missing parameters: date, time, people"]);
                exit;
            }

            $date = $_GET['date'];
            $time = $_GET['time'];
            $people = (int) $_GET['people'];

            $availableTable = $service->checkTableAvailability($date, $time, $people);

            if ($availableTable) {
                echo json_encode([
                    "available" => true,
                    "table_id" => $availableTable['id']
                ]);
            } else {
                echo json_encode([
                    "available" => false,
                    "message" => "אין שולחן פנוי בזמן המבוקש."
                ]);
            }

            exit;
        }

        // שליפת כל ההזמנות או לפי משתמש מסוים
        $userId = $_GET['user_id'] ?? null;

        $result = $userId ? $service->getReservationsByUser($userId)
                          : $service->getAllReservations();

        echo json_encode($result);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);

        if (!isset($data['table_id'], $data['user_id'], $data['reservation_date'])) {
            http_response_code(400);
            echo json_encode(["error" => "Missing identifiers for deletion"]);
            exit;
        }

        $deleted = $service->deleteReservation(
            $data['table_id'],
            $data['user_id'],
            $data['reservation_date']
        );

        http_response_code($deleted ? 200 : 400);
        echo json_encode([
            "success" => $deleted,
            "message" => $deleted ? "Reservation deleted" : "Deletion failed"
        ]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method Not Allowed"]);
}
