<?php
require_once './config/db.php';
require_once './models/ReservationModel.php';
require_once './service/ReservationService.php';

echo "<h2>✅ התחברות ל־MySQL הצליחה!</h2>";

try {
    $reservationService = new ReservationService($conn);

    echo "<hr><h3>📤 יצירת הזמנה חדשה:</h3>";
    $newReservation = [
        'user_id' => 4, // ודא שיש משתמש עם ID כזה
        'reservation_date' => '2025-07-01',
        'num_guests' => 2,
        'notes' => 'שולחן זוגי ליד החלון'
    ];

    $created = $reservationService->createReservation(
        $newReservation['user_id'],
        $newReservation['reservation_date'],
        $newReservation['num_guests'],
        $newReservation['notes']
    );

    echo $created ? "✅ הזמנה נוצרה בהצלחה" : "❌ שגיאה ביצירת הזמנה";

    echo "<hr><h3>📥 שליפת כל ההזמנות:</h3>";
    $reservations = $reservationService->getAllReservations();
    echo "<ul>";
    foreach ($reservations as $res) {
        echo "<li>🔹 ID: {$res['id']}, Date: {$res['reservation_date']}, Guests: {$res['num_guests']}, Notes: {$res['notes']}</li>";
    }
    echo "</ul>";

    echo "<hr><h3>🧹 מחיקת הזמנה אחרונה:</h3>";
    if (!empty($reservations)) {
        $lastId = end($reservations)['id'];
        $deleted = $reservationService->deleteReservation($lastId);
        echo $deleted ? "🗑️ הזמנה עם ID $lastId נמחקה" : "❌ שגיאה במחיקה";
    } else {
        echo "ℹ️ אין הזמנות למחיקה.";
    }

} catch (Exception $e) {
    echo "<hr><strong>שגיאה:</strong> " . $e->getMessage();
}
?>