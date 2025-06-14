let cart = [];

// ✅ טעינת עגלה מהשרת (הוזרמה מ־PHP מראש)
cart = Array.isArray(cartFromDB)
  ? cartFromDB.map((item) => ({
      id: parseInt(item.menu_item_id),
      name: item.name,
      price: parseFloat(item.price),
      quantity: parseInt(item.quantity),
    }))
  : [];

console.log("🛒 עגלה נטענה מהשרת:", cart);

// ✅ פרטי המשתמש (מוזרמים מ־PHP)
const userId = typeof user_id !== "undefined" ? user_id : null;
const userPhone = typeof user_phone !== "undefined" ? user_phone : null;

// אתחול הדף
function initMenuPage() {
  loadNavBar();
  renderMenu();
  renderCart();
  console.log("👤 משתמש מחובר:", userId, userPhone);
}

document.addEventListener("DOMContentLoaded", initMenuPage);

// מציג את פריטי התפריט
function renderMenu() {
  if (!Array.isArray(menuItems)) {
    console.error("❌ menuItems לא מוגדר או לא מערך");
    return;
  }

  const grid = document.querySelector(".menu-grid");
  grid.innerHTML = "";

  menuItems.forEach((item) => {
    const div = document.createElement("div");
    div.className = "menu-item";
    div.innerHTML = `
      <h3>${item.name}</h3>
      <p>${item.description}</p>
      <div class="price">₪${item.price}</div>
      <button data-id="${item.id}">הוסף לסל</button>
    `;
    div
      .querySelector("button")
      .addEventListener("click", () => addToCart(item.id));
    grid.appendChild(div);
  });
}

// מוסיף פריט לסל + שליחה ל־DB דרך טופס
function addToCart(id) {
  const item = menuItems.find((i) => i.id === id);
  if (!item) return;

  const existing = cart.find((i) => i.id === id);
  if (existing) {
    existing.quantity++;
  } else {
    cart.push({ ...item, quantity: 1 });
  }

  // שליחת הטופס לשרת (ללא רענון)
  const form = document.createElement("form");
  form.method = "POST";
  form.action = "./js/update_cart.php"; // נתיב נכון לקובץ
  form.target = "hiddenFrame";

  form.innerHTML = `
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="menu_item_id" value="${id}">
    <input type="hidden" name="user_id" value="${userId}">
  `;

  document.body.appendChild(form);
  form.submit();
  form.remove();

  renderCart();
}

// מציג את הסל עם כפתור מחיקה
function renderCart() {
  const ul = document.querySelector(".cart-items");
  ul.innerHTML = "";
  let total = 0;

  cart.forEach((item) => {
    total += item.price * item.quantity;
    const li = document.createElement("li");
    li.innerHTML = `
      ${item.name} - ₪${item.price} × ${item.quantity}
      <button onclick="removeFromCart(${item.id})">❌</button>
    `;
    ul.appendChild(li);
  });

  document.querySelector(".cart-total span").textContent = total;

  const checkoutBtn = document.querySelector(".checkout-button");
  checkoutBtn.style.display = cart.length ? "block" : "none";
  checkoutBtn.onclick = showOrderForm;
}

// מחיקת פריט מהעגלה + מחיקה מה־DB
function removeFromCart(id) {
  cart = cart.filter((item) => item.id !== id);

  // טופס נסתר למחיקה מה־DB
  const form = document.createElement("form");
  form.method = "POST";
  form.action = "update_cart.php";
  form.style.display = "none";

  const idInput = document.createElement("input");
  idInput.name = "menu_item_id";
  idInput.value = id;
  form.appendChild(idInput);

  const actionInput = document.createElement("input");
  actionInput.name = "action";
  actionInput.value = "delete";
  form.appendChild(actionInput);

  document.body.appendChild(form);
  form.submit();

  renderCart();
}

// טופס סיום הזמנה
function showOrderForm() {
  const container = document.querySelector(".order-form-container");
  const ids = cart.map((i) => `${i.id}:${i.quantity}`).join(",");

  container.innerHTML = `
    <h3>סיום הזמנה</h3>
    <form id="orderForm">
      <input type="hidden" name="itemIds" value="${ids}">
      <input type="text" name="name" placeholder="שם מלא" required>
      <input type="text" name="phone" value="${userPhone || ""}" required>
      <input type="email" name="email" placeholder="אימייל" required>
      <button type="submit">לתשלום</button>
    </form>
  `;

  container.style.display = "block";

  document.getElementById("orderForm").addEventListener("submit", (e) => {
    e.preventDefault();
    container.innerHTML = `<p class="thank-you">
      תודה לך, ${e.target.name.value}!<br>הזמנתך התקבלה.
    </p>`;
    console.log("🧾 הזמנה נשלחה:", ids);
  });
}
