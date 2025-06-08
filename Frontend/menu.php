<?php
// Include database connection and models
require_once '../Backend/config/db.php';
require_once '../Backend/models/MenuModel.php';
require_once '../Backend/models/OrderModel.php';
require_once '../Backend/models/UserModel.php';

// Initialize models
$menuModel = new MenuModel($conn);
$orderModel = new OrderModel($conn);
$userModel = new UserModel($conn);

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    
    try {
        switch ($action) {
            case 'getMenu':
                $menuItems = $menuModel->getAllMenuItems();
                echo json_encode(['success' => true, 'data' => $menuItems]);
                exit;
                
            case 'addMenuItem':
                if ($menuModel->menuItemExists($input['name'], $input['category'])) {
                    throw new Exception("המנה כבר קיימת בקטגוריה הזו");
                }
                
                $result = $menuModel->addMenuItem(
                    $input['name'],
                    $input['description'],
                    $input['price'],
                    $input['available'],
                    $input['image_url'],
                    $input['category']
                );
                
                echo json_encode(['success' => true, 'message' => 'מנה נוספה בהצלחה']);
                exit;
                
            case 'deleteMenuItem':
                if (!$menuModel->menuItemByIdExists($input['id'])) {
                    throw new Exception("המנה לא קיימת");
                }
                
                $result = $menuModel->deleteMenuItem($input['id']);
                echo json_encode(['success' => true, 'message' => 'מנה נמחקה בהצלחה']);
                exit;
                
            case 'saveOrder':
                // Validate required fields
                if (empty($input['name']) || empty($input['phone']) || empty($input['email']) || empty($input['items'])) {
                    throw new Exception("נתונים חסרים - נא למלא את כל השדות הנדרשים");
                }
                
                $name = trim($input['name']);
                $phone = trim($input['phone']);
                $email = trim($input['email']);
                $notes = trim($input['notes'] ?? '');
                $items = $input['items'];
                $total = floatval($input['total']);
                
                // Validate phone format (basic validation)
                if (!preg_match('/^[0-9\-\+\(\)\s]+$/', $phone)) {
                    throw new Exception("מספר טלפון לא תקין");
                }
                
                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("כתובת אימייל לא תקינה");
                }
                
                // Check if user exists by phone number
                $userId = null;
                if ($userModel->userPhoneExists($phone)) {
                    // Get existing user by phone
                    $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
                    $stmt->execute([$phone]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $userId = $user['id'];
                } else {
                    // Create new user
                    // For new users, we'll set default values for missing fields
                    $city = ''; // You might want to add city field to the order form
                    $address = ''; // You might want to add address field to the order form
                    $role = 'customer'; // Default role
                    
                    $userModel->addUser($name, $city, $address, $phone, $email, $role);
                    
                    // Get the newly created user ID
                    $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
                    $stmt->execute([$phone]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $userId = $user['id'];
                }
                
                if (!$userId) {
                    throw new Exception("שגיאה ביצירת/איתור משתמש");
                }
                
                // Create new order
                $orderId = $orderModel->createOrder($userId);
                
                if (!$orderId) {
                    throw new Exception("שגיאה ביצירת הזמנה");
                }
                
                // Add items to cart and calculate total
                $calculatedTotal = 0;
                foreach ($items as $item) {
                    $menuItemId = intval($item['id']);
                    $quantity = intval($item['quantity']);
                    $price = floatval($item['price']);
                    
                    if ($quantity <= 0) {
                        continue; // Skip invalid quantities
                    }
                    
                    // Verify the price from database to prevent manipulation
                    $dbPrice = $orderModel->getMenuItemPrice($menuItemId);
                    if ($dbPrice != $price) {
                        throw new Exception("מחיר המוצר שונה מהמחיר בבסיס הנתונים");
                    }
                    
                    // Add item to cart
                    $orderModel->addOrUpdateCartItem($orderId, $menuItemId, $quantity);
                    $calculatedTotal += $dbPrice * $quantity;
                }
                
                // Verify total matches calculated total
                if (abs($calculatedTotal - $total) > 0.01) {
                    throw new Exception("סכום ההזמנה אינו תואם לחישוב");
                }
                
                // Update order with total price and change status
                $stmt = $conn->prepare("UPDATE orders SET total_price = ?, status = 'Pending', delivery_address = ?, created_at = NOW() WHERE id = ?");
                $deliveryAddress = $notes; // Using notes as delivery address for now
                $stmt->execute([$calculatedTotal, $deliveryAddress, $orderId]);
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'ההזמנה נשמרה בהצלחה',
                    'orderId' => $orderId,
                    'userId' => $userId
                ]);
                exit;
                
            default:
                throw new Exception("פעולה לא מוכרת");
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// Get menu items for initial page load
try {
    $menuItems = $menuModel->getAllMenuItems();
} catch (Exception $e) {
    $menuItems = [];
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Menu</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap"
    rel="stylesheet"
  />
  <link rel="stylesheet" href="css/menu.css"/>
</head>
<body>
  <div id="navbar"></div>

  <main id="main-content" style="padding: 100px 20px 40px;">
    <!-- Menu section -->
    <section id="menu-section">
      <h2 class="section-title">התפריט שלנו</h2>
      <div class="menu-grid">
        <?php if (isset($error)): ?>
          <p style="color: red;">שגיאה בטעינת התפריט: <?= htmlspecialchars($error) ?></p>
        <?php else: ?>
          <?php foreach ($menuItems as $item): ?>
            <div class="menu-item" data-id="<?= $item['id'] ?>">
              <div class="menu-item-content">
                <h3 class="menu-item-name"><?= htmlspecialchars($item['name']) ?></h3>
                <p class="menu-item-description"><?= htmlspecialchars($item['description']) ?></p>
                <p class="menu-item-category"><?= htmlspecialchars($item['category']) ?></p>
                <div class="menu-item-footer">
                  <p class="menu-item-price">₪<?= number_format($item['price'], 2) ?></p>
                  <?php if ($item['available']): ?>
                    <button class="add-to-cart-btn" onclick="addToCart(<?= $item['id'] ?>, '<?= htmlspecialchars($item['name']) ?>', <?= $item['price'] ?>)">
                      הוסף לעגלה
                    </button>
                  <?php else: ?>
                    <span class="unavailable">לא זמין</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

    <!-- Cart section -->
    <aside id="cart-section">
      <h2 class="section-title">עגלת הזמנות</h2>
      <ul class="cart-items"></ul>
      <p class="cart-total">סה"כ: ₪<span>0</span></p>
      <button class="checkout-button">סיום הזמנה</button>
      <div class="order-form-container" style="display:none;"></div>
    </aside>
  </main>

  <style>
    /* Order form styles */
    .order-form-container {
      background: #f9f9f9;
      padding: 20px;
      border-radius: 8px;
      margin-top: 20px;
      border: 1px solid #ddd;
    }
    
    .order-form-container h3 {
      color: #333;
      margin-bottom: 15px;
      text-align: center;
    }
    
    .order-summary {
      background: white;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
      border: 1px solid #eee;
    }
    
    .order-summary h4 {
      margin-bottom: 10px;
      color: #555;
    }
    
    .order-summary ul {
      list-style: none;
      padding: 0;
      margin-bottom: 10px;
    }
    
    .order-summary li {
      padding: 5px 0;
      border-bottom: 1px solid #f0f0f0;
    }
    
    #orderForm input,
    #orderForm textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
      box-sizing: border-box;
    }
    
    #orderForm textarea {
      height: 80px;
      resize: vertical;
    }
    
    #orderForm button {
      background: #007bff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      margin-left: 10px;
    }
    
    #orderForm button[type="button"] {
      background: #6c757d;
    }
    
    #orderForm button:hover {
      opacity: 0.9;
    }
    
    .thank-you {
      text-align: center;
      background: white;
      padding: 30px;
      border-radius: 8px;
      border: 2px solid #28a745;
    }
    
    .thank-you h3 {
      color: #28a745;
      margin-bottom: 15px;
    }
    
    .thank-you p {
      margin-bottom: 10px;
      color: #555;
    }
    
    .thank-you button {
      background: #28a745;
      color: white;
      padding: 10px 25px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 15px;
    }

    .loading {
      opacity: 0.6;
      pointer-events: none;
    }
  </style>

  <script src="js/navbar.js"></script>
  <script>
    // Cart functionality
    let cart = [];
    let cartTotal = 0;

    function addToCart(id, name, price) {
      // Check if item already exists in cart
      const existingItem = cart.find(item => item.id === id);
      
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({
          id: id,
          name: name,
          price: price,
          quantity: 1
        });
      }
      
      updateCartDisplay();
    }

    function removeFromCart(id) {
      cart = cart.filter(item => item.id !== id);
      updateCartDisplay();
    }

    function updateQuantity(id, newQuantity) {
      if (newQuantity <= 0) {
        removeFromCart(id);
        return;
      }
      
      const item = cart.find(item => item.id === id);
      if (item) {
        item.quantity = newQuantity;
        updateCartDisplay();
      }
    }

    function updateCartDisplay() {
      const cartItemsContainer = document.querySelector('.cart-items');
      const cartTotalSpan = document.querySelector('.cart-total span');
      
      // Clear current cart display
      cartItemsContainer.innerHTML = '';
      cartTotal = 0;
      
      // Add each cart item
      cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        cartTotal += itemTotal;
        
        const li = document.createElement('li');
        li.className = 'cart-item';
        li.innerHTML = `
          <div class="cart-item-info">
            <span class="cart-item-name">${item.name}</span>
            <span class="cart-item-price">₪${item.price.toFixed(2)}</span>
          </div>
          <div class="cart-item-controls">
            <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
            <span class="quantity">${item.quantity}</span>
            <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
            <button class="remove-btn" onclick="removeFromCart(${item.id})">הסר</button>
          </div>
          <div class="cart-item-total">₪${itemTotal.toFixed(2)}</div>
        `;
        
        cartItemsContainer.appendChild(li);
      });
      
      // Update total
      cartTotalSpan.textContent = cartTotal.toFixed(2);
      
      // Show/hide checkout button
      const checkoutButton = document.querySelector('.checkout-button');
      checkoutButton.style.display = cart.length > 0 ? 'block' : 'none';
      
      // Add click event to checkout button
      checkoutButton.onclick = showOrderForm;
    }

    // Show order form when clicking checkout
    function showOrderForm() {
      const container = document.querySelector('.order-form-container');
      const ids = cart.map(item => item.id).join(',');
      const total = cartTotal.toFixed(2);
      
      container.innerHTML = `
        <h3>סיום הזמנה</h3>
        <div class="order-summary">
          <h4>סיכום הזמנה:</h4>
          <ul>
            ${cart.map(item => `
              <li>${item.name} x${item.quantity} - ₪${(item.price * item.quantity).toFixed(2)}</li>
            `).join('')}
          </ul>
          <p><strong>סה"כ: ₪${total}</strong></p>
        </div>
        <form id="orderForm">
          <input type="hidden" name="itemIds" value="${ids}">
          <input type="hidden" name="total" value="${total}">
          <input type="text" name="name" placeholder="שם מלא" required>
          <input type="text" name="phone" placeholder="טלפון" required>
          <input type="email" name="email" placeholder="אימייל" required>
          <textarea name="notes" placeholder="הערות להזמנה (אופציונלי)"></textarea>
          <button type="submit">לתשלום</button>
          <button type="button" onclick="hideOrderForm()">ביטול</button>
        </form>
      `;
      
      container.style.display = 'block';
      
      // Handle form submission
      document.getElementById('orderForm').addEventListener('submit', handleOrderSubmission);
    }

    // Hide order form
    function hideOrderForm() {
      const container = document.querySelector('.order-form-container');
      container.style.display = 'none';
      container.innerHTML = '';
    }

    // Handle order form submission
    async function handleOrderSubmission(e) {
      e.preventDefault();
      
      const formData = new FormData(e.target);
      const orderData = {
        name: formData.get('name'),
        phone: formData.get('phone'),
        email: formData.get('email'),
        notes: formData.get('notes'),
        items: cart,
        total: cartTotal,
        itemIds: formData.get('itemIds')
      };
      
      // Show loading state
      const submitButton = e.target.querySelector('button[type="submit"]');
      const originalText = submitButton.textContent;
      submitButton.textContent = 'שומר הזמנה...';
      submitButton.disabled = true;
      e.target.classList.add('loading');
      
      try {
        const result = await saveOrderToDatabase(orderData);
        
        if (result.success) {
          // Show success message
          const container = document.querySelector('.order-form-container');
          container.innerHTML = `
            <div class="thank-you">
              <h3>תודה לך, ${orderData.name}!</h3>
              <p>הזמנתך התקבלה בהצלחה.</p>
              <p>מספר הזמנה: ${result.orderId}</p>
              <p>סה"כ לתשלום: ₪${orderData.total.toFixed(2)}</p>
              <p>נתקשר אליך בקרוב לטלפון: ${orderData.phone}</p>
              <button onclick="finishOrder()">סגור</button>
            </div>
          `;
        } else {
          throw new Error(result.error || 'שגיאה לא ידועה');
        }
      } catch (error) {
        alert('שגיאה בשמירת ההזמנה: ' + error.message);
        
        // Restore form state
        submitButton.textContent = originalText;
        submitButton.disabled = false;
        e.target.classList.remove('loading');
      }
    }

    // Finish order and reset cart
    function finishOrder() {
      cart = [];
      cartTotal = 0;
      updateCartDisplay();
      hideOrderForm();
      alert('תודה על הזמנתך!');
    }

    // Function to make AJAX requests to the same page
    async function makeRequest(action, data = {}) {
      try {
        const response = await fetch(window.location.href, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            action: action,
            ...data
          })
        });
        
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        
        const result = await response.json();
        return result;
      } catch (error) {
        console.error('Request failed:', error);
        return { success: false, error: 'בקשה נכשלה: ' + error.message };
      }
    }

    // Function to save order to database
    async function saveOrderToDatabase(orderData) {
      const result = await makeRequest('saveOrder', orderData);
      return result;
    }

    // Example function to refresh menu (if needed)
    async function refreshMenu() {
      const result = await makeRequest('getMenu');
      if (result.success) {
        // Rebuild menu display with new data
        location.reload(); // Simple approach - reload the page
      } else {
        alert('שגיאה בטעינת התפריט: ' + result.error);
      }
    }

    // Initialize cart display
    document.addEventListener('DOMContentLoaded', function() {
      updateCartDisplay();
      loadNavBar();
    });
  </script>
</body>
</html>