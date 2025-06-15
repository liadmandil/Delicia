# 🍜 דליסיה - Delicia Asian Restaurant

<div align="center">
  <img src="https://img.shields.io/badge/Restaurant-Asian%20Kitchen-red?style=for-the-badge&logo=food" alt="Restaurant Badge">
  <img src="https://img.shields.io/badge/Tech-PHP%20%7C%20MySQL%20%7C%20JavaScript-blue?style=for-the-badge" alt="Tech Stack">
  <img src="https://img.shields.io/badge/Language-Hebrew%20RTL-green?style=for-the-badge" alt="Language">
</div>

## 🌟 About Delicia

**דליסיה** is a modern Asian restaurant web application that combines traditional Asian cuisine with a contemporary dining experience. Our platform offers a complete digital restaurant solution with Hebrew RTL support.

> *"אל תדבר לסושי, דבר איתנו!"* - Don't talk to sushi, talk to us!

---

## 🚀 Features

### 🏠 **Homepage**
- **Hero Image Slider**: Rotating gallery showcasing restaurant ambiance
- **Restaurant Information**: Hours, locations, and contact details
- **Dynamic Content**: Auto-updating promotional content

### 🍽️ **Menu System** 
- **Interactive Menu Display**: Browse our Asian cuisine offerings
- **Smart Shopping Cart**: Add/remove items with quantity controls
- **Real-time Price Calculation**: Instant total updates
- **Order Processing**: Complete order workflow with customer details

### 📝 **Table Reservations**
- **Date Validation**: Prevents past-date bookings
- **Large Group Detection**: Special handling for 6+ people
- **AJAX Form Submission**: Smooth user experience without page reloads
- **Countdown Display**: Shows days remaining until reservation

### 📸 **Photo Gallery**
- **Restaurant Showcase**: Visual presentation of dishes and atmosphere
- **Responsive Design**: Optimized viewing on all devices

### 💬 **Customer Feedback System**
- **Comprehensive Review Form**: Multi-section feedback collection
- **Rich Input Types**: Colors, ranges, file uploads, and more
- **Email Integration**: Direct communication with management

### 📞 **Support Contact**
- **Multi-category Support**: Technical, payment, account issues
- **Database Storage**: All inquiries saved for follow-up
- **Admin Panel**: View previous customer inquiries

---

## 🛠️ Technical Stack

### **Backend**
- ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white) **PHP 7.4+**
- ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white) **MySQL Database**
- **Session Management** for user state
- **Prepared Statements** for SQL injection prevention

### **Frontend**
- ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white) **Semantic HTML5**
- ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white) **CSS3 with RTL Support**
- ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black) **Vanilla JavaScript**
- **Responsive Design** for mobile compatibility

### **Database Schema**
```sql
Tables:
├── menu (id, name, description, price)
├── orders (id, phone, name, email, created_at, total_price, delivery_address)
├── order_items (order_id, menu_item_id, quantity)
└── contact_us (id, full_name, email, phone, subject, category, message)
```

---

## 📁 Project Structure

```
delicia-restaurant/
├── 📄 index.php              # Homepage with restaurant info
├── 📄 menu.php               # Interactive menu & ordering
├── 📄 review.html            # Customer feedback form
├── 📄 gallery.html           # Photo gallery
├── 📄 contact.html           # Contact page
├── 📄 ContactUsForm.php      # Support ticket system
├── 📁 css/                   # Stylesheets
│   ├── style.css
│   ├── menu.css
│   └── gallery.css
├── 📁 js/                    # JavaScript modules
│   ├── main.js              # Homepage functionality
│   ├── menu.js              # Cart & ordering logic
│   ├── navbar.js            # Navigation component
│   └── gallery.js           # Image gallery
├── 📁 images/               # Restaurant photos & assets
└── 📁 about/               # Additional pages
    └── hub.html
```

---

## 🎯 Key Functionalities

### **Order Management System**
- Real-time cart updates with local storage
- Comprehensive order form with validation
- Database integration for order tracking
- Hidden iframe submission for seamless UX

### **Reservation System**
- Smart date validation (no past dates)
- Large group detection and special messaging
- Session-based message handling
- AJAX-powered form submission

### **Multi-language Support**
- Full Hebrew RTL (Right-to-Left) implementation
- Unicode UTF-8 support throughout
- Culturally appropriate UI/UX design

### **Security Features**
- SQL injection prevention with prepared statements
- Input sanitization and validation
- Error logging and debugging capabilities
- Session management for secure user states

---

## 🔧 Installation & Setup

### **Database Configuration**
```php
$conn = new mysqli(
    "sql211.byethost22.com", 
    "b22_39125661", 
    "s@Ts7AP2L.pwPHx", 
    "b22_39125661_delicia_db"
);
```
---

## 🎨 User Experience Features

### **Visual Design**
- Modern glassmorphism effects
- Smooth CSS transitions and animations
- Professional color scheme
- Mobile-first responsive design

### **Interactive Elements**
- Dynamic image sliders with auto-rotation
- Quantity selectors with +/- buttons
- Real-time form validation
- Progress indicators and loading states

### **Accessibility**
- Semantic HTML structure
- Keyboard navigation support
- Screen reader compatibility
- High contrast color ratios

---

## 📱 Responsive Design

- **Mobile-First Approach**: Optimized for smartphone usage
- **Tablet Compatibility**: Seamless experience on medium screens
- **Desktop Enhancement**: Full-featured experience on large displays
- **Cross-Browser Support**: Compatible with modern browsers

---

## 🔐 Security Considerations

- **Data Sanitization**: All user inputs are properly escaped
- **SQL Injection Prevention**: Prepared statements throughout
- **Session Security**: Proper session management
- **Error Handling**: Comprehensive logging without exposing sensitive data

---

## 🌐 Contact Information

**Restaurant Locations:**
- 📍 [חיפה - Haifa Branch](https://maps.app.goo.gl/SRN5jTveNbR9HiVX6)
- 📍 [תל אביב - Tel Aviv Branch](https://www.bing.com/maps?q=150+Dizengoff+St,+Tel+Aviv)
- 📍 [עמק חפר - Emek Hefer Branch](https://www.openstreetmap.org/note/4444204#map=18/32.343721/34.913140&layers=N)

**Contact Details:**
- 📧 Email: info@delicia.co.il
- ☎️ Phone: 03-1234567
- 🕒 Hours: Sun-Thu 12:00-22:30, Fri 23:00, Sat 23:30

---

## 🏆 Project Highlights

- ✅ **Full-Stack Implementation**: Complete restaurant management system
- ✅ **Cultural Adaptation**: Native Hebrew RTL interface
- ✅ **Modern UX/UI**: Contemporary design with smooth interactions
- ✅ **Database Integration**: Robust data management
- ✅ **Security-First**: Production-ready security measures
- ✅ **Mobile-Optimized**: Responsive across all devices

---

<div align="center">
  <h3>🍣 Built with passion for Asian cuisine and modern web development 🥢</h3>
  <p><em>© 2024 Delicia Asian Restaurant - All rights reserved</em></p>
</div>
