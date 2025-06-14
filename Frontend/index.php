<?php
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $date = $_POST["date"];

    $today = new DateTime();
    $reservationDate = new DateTime($date);
    $interval = $today->diff($reservationDate);
    $daysLeft = $interval->days;

    if ($reservationDate < $today) {
        $response = "<p style='color:red;'>התאריך שהוזן עבר. נא לבחור תאריך עתידי.</p>";
    } else {
        $response = "<h3>תודה $name!</h3><p>נותרו <strong>$daysLeft</strong> ימים להזמנה שלך.</p>";
    }
    
    // If this is an AJAX request, return JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['message' => $response]);
        exit;
    }
    
    // Store message in session and redirect to prevent resubmission
    $_SESSION['form_message'] = $response;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Check if there's a message from the session
if (isset($_SESSION['form_message'])) {
    $message = $_SESSION['form_message'];
    unset($_SESSION['form_message']); // Clear the message after displaying
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

    .loading {
      opacity: 0.6;
      pointer-events: none;
    }
  </style>
  </head>
  <body>
    <div id="navbar"></div>
    <div id="container"></div>
    <div class="reservation-form">
      <h2>הזמן שולחן</h2>
      <form id="reservationForm" action="" method="POST">
        <input type="text" name="name" placeholder="שם מלא" required>
        <input type="email" name="email" placeholder="אימייל" required>
        <input type="date" name="date" required>
        <button type="submit">שלח הזמנה</button>
      </form>

      <div class="message" id="messageDiv">
        <?php echo $message; ?>
      </div>
    </div>  
    
    <script>
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        
        const form = this;
        const messageDiv = document.getElementById('messageDiv');
        const formData = new FormData(form);
        
        // Add loading state
        form.classList.add('loading');
        messageDiv.innerHTML = '<p>שולח...</p>';
        
        // Create XMLHttpRequest
        const xhr = new XMLHttpRequest();
        xhr.open('POST', window.location.href, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                form.classList.remove('loading');
                
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        messageDiv.innerHTML = response.message;
                        form.reset(); // Clear the form
                    } catch (error) {
                        messageDiv.innerHTML = '<p style="color:red;">שגיאה בשליחת הטופס. אנא נסה שוב.</p>';
                    }
                } else {
                    messageDiv.innerHTML = '<p style="color:red;">שגיאה בשליחת הטופס. אנא נסה שוב.</p>';
                }
            }
        };
        
        xhr.send(formData);
    });
    </script>
    
    <script src="js/navbar.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>