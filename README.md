# Event Management System - Secured CRUD Application

A complete PHP-based event management system with authentication and authorization using sessions and cookies.

![PHP](https://img.shields.io/badge/PHP-7.4+-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange)
![License](https://img.shields.io/badge/License-MIT-green)

##  Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Security Implementation](#security-implementation)
- [Project Structure](#project-structure)
- [Usage](#usage)
- [Security Features](#security-features)
- [Contributing](#contributing)
- [License](#license)

---

##  Overview

This project is a **secure Event Management System** that demonstrates proper implementation of authentication, authorization, and CRUD operations in PHP. Users can register, login, and manage their personal events with full security measures in place.

**Assignment Focus:**
- Securing CRUD operations with sessions and cookies
- Implementing proper authentication mechanisms
- Preventing common web vulnerabilities (SQL Injection, XSS, Session Fixation)

---

## ✨ Features

### User Authentication
-  User Registration with validation
-  Secure Login system
-  "Remember Me" functionality (30-day cookies)
-  Secure Logout with session destruction

### Event Management (CRUD)
-  **Create** - Add new events
-  **Read** - View all your events
-  **Update** - Edit existing events
-  **Delete** - Remove events with confirmation

### Security Features
-  Session-based authentication
-  Cookie-based persistent login
-  Password hashing (bcrypt)
-  SQL injection prevention (Prepared Statements)
-  XSS protection (Input sanitization)
-  Session fixation prevention
-  User-specific data access control

---

## Technologies Used

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3
- **Security:** PDO, Password Hashing, Sessions, Cookies

---

##  Installation

### Prerequisites

- XAMPP installed
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web browser

### Steps

1. **Clone the repository**
```bash
   git clone https://github.com/your-username/event-management-system.git
   cd Event_Management_System
```

2. **Move to web server directory**
```bash
   # For XAMPP
   Copy folder to: C:/xampp/htdocs/Event_Management_System
   
   # For WAMP
   Copy folder to: C:/wamp64/www/Event_Management_System
```

3. **Configure database connection**
   - Open `config.php`
   - Update database credentials if needed:
```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'event_system');
```

4. **Import database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create new database: `event_system`
   - Import `database.sql` file

5. **Access the application**
```
   http://localhost/Event_Management_System/login.php
```

---

##  Database Setup

### Database Schema

The system uses two main tables:

#### Users Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Events Table
```sql
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    location VARCHAR(150),
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);
```

### Default Credentials
- **Username:** admin
- **Password:** admin123

---

##  Security Implementation

### 1. **How Sessions Are Used**

Sessions manage user authentication state throughout the application.

**Session Configuration:**
```php
ini_set('session.cookie_httponly', 1);  // Prevent JavaScript access
ini_set('session.use_only_cookies', 1); // Force cookie-only sessions
ini_set('session.cookie_secure', 0);    // Set to 1 for HTTPS
ini_set('session.cookie_samesite', 'Strict'); // CSRF protection
```

**Session Management:**
- Sessions are started on every page via `config.php`
- User credentials stored in `$_SESSION['user_id']` and `$_SESSION['username']`
- Session ID regenerated after login to prevent fixation
- Sessions destroyed completely on logout

**Example:**
```php
// After successful login
session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
```

---

### 2. **How Cookies Are Used**

Cookies implement the "Remember Me" functionality for persistent login.

**Cookie Implementation:**
```php
if ($remember) {
    $token = bin2hex(random_bytes(32)); // Secure random token
    setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
    setcookie('user_id', $user['id'], time() + (86400 * 30), '/', '', false, true);
}
```

**Cookie Parameters:**
- **Name:** `remember_token` and `user_id`
- **Value:** Cryptographically secure random token
- **Expiry:** 30 days (86400 seconds × 30)
- **Path:** `/` (entire site)
- **Secure:** `false` (set to `true` for HTTPS)
- **HTTPOnly:** `true` (JavaScript cannot access)

**Cookie Clearing on Logout:**
```php
setcookie('remember_token', '', time() - 3600, '/');
setcookie('user_id', '', time() - 3600, '/');
```

---

### 3. **How Authentication is Secured**

#### **Password Security**
- Passwords hashed using `password_hash()` with bcrypt algorithm
- Password verification using `password_verify()`
- Never store plain text passwords
```php
// Registration
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Login
if (password_verify($password, $user['password'])) {
    // Login successful
}
```

#### **SQL Injection Prevention**
- All database queries use **prepared statements**
- User input never directly concatenated into SQL
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
```

#### **XSS Protection**
- All user inputs sanitized using `htmlspecialchars()` and `strip_tags()`
- Output escaping prevents malicious script injection
```php
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
```

#### **Session Fixation Prevention**
- Session ID regenerated after successful login
- New session created on logout
```php
session_regenerate_id(true);
```

#### **Access Control**
- All CRUD pages check for valid session
- Users can only access their own data
```php
if (!isLoggedIn()) {
    redirect('login.php');
}

// User-specific queries
$stmt = $pdo->prepare("SELECT * FROM events WHERE created_by = ?");
$stmt->execute([$_SESSION['user_id']]);
```

---

##  Project Structure
```
Event-Management-System/
│
├── config.php              # Database & session configuration
├── signup.php              # User registration page
├── login.php               # User login page
├── logout.php              # Logout handler
├── dashboard.php           # Main page (Read & Delete events)
├── create_event.php        # Create new event page
├── edit_event.php          # Edit existing event page
├── database.sql            # Database schema & sample data
└── README.md               # Project documentation
```

---

## Usage

### 1. **Register a New Account**
- Navigate to `signup.php`
- Fill in username, email, and password
- Submit to create account

### 2. **Login**
- Navigate to `login.php`
- Enter credentials
- Check "Remember Me" for 30-day persistent login
- Redirected to dashboard on success

### 3. **Create Event**
- Click "Create New Event" button
- Fill in event details (title, date, location, description)
- Submit to save event

### 4. **View Events**
- Dashboard displays all your events
- Events sorted by date (newest first)

### 5. **Edit Event**
- Click "Edit" button on any event
- Modify event details
- Submit to update

### 6. **Delete Event**
- Click "Delete" button on any event
- Confirm deletion
- Event removed from database

### 7. **Logout**
- Click "Logout" in navigation bar
- Session destroyed, cookies cleared
- Redirected to login page

---


##  Security Features Summary

| Security Feature | Implementation | Purpose |
|-----------------|----------------|---------|
| **Session Management** | HTTP-only, SameSite cookies | Prevent XSS and CSRF |
| **Session Regeneration** | After login/logout | Prevent session fixation |
| **Password Hashing** | Bcrypt algorithm | Secure password storage |
| **Prepared Statements** | PDO with bound parameters | Prevent SQL injection |
| **Input Sanitization** | `htmlspecialchars()`, `strip_tags()` | Prevent XSS attacks |
| **Cookie Security** | HTTP-only, secure tokens | Prevent cookie theft |
| **Access Control** | Session validation | Authorized access only |
| **Data Isolation** | User-specific queries | Users see only their data |

---

##  Security Checklist

- [x] Passwords are hashed, never stored in plain text
- [x] All SQL queries use prepared statements
- [x] All user inputs are sanitized
- [x] All outputs are escaped
- [x] Sessions use HTTP-only cookies
- [x] Session IDs regenerated after authentication
- [x] CSRF protection via SameSite cookies
- [x] Users can only access their own data
- [x] Failed login doesn't reveal if user exists
- [x] Sessions destroyed completely on logout

---

##  Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a new branch (`git checkout -b feature/improvement`)
3. Make your changes
4. Commit your changes (`git commit -am 'Add new feature'`)
5. Push to the branch (`git push origin feature/improvement`)
6. Create a Pull Request

---

##  License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**Your Name**
- GitHub: [Dosite_Cyiza](https://github.com/Dosite_cyiza)
- Email: dositecyiza@gmail.com

---

##  Known Issues

None at the moment. Please report any bugs in the Issues section.

---

##  Future Enhancements

- [x] Email verification on registration
- [x] Password reset functionality
- [x] User profile management
- [x] Event search and filtering
- [x] Event categories
- [x] Image upload for events
- [x] Email notifications
- [x] Two-factor authentication
- [x] Admin panel

---

**⭐ If you found this project helpful, please give it a star!**

---

**Last Updated:** December 2024
