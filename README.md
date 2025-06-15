# 🍽️ Delicia - מערכת ניהול מסעדה

מערכת PHP מבוססת MySQL לניהול תפריט, הזמנות, ועגלות קנייה במסעדה.

---

## 📁 מבנה קבצים עיקרי

| קובץ                             | תיאור                                               |
| -------------------------------- | --------------------------------------------------- |
| `config/db.php`                  | התחברות למסד הנתונים                               |
| `models/OrderModel.php`          | פעולות מול הטבלאות `orders`, `cart`, `menu`        |
| `models/MenuModel.php`           | פעולות מול טבלת `menu` בלבד                        |
| `services/OrderService.php`      | לוגיקה עסקית להזמנות (שליפה, הוספה, מחיקה וכו')   |
| `services/MenuService.php`       | לוגיקה עסקית לתפריט (שליפה, הוספה, מחיקה)         |
| `controllers/MenuController.php` | שליטה על תפריט - משתמש בשירותים ודגמים            |
| `controllers/OrderController.php`| שליטה על הזמנות                                     |
| `api/orders.php`                 | קובץ API מרכזי לכל פעולות על הזמנות                |
| `api/menu.php`                   | קובץ API מרכזי לכל פעולות על תפריט                 |
| `test.php`                       | בדיקות מקומיות לתפקוד כולל                         |

---

## 🗃️ טבלאות במסד הנתונים `delicia_db`

- `users` – משתמשים רשומים
- `menu` – פריטי תפריט זמינים
- `orders` – הזמנות לפי משתמש, כולל מצב (InCart, Confirmed וכו')
- `cart` – פריטים בעגלה לכל הזמנה

---

## 🌐 REST API – נקודות קצה

### 🔍 צפייה בהזמנה
- **URL:** `GET /api/orders.php?action=get&order_id=1`
- **תיאור:** מחזיר את פרטי ההזמנה + פריטי עגלה

### ➕ הוספה לעגלה
- **URL:** `POST /api/orders.php?action=addToCart`
- **פרמטרים:** `user_id`, `menu_item_id`, `quantity`

### ➖ הפחתת כמות ב־1
- **URL:** `POST /api/orders.php?action=removeOneFromCart`
- **פרמטרים:** `order_id`, `menu_item_id`

### ❌ מחיקה מלאה מהעגלה
- **URL:** `POST /api/orders.php?action=deleteFromCart`
- **פרמטרים:** `order_id`, `menu_item_id`

### 📬 קבלת הזמנה פתוחה למשתמש (או יצירת אחת חדשה)
- **URL:** `GET /api/orders.php?action=getOrCreateOpenOrder&user_id=1`

### 📜 שליפת כל פריטי התפריט
- **URL:** `GET /api/menu.php?action=getMenu`

### ➕ הוספת פריט חדש לתפריט
- **URL:** `POST /api/menu.php?action=add`
- **פרמטרים:** `name`, `description`, `price`, `available`, `image_url`, `category`

### 🗑️ מחיקת פריט מהתפריט
- **URL:** `POST /api/menu.php?action=delete`
- **פרמטרים:** `menu_item_id`

---

## 🧪 בדיקות עם test.php

קובץ `test.php` מדגים את כל הפעולות בממשק HTML פשוט:
- שליפת תפריט
- הוספה ומחיקה מהתפריט
- הוספת מוצר לעגלה
- מחיקת מוצר מהעגלה
- הצגת הזמנות לפי מזהה
- בדיקת הזמנה פתוחה / יצירת חדשה

---

## 🗂️ דוגמת שליפת הזמנה

```json
{
  "order": {
    "id": 1,
    "user_id": 1,
    "total_price": 64,
    "status": "InCart"
  },
  "items": [
    {
      "menu_item_id": 2,
      "quantity": 2,
      "menu_name": "סושי סלמון",
      "price": 32
    }
  ]
}
