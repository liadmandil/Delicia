<?php
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $date = $_POST["date"];

    $today = new DateTime();
    $reservationDate = new DateTime($date);
    $interval = $today->diff($reservationDate);
    $daysLeft = $interval->days;

    if ($reservationDate < $today) {
        $message = "<p style='color:red;'>התאריך שהוזן עבר. נא לבחור תאריך עתידי.</p>";
    } else {
        $message = "<h3>תודה $name!</h3><p>נותרו <strong>$daysLeft</strong> ימים להזמנה שלך.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>דליסיה - מסעדה אסיאתית</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
    body {
      margin: 0;
      background-color: #000;
      color: white;
      font-family: Arial, sans-serif;
      direction: rtl;
    }

    header {
      background-color: #111;
      padding: 20px;
      text-align: center;
    }

    h1 {
      margin: 0;
      font-size: 2.5em;
    }

    main {
      padding: 40px 20px;
      text-align: center;
    }

    footer {
      background-color: #111;
      padding: 20px;
      text-align: center;
    }

    .reservation-form {
      background-color: rgba(255, 255, 255, 0.07);
      backdrop-filter: blur(6px);
      border-radius: 12px;
      padding: 30px;
      margin: 40px auto;
      max-width: 300px;
    }

    input, button {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: none;
      font-size: 16px;
      box-sizing: border-box;
    }

    input {
      background-color: rgba(255, 255, 255, 0.15);
      color: white;
    }

    input::placeholder {
      color: #ccc;
    }

    button {
      background-color: #db4a39;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #c0392b;
    }

    .message {
      margin-top: 20px;
      font-size: 1.2em;
    }
  </style>
  </head>
  <body>
    <div id="navbar"></div>
     <div id="container"></div>
    <div class="reservation-form">
    <h2>הזמן שולחן</h2>
    <form method="POST">
      <input type="text" name="name" placeholder="שם מלא" required>
      <input type="email" name="email" placeholder="אימייל" required>
      <input type="date" name="date" required>
      <button type="submit">שלח הזמנה</button>
    </form>

    <div class="message">
      <?php echo $message; ?>
    </div>
  </div>  
    <script src="js/navbar.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
<style>
</style>