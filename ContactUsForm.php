<?php
// חיבור למסד הנתונים
$host = 'sql211.byethost22.com';
$port = '3306';
$db = 'b22_39125661_delicia_db';
$user = 'b22_39125661';
$pass = 's@Ts7AP2L.pwPHx';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// טיפול בשליחה (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $phone = $conn->real_escape_string($_POST["phone"]);
    $subject = $conn->real_escape_string($_POST["subject"]);
    $category = $conn->real_escape_string($_POST["category"]);
    $message = $conn->real_escape_string($_POST["message"]);

    $sql = "INSERT INTO contact_us (full_name, email, phone, subject, category, message)
            VALUES ('$fullName', '$email', '$phone', '$subject', '$category', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('הטופס נשלח בהצלחה!');
                window.location.href = 'ContactUsForm.php';
              </script>";
        exit;
    } else {
        echo "שגיאה בשליחה: " . $conn->error;
    }
}

// אם קיים GET עם ?show=1, נציג את הפניות הקודמות
$showEntries = isset($_GET['show']) && $_GET['show'] == '1';

?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title>צור קשר עם התמיכה</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./css/menu.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background-color: #f2f2f2;
      font-size: 16px;
      color: black;
    }
    form {
      margin-top: 100px !important;
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
    #showEntriesForm {
      margin-top: 0px !important;
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
      resize: none;
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
    
  </style>
</head>
<body>
<div id="navbar"></div>
<form method="POST" action="ContactUsForm.php">
  <div class="form-group">
    <label>שם מלא:</label>
    <input type="text" name="name" required />
  </div>

  <div class="form-group">
    <label>אימייל:</label>
    <input type="email" name="email" required />
  </div>

  <div class="form-group">
    <label>טלפון:</label>
    <input type="tel" name="phone" pattern="05\d{8}" placeholder="למשל: 0521234567" />
  </div>

  <div class="form-group">
    <label>נושא הפנייה:</label>
    <input type="text" name="subject" />
  </div>

  <div class="form-group">
    <label>בחר קטגוריה:</label>
    <select name="category">
      <option value="technical">בעיה טכנית</option>
      <option value="account">חשבון משתמש</option>
      <option value="payment">תשלומים</option>
      <option value="other">אחר</option>
    </select>
  </div>

  <div class="form-group">
    <label>הודעה:</label>
    <textarea name="message" rows="4" required></textarea>
  </div>

  <div class="submit-btn">
    <button type="submit">סיים ושלח</button>
  </div>
</form>

<!-- כפתור להציג את הפניות הקודמות -->
<div class="show-btn">
  <form id="showEntriesForm" method="GET" action="ContactUsForm.php">
    <button type="submit" name="show" value="1">הצג פניות קודמות</button>
  </form>
</div>

<?php if ($showEntries): ?>
  <?php
    $result = $conn->query("SELECT full_name, email, phone, subject, category, message FROM contact_us ORDER BY id DESC");
    if ($result && $result->num_rows > 0):
  ?>
    <table>
      <thead>
        <tr>
          <th>שם מלא</th>
          <th>אימייל</th>
          <th>טלפון</th>
          <th>נושא</th>
          <th>קטגוריה</th>
          <th>הודעה</th>
        </tr>
      </thead>
      <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?=htmlspecialchars($row['full_name'])?></td>
          <td><?=htmlspecialchars($row['email'])?></td>
          <td><?=htmlspecialchars($row['phone'])?></td>
          <td><?=htmlspecialchars($row['subject'])?></td>
          <td><?=htmlspecialchars($row['category'])?></td>
          <td><?=nl2br(htmlspecialchars($row['message']))?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="text-align:center; margin-top: 30px; color:white">לא נמצאו פניות קודמות</p>
  <?php endif; ?>
<?php endif; ?>

<script src="./js/navbar.js"></script>
<script>loadNavBar();</script>
</body>
</html>
