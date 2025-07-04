let cart = [];

// אתחול הדף
function initMenuPage() {
  loadNavBar();
  renderMenu();
  renderCart();
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
      <button data-id="${item.id}" class="add-btn">הוסף לסל</button>
    `;
    grid.appendChild(div);
  });

  // bind add buttons
  grid.querySelectorAll(".add-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      addToCart(id);
    });
  });
}

// מוסיף פריט לסל (in‑memory)
function addToCart(id) {
  const item = menuItems.find((i) => i.id == id);
  if (!item) return;

  const existing = cart.find((i) => i.id == id);
  if (existing) {
    existing.quantity++;
  } else {
    cart.push({ ...item, quantity: 1 });
  }

  renderCart();
}

// הוספת פריט יחיד לסל
function increaseQuantity(id) {
  const existing = cart.find((i) => i.id == id);
  if (existing) {
    existing.quantity++;
    renderCart();
  }
}

// הפחתת פריט יחיד מהסל
function decreaseQuantity(id) {
  const existing = cart.find((i) => i.id == id);
  if (existing) {
    if (existing.quantity > 1) {
      existing.quantity--;
    } else {
      // אם הכמות 1, נסיר את הפריט לגמרי
      cart = cart.filter((item) => item.id != id);
    }
    renderCart();
  }
}

// מציג את סל הקניות
function renderCart() {
  const ul = document.querySelector(".cart-items");
  ul.innerHTML = "";

  let total = 0;
  cart.forEach((item) => {
    total += item.price * item.quantity;
    const li = document.createElement("li");
    li.innerHTML = `
      <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
        <div style="display: flex; align-items: center; gap: 10px;">
          <span>${item.name} - ₪${item.price}</span>
          <span style="font-weight: bold;">× ${item.quantity}</span>
        </div>
        <div style="display: flex; align-items: center; gap: 5px;">
          <button data-id="${item.id}" class="decrease-btn" style="width: 30px; height: 30px; border: 1px solid #ccc; background: #f5f5f5; cursor: pointer;">-</button>
          <button data-id="${item.id}" class="increase-btn" style="width: 30px; height: 30px; border: 1px solid #ccc; background: #f5f5f5; cursor: pointer;">+</button>
          <button data-id="${item.id}" class="remove-btn" style="margin-left: 5px;">❌</button>
        </div>
      </div>
    `;
    ul.appendChild(li);
  });

  // bind quantity control buttons
  ul.querySelectorAll(".increase-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      increaseQuantity(id);
    });
  });

  ul.querySelectorAll(".decrease-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      decreaseQuantity(id);
    });
  });

  // bind remove buttons
  ul.querySelectorAll(".remove-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      removeFromCart(id);
    });
  });

  document.querySelector(".cart-total span").textContent = total;
  const checkoutBtn = document.querySelector(".checkout-button");
  checkoutBtn.style.display = cart.length ? "block" : "none";
  checkoutBtn.onclick = showOrderForm;

  console.log("cart:", cart);
}

// הסרה מהסל (in‑memory)
function removeFromCart(id) {
  cart = cart.filter((item) => item.id != id);
  renderCart();
}

// טופס סיום הזמנה
function showOrderForm() {
  const container = document.querySelector(".order-form-container");
  const ids   = cart.map(i => `${i.id}:${i.quantity}`).join(",");
  const total = cart.reduce((sum, i) => sum + i.price*i.quantity, 0);

  container.innerHTML = `
    <h3>סיום הזמנה</h3>
    <form id="orderForm"
          action=""
          method="POST"
          target="hiddenFrame">
      <input type="hidden" name="itemIds"        value="${ids}">
      <input type="hidden" name="totalPrice"     value="${total}">
      <input type="text"   name="name"           placeholder="שם מלא"       required>
      <input type="email"  name="email"          placeholder="אימייל"        required>
      <input type="tel"    name="phone" style="text-align: right;" placeholder="טלפון" required>
      <input type="text"   name="deliveryAddress" placeholder="כתובת"       required>
      <button type="submit">לתשלום</button>
    </form>
  `;
  container.style.display = "block";

  // once the hidden iframe finishes the POST, show the thank-you
  const iframe = document.querySelector('iframe[name="hiddenFrame"]');
  iframe.onload = () => {
    container.innerHTML = `<p class="thank-you">
      תודה לך, ${document.querySelector("#orderForm input[name=name]").value}!<br>הזמנתך התקבלה.
    </p>`;
    cart = [];
    renderCart();
    // clear the handler so it only fires once
    iframe.onload = null;
  };
}