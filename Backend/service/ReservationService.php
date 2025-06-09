<?php
require_once __DIR__ . '/../models/ReservationModel.php';

class ReservationService {
    private $reservationModel;

    public function __construct($db) {
        $this->reservationModel = new ReservationModel($db);
    }

    public function createReservation($tableId, $userId, $date, $time, $people) {
        if ($this->reservationModel->isUserAlreadyBooked($userId, $date, $time)) {
            return ["success" => false, "message" => "User already has a reservation at that time"];
        }

        if ($this->reservationModel->isTableTaken($tableId, $date, $time)) {
            return ["success" => false, "message" => "Table is already reserved at that time"];
        }

        $success = $this->reservationModel->addReservation($tableId, $userId, $date, $time, $people);
        return ["success" => $success, "message" => $success ? "Reservation created" : "Failed to create reservation"];
    }

    public function getAllReservations() {
        return $this->reservationModel->getAllReservations();
    }

    public function getReservationsByUser($userId) {
        return $this->reservationModel->getReservationsByUser($userId);
    }

    public function deleteReservation($tableId, $userId, $date) {
        return $this->reservationModel->deleteReservation($tableId, $userId, $date);
    }

    public function checkTableAvailability($date, $time, $people) {
        return $this->reservationModel->findAvailableTable($date, $time, $people);
    }

}