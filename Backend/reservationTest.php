<?php
require_once './config/db.php';
require_once './models/ReservationModel.php';
require_once './service/ReservationService.php';

echo "<h2>✅ התחברות ל־MySQL הצליחה!</h2>";

try {
    $reservationService = new ReservationService($conn);

    // === בדיקת זמינות שולחן לפי פרטים ===
    echo "<hr><h3>🔍 בדיקת זמינות שולחן:</h3>";
    $checkDate  = '2025-07-03';
    $checkTime  = '20:00:00';
    $checkPeople = 4;

    $availableTable = $reservationService->checkTableAvailability($checkDate, $checkTime, $checkPeople);
    if ($availableTable) {
        echo "✅ נמצא שולחן פנוי (ID: {$availableTable['id']}) ל־{$checkPeople} אנשים בתאריך $checkDate בשעה $checkTime";
    } else {
        echo "❌ אין שולחנות פנויים ל־$checkPeople אנשים בתאריך $checkDate בשעה $checkTime";
    }

    // === יצירת הזמנה חדשה ===
    echo "<hr><h3>📤 יצירת הזמנה חדשה:</h3>";
    $newReservation = [
        'user_id' => 4,
        'table_id' => 3,
        'reservation_date' => '2025-07-02',
        'reservation_time' => '21:00:00',
        'people' => 5
    ];

    $created = $reservationService->createReservation(
        $newReservation['table_id'],
        $newReservation['user_id'],
        $newReservation['reservation_date'],
        $newReservation['reservation_time'],
        $newReservation['people']
    );
    echo $created ? "✅ הזמנה נוצרה בהצלחה" : "❌ שגיאה ביצירת הזמנה";

    // === שליפת כל ההזמנות ===
    echo "<hr><h3>📥 כל ההזמנות הקיימות:</h3>";
    $reservations = $reservationService->getAllReservations();

    echo "<ul>";
    foreach ($reservations as $res) {
        echo "<li>🔹 Table: {$res['table_id']}, User: {$res['user_id']}, Date: {$res['reservation_date']} at {$res['reservation_time']} — 👥 {$res['people']} אנשים</li>";
    }
    echo "</ul>";

    // === שליפת הזמנות של משתמש מסוים ===
    echo "<hr><h3>👤 הזמנות של משתמש ID = 4:</h3>";
    $userReservations = $reservationService->getReservationsByUser(4);
    if (!empty($userReservations)) {
        echo "<ul>";
        foreach ($userReservations as $res) {
            echo "<li>🟢 שולחן {$res['table_id']} בתאריך {$res['reservation_date']} בשעה {$res['reservation_time']} — 👥 {$res['people']} אנשים</li>";
        }
        echo "</ul>";
    } else {
        echo "ℹ️ אין הזמנות למשתמש הזה.";
    }

    // === מחיקת ההזמנה החדשה ===
    echo "<hr><h3>🗑️ מחיקת ההזמנה החדשה:</h3>";
    $deleted = $reservationService->deleteReservation(
        $newReservation['table_id'],
        $newReservation['user_id'],
        $newReservation['reservation_date']
    );
    echo $deleted ? "🧹 ההזמנה נמחקה" : "❌ שגיאה במחיקת ההזמנה";

} catch (Exception $e) {
    echo "<hr><strong>שגיאה:</strong> " . $e->getMessage();
}
