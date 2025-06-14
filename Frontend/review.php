<?php
// חיבור למסד הנתונים
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "delicia_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // כל הקוד של קבלת מידע ושליחת שאילתה עובר לפה
    // טיפול בקובץ שהועלה
    $file_path = NULL;
    if (isset($_FILES['file-upload']) && $_FILES['file-upload']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = basename($_FILES['file-upload']['name']);
        $target_file = $upload_dir . time() . "_" . $file_name;
        if (move_uploaded_file($_FILES['file-upload']['tmp_name'], $target_file)) {
            $file_path = $target_file;
        }
    }

    // קבלת הערכים מהטופס
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $rating = intval($_POST['rating']);
    $visit_time = $conn->real_escape_string($_POST['visit-time']);
    $meal_type = $conn->real_escape_string($_POST['meal-type']);

    // טיפול בשדות checkbox (שיכולים להיות מרובים)
    $improvements = "";
    if (isset($_POST['improvements'])) {
        if (is_array($_POST['improvements'])) {
            $improvements = $conn->real_escape_string(implode(", ", $_POST['improvements']));
        } else {
            $improvements = $conn->real_escape_string($_POST['improvements']);
        }
    }

    $favorite_dish = $conn->real_escape_string($_POST['favorite-dish']);
    $comments = $conn->real_escape_string($_POST['comments']);

    // הכנסה לטבלה
    $sql = "INSERT INTO reviews 
        (name, email, rating, visit_time, meal_type, improvements, favorite_dish, comments, file_path) 
        VALUES 
        ('$name', '$email', $rating, '$visit_time', '$meal_type', '$improvements', '$favorite_dish', '$comments', " . ($file_path ? "'$file_path'" : "NULL") . ")";

    if ($conn->query($sql) === TRUE) {
        echo "תודה על המשוב!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        padding: 30px;
        background-color: #f2f2f2;
        }
        form {
        margin-top: 150px !important;
        width: 100%;
        max-width: 600px;
        margin: 50px auto 20px auto;
        background-color: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .form-group {
        margin-bottom: 15px;
        }
        label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
        }
        input, select, textarea {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        }
        .submit-btn, .show-btn {
        text-align: center;
        margin-top: 20px;
        }
        button {
        padding: 10px 25px;
        font-size: 16px;
        cursor: pointer;
        }
        table {
        margin: 30px auto;
        border-collapse: collapse;
        width: 90%;
        max-width: 1000px;
        background: white;
        direction: rtl;
        }
        th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: right;
        }
        th {
        background-color: #f4f4f4;
        }
        td {
        max-width: 300px;        /* רוחב מקסימלי של התא */
        white-space: normal;     /* מאפשר שבירת שורות */
        word-wrap: break-word;   /* שובר מילים ארוכות לשורות */
        overflow-wrap: break-word; /* מוודא שבירת מילים */
        max-height: 5em;         /* גובה מקסימלי, כ-3-4 שורות */
        overflow: hidden;        /* מסתיר טקסט מעבר לגובה */
        }
        .checkbox-inline {
            display: inline-flex;
            align-items: center;
            margin-right: 15px;
        }
        textarea {
            resize: none;
        }
    </style>
    <link rel="stylesheet" href="./css/menu.css">
</head>
<body dir="rtl">
    <div id="navbar"></div>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">שם מלא:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">דוא"ל:</label><br>
        <input type="email" id="email" name="email" placeholder="example@exmaple.com"><br><br>

        <label for="rating">ציון השירות (1-10):</label><br>
        <input type="number" id="rating" name="rating" min="1" max="10"><br><br>

        <label for="visit-time">שעת הביקור:</label><br>
        <input type="time" id="visit-time" name="visit-time"><br><br>

        <label for="meal-type">סוג ארוחה:</label><br>
        <select id="meal-type" name="meal-type">
            <option value="מרק">מרק</option>
            <option value="סושי">סושי</option>
            <option value="נודלס">נודלס</option>
            <option value="קינוחים">קינוחים</option>
        </select><br><br>

        <label for="improvements[]">מה לשפר? (בחר אופציות):</label><br>
        
        <div>
            <label class="checkbox-inline">
                <input type="checkbox" name="improvements[]" value="טעם"> טעם
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" name="improvements[]" value="הגשה"> הגשה
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" name="improvements[]" value="שירות"> שירות
            </label>
        </div>
        <br>

        <label for="favorite-dish">מנה מועדפת:</label><br>
        <input list="dishes" id="favorite-dish" name="favorite-dish">
        <datalist id="dishes">
            <option value="מרק מיסו">
            <option value="סושי רול">
            <option value="נודלס מוקפצים">
            <option value="טירמיסו אסייתי">
        </datalist><br><br>

        <label for="comments">תגובות והערות:</label><br>
        <textarea id="comments" name="comments" rows="5" cols="40" placeholder="מה חשבת על החוויה?"></textarea><br><br>

        <label for="file-upload">העלה תמונה או מסמך:</label><br>
        <input type="file" id="file-upload" name="file-upload"><br><br>

        <button type="submit">שלח משוב</button>
    </form>
    <script src="js/navbar.js"></script>
    <script src="js/review.js"></script>
</body>
</html>