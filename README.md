
## 📁 מבנה קבצים עיקרי

| קובץ                             | תיאור                                               |
| -------------------------------- | ------------------------------------------------     |
| `config/db.php`                  | התחברות למסד הנתונים                               |
| `models/OrderModel.php`          | פעולות מול הטבלאות `orders`, `cart`, `menu`        |
| `models/MenuModel.php`           | שליפת מנות מהתפריט                                 |
| `services/OrderService.php`      | לוגיקה עסקית עבור ניהול עגלה והזמנות              |
| `controllers/MenuController.php` | שליטה על תפריט                                      |
| `api/orders.php`                 | קובץ API מרכזי לכל הפעולות (GET/POST)              |
| `test.php`                       | קובץ בדיקות תצוגה באתר (מומלץ להרצה מקומית בלבד) |

---

## 📌 טבלאות עיקריות במסד `delicia_db`

* `users` – משתמשים
* `menu` – מנות בתפריט
* `orders` – הזמנות (שדה `status` מכיל InCart, Confirmed וכו')
* `cart` – פריטים בתוך הזמנה מסוימת

---

## 🌐 REST API – נקודות קצה

### 🔍 צפייה בהזמנה

* **URL:** `GET /api/orders.php?action=get&order_id=1`
* **תיאור:** מחזיר את פרטי ההזמנה והפריטים שבעגלה
* **תוצאה לדוגמה:**

```json
{
  "order": {"id": 1, "user_id": 1, "total_price": 64, "status": "InCart"},
  "items": [
    {"menu_item_id": 2, "quantity": 2, "menu_name": "סושי סלמון", "price": 32}
  ]
}
```

---

### ➕ הוספה לעגלה

* **URL:** `POST /api/orders.php?action=addToCart`
* **פרמטרים:** `user_id`, `menu_item_id`, `quantity`
* **דוגמה:**

```bash
curl -X POST http://localhost/api/orders.php?action=addToCart \
  -d "user_id=1&menu_item_id=2&quantity=2"
```

* **תוצאה:** `{"success": true, "order_id": 1}`

---

### ➖ הפחתת פריט אחד מהעגלה

* **URL:** `POST /api/orders.php?action=removeOneFromCart`
* **פרמטרים:** `order_id`, `menu_item_id`
* **תוצאה:**

```json
{"success": true, "updated": true, "action": "decremented quantity"}
```

---

## 🧪 בדיקות באמצעות `test.php`

הקובץ `test.php` מדגים את הפעולות דרך ממשק דפדפן:

* שליפת מנות
* הצגת הזמנות קיימות
* הוספת פריט לעגלה ובדיקת עדכון מחיר
* מחיקת פריט ובדיקת סכום חדש

---


