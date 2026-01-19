-- Create database
CREATE DATABASE IF NOT EXISTS Toth CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE Toth;

-- Drop existing tables (for clean install)
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS students;

-- Students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Courses table
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Enrollments table
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id),
    INDEX idx_student (student_id),
    INDEX idx_course (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample courses
INSERT INTO courses (title, description) VALUES
('Introduction to PHP', 'Learn the basics of PHP programming, including variables, loops, functions, and OOP concepts.'),
('MySQL Database Design', 'Master database design principles, normalization, and SQL queries. Optimize your data structures.'),
('Web Security Fundamentals', 'Understand common web vulnerabilities, secure coding practices, and protection against XSS, SQL injection, and CSRF attacks.'),
('Advanced OOP in PHP', 'Deep dive into object-oriented programming with design patterns, interfaces, abstract classes, and trait usage.'),
('RESTful API Development', 'Build scalable REST APIs with PHP, implementing proper error handling, authentication, and HTTP best practices.'),
('Frontend Frameworks Integration', 'Integrate your PHP backend with modern frontend frameworks like Vue.js or React.'),
('Performance Optimization', 'Learn caching strategies, database optimization, and code profiling to build lightning-fast applications.'),
('Testing & Quality Assurance', 'Write unit tests, integration tests, and implement CI/CD pipelines for robust PHP applications.');
