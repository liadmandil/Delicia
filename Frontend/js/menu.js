//const { response } = require("express");

// מבנה של פריטי התפריט
// const menuItems = [
//   { id: 1, name: "סושי קלסי", desc: "6 יחידות סושי מגוונות", price: 45 },
//   { id: 2, name: "רול צמחוני", desc: "אורז, אבוקדו, מלפפון", price: 38 },
//   { id: 3, name: "ראמן עוף", desc: "מרק עדין עם אטריות וביצת עין", price: 55 },
//   {
//     id: 4,
//     name: "וואן טון",
//     desc: "8 וואן טון מטוגנים עם רוטב צ'ילי",
//     price: 42,
//   },
//   { id: 5, name: "המבורגר אסיאתי", desc: "בשר, חמוצים, מיונז קיסו", price: 60 },
// ];

let menuItems;

fetch('../Backend/api/menu.php', {
  method: 'GET',
})
.then(response => response.json())
.then(result => {
  if (result.success) {
    console.log("📋 תפריט שהתקבל:", result.data);
    menuItems = result.data;
    
    // Put your code that needs menuItems here
    renderMenu();
    
  } else {
    console.error("❌ שגיאה:", result.message);
  }
})
.catch(error => {
  console.error("⚠️ תקלה בחיבור לשרת:", error);
});

let cart = [];

// אתחול הדף
function initMenuPage() {
  loadNavBar();
  //renderMenu();
  renderCart();
}

document.addEventListener("DOMContentLoaded", initMenuPage);

// מציג את פריטי התפריט
function renderMenu() {
  const grid = document.querySelector(".menu-grid");
  menuItems.forEach((item) => {
    const div = document.createElement("div");
    div.className = "menu-item";
    div.innerHTML = `
        <h3>${item.name}</h3>
        <p>${item.description}</p>
        <div class="price">₪${item.price}</div>
        <button onclick="addToCart(${item.id})" data-id="${item.id}">הוסף לסל</button>
      `;
    grid.appendChild(div);
    div
      .querySelector("button")
      .addEventListener("click", () => addToCart(item.id));
  });
}

// מוסיף לפרטי הסל
function addToCart(id) {
  const item = menuItems.find((i) => i.id === id);
  cart.push(item);
  renderCart();
  // Fetch to add the item to the cart
}

// מציג את הסל עם הסכום הכולל
function renderCart() {
  const ul = document.querySelector(".cart-items");
  ul.innerHTML = "";
  let total = 0;
  cart.forEach((item, idx) => {
    total += parseInt(item.price);
    const li = document.createElement("li");
    li.textContent = `${item.name} - ₪${item.price}`;
    ul.appendChild(li);
  });
  document.querySelector(".cart-total span").textContent = total;

  // אם יש לפחות פריט, מראים את כפתור Checkout
  const checkoutBtn = document.querySelector(".checkout-button");
  checkoutBtn.style.display = cart.length ? "block" : "none";
  checkoutBtn.onclick = showOrderForm;
}

// מראה טופס Order עם מזהי הפריטים ופרטים אישיים
function showOrderForm() {
  const container = document.querySelector(".order-form-container");
  const ids = cart.map((i) => i.id).join(",");
  container.innerHTML = `
      <h3>סיום הזמנה</h3>
      <form id="orderForm">
        <input type="hidden" name="itemIds"   value="${ids}">
        <input type="text"   name="name"      placeholder="שם מלא" required>
        <input type="text"    name="phone"     placeholder="טלפון" required>
        <input type="email"  name="email"     placeholder="אימייל" required>
        <button type="submit">לתשלום</button>
      </form>
    `;
  container.style.display = "block";
  document.getElementById("orderForm").addEventListener("submit", (e) => {
    e.preventDefault();
    // כאן תוכל לשלוח לשרת...
    container.innerHTML = `<p class="thank-you">
          תודה לך, ${e.target.name.value}!<br>
          הזמנתך התקבלה.  
        </p>`;

    const OrderIds = ids;
    console.log(ids);
  });
}
