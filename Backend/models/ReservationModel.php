<?php
class ReservationModel {
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }

    public function addReservation($tableId, $userId, $date, $time) {
        $stmt = $this->pdo->prepare("INSERT INTO reservations (table_id, user_id, reservation_date, reservation_time)
                                     VALUES (?, ?, ?, ?)");
        return $stmt->execute([$tableId, $userId, $date, $time]);
    }

    public function isUserAlreadyBooked($userId, $date, $time) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations
                                     WHERE user_id = ? AND reservation_date = ? AND reservation_time = ?");
        $stmt->execute([$userId, $date, $time]);
        return $stmt->fetchColumn() > 0;
    }

    public function isTableTaken($tableId, $date, $time) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations
                                     WHERE table_id = ? AND reservation_date = ? AND reservation_time = ?");
        $stmt->execute([$tableId, $date, $time]);
        return $stmt->fetchColumn() > 0;
    }

    public function getAllReservations() {
        $stmt = $this->pdo->query("SELECT * FROM reservations");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationsByUser($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM reservations WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteReservation($tableId, $userId, $date) {
        $stmt = $this->pdo->prepare("DELETE FROM reservations WHERE table_id = ? AND user_id = ? AND reservation_date = ?");
        return $stmt->execute([$tableId, $userId, $date]);
    }
}