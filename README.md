# Thoth - LMS Platform

## 📚 Project Overview

**Thoth** is a simple yet solid **Learning Management System (LMS)** foundation built with PHP, following the **MVC (Model-View-Controller)** architecture pattern. It demonstrates:

- ✅ Clean MVC architecture with separated concerns
- ✅ Secure authentication system for students
- ✅ Route-based access control (public & protected pages)
- ✅ Course management and student enrollments
- ✅ Security best practices (password hashing, CSRF protection, prepared statements)
- ✅ PDO for database interactions

This project serves as a **learning foundation** for building more complex LMS features like grading, assignments, and course analytics.

---

## 🎯 Core Learning Objectives

By completing this project, you'll understand:

1. **MVC Architecture** - How to separate business logic, request handling, and presentation
2. **Routing & URL Mapping** - How to map URLs to controller actions
3. **Database Access with PDO** - Safe database queries using prepared statements
4. **Authentication & Sessions** - Secure login systems with session management
5. **CSRF & Security** - Protecting forms and user data
6. **Code Organization** - Maintainable, scalable project structure

---

## 🚀 Quick Start

### Prerequisites
- PHP 7.4+ with PDO MySQL extension
- MySQL 5.7+
- Apache with mod_rewrite enabled (or similar web server)

### Installation

1. **Clone or download the project:**
   ```bash
   cd /path/to/toth
   ```

2. **Create the database:**
   - Open your MySQL client (phpMyAdmin, CLI, etc.)
   - Import `database.sql`:
   ```bash
   mysql -u root -p < database.sql
   ```
   - Or manually run the SQL commands from `database.sql`

3. **Configure database connection** (if needed):
   - Edit `config/config.php`
   - Update `dsn`, `user`, and `pass` for your MySQL setup
   ```php
   'dsn' => 'mysql:host=127.0.0.1;dbname=Toth;charset=utf8mb4',
   'user' => 'root',
   'pass' => '',
   ```

4. **Start a local server:**
   ```bash
   cd public
   php -S localhost:8000
   ```

5. **Open in browser:**
   ```
   http://localhost:8000
   ```

---

## 📋 Features

### ✨ Authentication System
- **Register** - Students create accounts with email & password validation
- **Login** - Secure login using password hashing (PASSWORD_DEFAULT)
- **Logout** - Destroy session securely
- **Session Management** - Track logged-in users

### 📖 Course Management
- **Browse Courses** - View all available courses on dashboard
- **Course Details** - Read full course description
- **Enroll in Course** - Join courses with one click
- **View Enrollments** - See which courses you're enrolled in

### 🔐 Security Features
- **Password Hashing** - Using `password_hash()` with PHP defaults
- **CSRF Protection** - Tokens on all forms
- **Prepared Statements** - PDO prevents SQL injection
- **Input Validation** - Server-side validation of all inputs
- **Output Escaping** - `htmlspecialchars()` prevents XSS
- **Session Security** - Regenerate session IDs on logout

---

## 🛣️ Routes

### Public Routes (No Login Required)
| Method | Route | Controller | Description |
|--------|-------|-----------|-------------|
| GET | `/` | HomeController::index() | Home page |
| GET | `/register` | StudentController::showRegister() | Registration form |
| POST | `/register` | StudentController::register() | Process registration |
| GET | `/login` | StudentController::showLogin() | Login form |
| POST | `/login` | StudentController::login() | Process login |

### Protected Routes (Login Required)
| Method | Route | Controller | Description |
|--------|-------|-----------|-------------|
| POST | `/logout` | StudentController::logout() | Logout user |
| GET | `/student/dashboard` | StudentController::dashboard() | Student dashboard & courses list |
| GET | `/student/course/{id}` | StudentController::course() | Course details |
| POST | `/student/course/{id}/enroll` | StudentController::enroll() | Enroll in course |

---

## 🏗️ Project Structure

```
toth/
├── public/                 # Web root - only publicly accessible
│   ├── index.php          # Single entry point (front controller)
│   ├── .htaccess          # Apache URL rewriting
│   └── assets/
│       └── css/
│           └── app.css    # Styling
│
├── app/
│   ├── core/              # Core framework files
│   │   ├── Router.php     # URL routing & dispatching
│   │   ├── BaseController.php  # Base controller with render() & redirect()
│   │   ├── Database.php   # PDO connection singleton
│   │   ├── Auth.php       # Session-based authentication
│   │   └── Csrf.php       # CSRF token generation & validation
│   │
│   ├── controllers/       # HTTP request handlers
│   │   ├── HomeController.php
│   │   └── StudentController.php
│   │
│   ├── models/            # Business logic & database access
│   │   ├── Student.php
│   │   ├── Course.php
│   │   └── Enrollment.php
│   │
│   └── views/             # HTML templates
│       ├── home.php
│       ├── layout/
│       │   ├── header.php
│       │   └── footer.php
│       └── student/
│           ├── register.php
│           ├── login.php
│           ├── dashboard.php
│           └── course.php
│
├── config/
│   └── config.php         # Database configuration
│
└── database.sql           # Database schema & sample data
```

---

## 🔄 MVC Flow Explanation

### How a Request Flows Through the App

```
1. Browser Request
   └─ GET /student/dashboard

2. Web Server
   └─ All requests routed to public/index.php (via .htaccess)

3. Router (Router.php)
   └─ Matches URL pattern to a controller action
   └─ Finds: StudentController::dashboard()

4. Controller (StudentController.php)
   └─ Handles the HTTP request
   └─ Calls models for data
   └─ Passes data to view

5. Model (Course.php, Enrollment.php)
   └─ Queries database using PDO
   └─ Returns data to controller

6. View (student/dashboard.php)
   └─ Receives data from controller
   └─ Renders HTML using the data

7. Response
   └─ Browser displays the page
```

### Example: User Registration Flow

```
User submits registration form
         ↓
POST /register → public/index.php
         ↓
Router matches → StudentController::register()
         ↓
Controller validates input (name, email, password)
         ↓
Model checks if email exists → Student::emailExists()
         ↓
Model creates user → Student::create()
    - Hashes password
    - Inserts into database
    - Returns new user ID
         ↓
Controller calls Auth::login() → Sets $_SESSION['student_id']
         ↓
Controller redirects to /student/dashboard
         ↓
User is now logged in
```

---

## 💾 Database Schema

### students Table
```sql
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,    -- Hashed with password_hash()
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### courses Table
```sql
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### enrollments Table
```sql
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id)
);
```

---

## 📊 UML Diagrams

### Use Case Diagram

```
┌─────────────────────────────────┐
│         Student (Actor)         │
└────────┬────────────────────────┘
         │
         ├─→ Register Account
         │     (Provide name, email, password)
         │
         ├─→ Login
         │     (Enter email & password)
         │
         ├─→ View Dashboard
         │     (See all courses & enrollments)
         │
         ├─→ View Course Details
         │     (Read course description)
         │
         ├─→ Enroll in Course
         │     (Click enroll button)
         │
         └─→ Logout
               (End session)
```

### Class Diagram

```
┌──────────────────────────┐
│       Student            │
├──────────────────────────┤
│ - id: int                │
│ - name: string           │
│ - email: string          │
│ - password: string (hash)│
├──────────────────────────┤
│ + authenticate(email, password): ?array
│ + emailExists(email): bool
│ + create(name, email, password): int
└──────────────────────────┘
           ↑
           │ authenticates
           │
           │         ┌──────────────────────────┐
           │         │      Enrollment          │
           │         ├──────────────────────────┤
           │         │ - id: int                │
           │         │ - student_id: int        │
           │         │ - course_id: int         │
           └─────────┤ - enrollment_date: date  │
                     ├──────────────────────────┤
                     │ + enroll(sid, cid): void
                     │ + isEnrolled(...): bool
                     │ + enrolledCourseIds(...): array
                     └──────────────────────────┘
                              ↓ enrolls
                              │ in
                              ↓
                     ┌──────────────────────────┐
                     │       Course             │
                     ├──────────────────────────┤
                     │ - id: int                │
                     │ - title: string          │
                     │ - description: string    │
                     ├──────────────────────────┤
                     │ + all(): array
                     │ + find(id): ?array
                     └──────────────────────────┘
```

### Sequence Diagram: Login Flow

```
Student          Browser         Router          Controller         Model        Session
  │                 │               │                 │              │             │
  ├─ Enter email    │               │                 │              │             │
  │ and password    │               │                 │              │             │
  ├─────────────→   │               │                 │              │             │
  │              POST /login         │                 │              │             │
  │              with CSRF token     │                 │              │             │
  │                 │────────────→   │                 │              │             │
  │                 │                ├─ match route   │              │             │
  │                 │                │                 │              │             │
  │                 │                ├──────────────→  │              │             │
  │                 │                │        StudentController::login()            │
  │                 │                │                 │              │             │
  │                 │                │                 ├─ validate   │             │
  │                 │                │                 │              │             │
  │                 │                │                 ├──────────→  │             │
  │                 │                │                 │    Student::authenticate()│
  │                 │                │                 │              │             │
  │                 │                │                 │   PDO query  │             │
  │                 │                │                 │← fetch user  │             │
  │                 │                │                 │              │             │
  │                 │                │                 ├─ password_verify()        │
  │                 │                │                 │              │             │
  │                 │                │                 ├──────────────────────────→│
  │                 │                │                 │              │    Auth::login()
  │                 │                │                 │              │   $_SESSION['student_id']
  │                 │                │                 │              │      set │
  │                 │                │                ← return        │←─────────┤
  │                 │                │                 │              │             │
  │                 │                │         redirect to dashboard  │             │
  │                 │                │←──────────────────────         │             │
  │                 │←───────────────────────────────────────────────             │
  │    ← Redirect to /student/dashboard                               │             │
  │    (logged in session active)                                     │             │
  └─────────────────────────────────────────────────────────────────────────────────
```

---

## 🔐 Security Implementation

### Password Security
```php
// Registration - Hashing
$hash = password_hash($password, PASSWORD_DEFAULT);  // ~2.5 seconds hashing time
$stmt->execute([$name, $email, $hash]);

// Login - Verification
if (password_verify($password, $student['password'])) {
    // Password is correct
}
```

### CSRF Protection
```php
// Generate token on form
<input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">

// Verify on submission
Csrf::verify($_POST['_csrf'] ?? null);  // Exits if invalid
```

### SQL Injection Prevention
```php
// ✅ SAFE - Prepared statement
$stmt = $pdo->prepare('SELECT * FROM students WHERE email = ?');
$stmt->execute([$email]);

// ❌ UNSAFE - Direct concatenation (NEVER DO THIS)
$query = "SELECT * FROM students WHERE email = '$email'";
```

### XSS Prevention
```php
// ✅ SAFE - Escape output
<h3><?= htmlspecialchars($course['title']) ?></h3>

// ❌ UNSAFE - Direct output
<h3><?= $course['title'] ?></h3>
```

### Session Security
```php
// On logout - Regenerate session ID
session_regenerate_id(true);
unset($_SESSION['student_id']);

// Protects against session fixation attacks
```

---

## 📝 Code Examples

### Authentication Check in Protected Page
```php
// In StudentController::dashboard()
public function dashboard(): void
{
    Auth::requireLogin();  // Redirects to /login if not authenticated
    
    $courseModel = new Course();
    $courses = $courseModel->all();
    
    $this->render('student/dashboard', ['courses' => $courses]);
}
```

### Creating a Student (Registration)
```php
// In StudentModel
public function create(string $name, string $email, string $password): int
{
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $this->pdo->prepare(
        'INSERT INTO students (name, email, password) VALUES (?, ?, ?)'
    );
    $stmt->execute([$name, $email, $hash]);
    
    return (int)$this->pdo->lastInsertId();
}
```

### Rendering a View with Data
```php
// In Controller
$this->render('student/dashboard', [
    'courses' => $courses,
    'enrolledIds' => $enrolledIds,
]);

// In View (student/dashboard.php)
<?php foreach ($courses as $course): ?>
    <h3><?= htmlspecialchars($course['title']) ?></h3>
    <?php if (in_array($course['id'], $enrolledIds)): ?>
        <span class="badge">Enrolled</span>
    <?php endif; ?>
<?php endforeach; ?>
```

---

## 🧪 Testing the Application

### 1. Test Registration
- Go to http://localhost:8000/register
- Fill in: Name, email, password (8+ characters)
- Click "Create account"
- Verify you're redirected to dashboard

### 2. Test Login
- Go to http://localhost:8000/login
- Enter registered email and password
- Click "Login"
- Verify you're logged in

### 3. Test Course Enrollment
- On dashboard, click "Details" on a course
- Click "Enroll" button
- Verify enrollment is saved
- Verify "Enrolled" badge appears on dashboard

### 4. Test Security
- Try accessing /student/dashboard without login
- You should be redirected to /login
- Inspect network tab - verify password is sent over HTTPS (in production)

---

## 🎓 Learning Path

### Level 1: Understanding the Basics
1. Read `public/index.php` - Single entry point
2. Read `app/core/Router.php` - How URLs are mapped
3. Read `app/controllers/HomeController.php` - Simple controller

### Level 2: Authentication
1. Study `app/models/Student.php` - Password hashing & verification
2. Study `app/core/Auth.php` - Session management
3. Trace a login flow manually

### Level 3: Protected Pages
1. Read `app/core/BaseController.php` - View rendering
2. Study `StudentController::dashboard()` - Protected route
3. Test accessing protected pages without login

### Level 4: Security Deep Dive
1. Review CSRF protection in `app/core/Csrf.php`
2. Check prepared statements in models
3. Examine output escaping in views

### Level 5: Extension Tasks
- Add a "forgot password" feature
- Implement course categories
- Add pagination to course listing
- Create an admin dashboard
- Add student profile editing

---

## 🚀 Next Steps & Extensions

### Easy Additions
- [ ] Add timestamps to display "Joined 2 days ago"
- [ ] Implement course search/filter
- [ ] Add student profile page

### Medium Difficulty
- [ ] Add instructor role (separate from student)
- [ ] Implement course prerequisites
- [ ] Add email verification on registration
- [ ] Create password reset feature

### Advanced Features
- [ ] Add assignments/quiz system
- [ ] Implement grading system
- [ ] Create course analytics dashboard
- [ ] Add real-time notifications
- [ ] Build REST API endpoints

---

## 🛠️ Technology Stack

| Component | Technology | Version |
|-----------|-----------|---------|
| Language | PHP | 7.4+ |
| Database | MySQL | 5.7+ |
| Architecture | MVC Pattern | - |
| ORM | PDO (prepared) | Built-in |
| Session | PHP Native Sessions | - |
| Security | password_hash, CSRF tokens | Built-in |

---

## 📞 Support & Questions

For learning resources:
- [PHP Official Documentation](https://www.php.net/docs.php)
- [PDO Security](https://www.php.net/manual/en/pdo.prepared-statements.php)
- [OWASP Security Practices](https://owasp.org/)
- [MVC Pattern Explanation](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)

---

## 📄 License

This is a learning project. Feel free to use and modify for educational purposes.

---

**Happy Learning! 🎓**
#   T h o t h  
 #   T h o t h  
 