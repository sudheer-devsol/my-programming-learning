-- ===================================================================
-- Full-Stack Collaborative LMS - Database Schema
-- Import this file into phpMyAdmin / MySQL / MariaDB
-- ===================================================================

CREATE DATABASE IF NOT EXISTS lms_db;
USE lms_db;

-- -------------------------------------------------------------
-- USERS  (Admin, Teacher, Student all in one table with a role)
-- -------------------------------------------------------------
CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('admin','teacher','student') NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- -------------------------------------------------------------
-- COURSES
-- -------------------------------------------------------------
CREATE TABLE course (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_title VARCHAR(150) NOT NULL,
    course_description TEXT,
    course_image VARCHAR(255) DEFAULT NULL,
    teacher_id INT NOT NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES user(user_id) ON DELETE CASCADE
);

-- -------------------------------------------------------------
-- COURSE ENROLLMENT  (student <-> course, many to many)
-- -------------------------------------------------------------
CREATE TABLE course_enrollment (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    enrolled_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES course(course_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES user(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (course_id, student_id)
);

-- -------------------------------------------------------------
-- LECTURES
-- -------------------------------------------------------------
CREATE TABLE lecture (
    lecture_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    lecture_title VARCHAR(150) NOT NULL,
    lecture_description TEXT,
    lecture_content TEXT,
    video_link VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES course(course_id) ON DELETE CASCADE
);

-- -------------------------------------------------------------
-- LEARNING MATERIALS  (uploaded files, can belong to a lecture or just a course)
-- -------------------------------------------------------------
CREATE TABLE material (
    material_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    lecture_id INT DEFAULT NULL,
    material_title VARCHAR(150) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES course(course_id) ON DELETE CASCADE,
    FOREIGN KEY (lecture_id) REFERENCES lecture(lecture_id) ON DELETE SET NULL
);

-- -------------------------------------------------------------
-- ASSIGNMENTS
-- -------------------------------------------------------------
CREATE TABLE assignment (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    deadline DATETIME NOT NULL,
    attachment VARCHAR(255) DEFAULT NULL,
    status ENUM('active','closed') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES course(course_id) ON DELETE CASCADE
);

-- -------------------------------------------------------------
-- ASSIGNMENT SUBMISSIONS
-- -------------------------------------------------------------
CREATE TABLE assignment_submission (
    submission_id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT NOT NULL,
    student_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    marks INT DEFAULT NULL,
    feedback TEXT DEFAULT NULL,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assignment_id) REFERENCES assignment(assignment_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES user(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_submission (assignment_id, student_id)
);

-- -------------------------------------------------------------
-- PROJECTS
-- -------------------------------------------------------------
CREATE TABLE project (
    project_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    project_title VARCHAR(150) NOT NULL,
    project_description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES course(course_id) ON DELETE CASCADE
);

-- -------------------------------------------------------------
-- PROJECT GROUPS
-- -------------------------------------------------------------
CREATE TABLE project_group (
    group_id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    group_name VARCHAR(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES project(project_id) ON DELETE CASCADE
);

-- -------------------------------------------------------------
-- PROJECT GROUP MEMBERS
-- -------------------------------------------------------------
CREATE TABLE project_group_member (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    student_id INT NOT NULL,
    joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES project_group(group_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES user(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_member (group_id, student_id)
);

-- -------------------------------------------------------------
-- CHAT GROUPS  (one row per course chat, one row per project-group chat)
-- -------------------------------------------------------------
CREATE TABLE chat_group (
    chat_group_id INT AUTO_INCREMENT PRIMARY KEY,
    chat_type ENUM('course','project') NOT NULL,
    course_id INT DEFAULT NULL,
    project_group_id INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES course(course_id) ON DELETE CASCADE,
    FOREIGN KEY (project_group_id) REFERENCES project_group(group_id) ON DELETE CASCADE
);

-- -------------------------------------------------------------
-- CHAT MESSAGES
-- -------------------------------------------------------------
CREATE TABLE chat_message (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    chat_group_id INT NOT NULL,
    sender_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chat_group_id) REFERENCES chat_group(chat_group_id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES user(user_id) ON DELETE CASCADE
);

-- -------------------------------------------------------------
-- PROJECT PROGRESS UPDATES
-- -------------------------------------------------------------
CREATE TABLE project_update (
    update_id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    student_id INT NOT NULL,
    update_text TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES project_group(group_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES user(user_id) ON DELETE CASCADE
);

-- -------------------------------------------------------------
-- NOTIFICATIONS
-- -------------------------------------------------------------
CREATE TABLE notification (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message VARCHAR(255) NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

-- ===================================================================
-- DEFAULT ADMIN ACCOUNT
-- Email: admin@lms.com   Password: admin123
-- (password below is a bcrypt hash of "admin123")
-- ===================================================================
INSERT INTO user (role, first_name, last_name, email, password, status)
VALUES ('admin', 'System', 'Admin', 'admin@lms.com', '$2y$10$JPZBu2Y6XfayoceUchz5QuAfHZ1zt9HeXzZt617M/cd3hv7JTmFSG', 'active');
-- This hash was generated with PHP's password_hash('admin123', PASSWORD_DEFAULT)
-- and verified to work. If it ever fails to login on your server, regenerate with:
-- php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
-- and UPDATE user SET password = '<new hash>' WHERE email='admin@lms.com';
