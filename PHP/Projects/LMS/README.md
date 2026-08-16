# Collaborative Learning Management System (LMS)

A full-stack Learning Management System built with **HTML, CSS, Bootstrap 5, Vanilla JavaScript, XMLHttpRequest/AJAX, PHP (MySQLi), and MySQL/MariaDB**.

The system provides separate experiences for **Admin, Teacher, and Student** users, with features for course management, enrollment, lectures, learning materials, assignments, grading, group projects, project groups, notifications, and group communication.

## Features

### Admin

* Admin authentication
* Manage teachers
* Manage students
* Create and manage courses
* Assign teachers to courses
* Monitor course and user activities
* Manage the overall LMS environment

### Teacher

* Teacher dashboard
* Manage assigned courses
* Upload course lectures
* Upload learning materials
* Create assignments
* Review student submissions
* Grade assignments with marks and feedback
* Create group projects
* Create and manage project groups
* Assign students to project groups
* Communicate with enrolled students
* Participate in private project group chats
* Receive notifications for relevant activities

### Student

* Student registration and login
* Browse available courses
* Enroll in multiple courses
* Access enrolled courses
* View lectures and learning materials
* Submit assignments through file uploads
* View marks and teacher feedback
* Participate in group projects
* Post project progress updates
* Access course-wide group chats
* Participate in private project group chats
* Receive notifications for important course activities

## Course & Enrollment System

The LMS allows students to browse available courses and enroll in multiple courses.

Each course is managed by an assigned teacher who can provide lectures, learning materials, assignments, and projects to enrolled students.

Once a student enrolls in a course, they gain access to the learning content and the course's common group chat.

## Assignments & Grading

Teachers can create assignments for their courses, while students can submit their work through file uploads.

Teachers can review submissions and provide:

* Marks
* Feedback
* Submission status

Students can then view their results and teacher feedback from their learning area.

## Group Projects

Teachers can create group projects within their courses and organize students into project groups.

Students can be assigned to project groups, where they can collaborate with their group members and teacher.

Each project group has its own private chat that is accessible only to the group's members and the assigned teacher.

Students can also post project progress updates within their project group.

## Chat System

The LMS provides two levels of group communication.

### Course Group Chat

Each enrolled course has a common group chat that includes:

* The course teacher
* All enrolled students

### Project Group Chat

Each project group has a private chat available only to:

* The project teacher
* Members of that project group

This allows students to communicate about their course and project work in an organized environment.

## Notifications

The system generates notifications for important activities, including:

* New lectures
* New learning materials
* New assignments
* New projects
* Course enrollments
* Assignment submissions
* Assignment grading

This helps users stay informed about activity within their courses.

## Requirements

To run the LMS locally, you need:

* XAMPP or another Apache + PHP 8+ + MySQL/MariaDB environment
* A web browser

## Installation

### 1. Copy the Project

Copy the complete `lms` folder into your XAMPP `htdocs` directory.

For Windows:

```text id="2p9z1j"
C:\xampp\htdocs\lms
```

For macOS:

```text id="p7k4n2"
/Applications/XAMPP/htdocs/lms
```

For Linux:

```text id="r8x3m1"
/opt/lampp/htdocs/lms
```

### 2. Start Apache and MySQL

Open the XAMPP Control Panel and start:

* Apache
* MySQL

### 3. Import the Database

Open phpMyAdmin:

```text id="x4q8k2"
http://localhost/phpmyadmin
```

Select **Import** and choose:

```text id="m6v2p9"
database/lms.sql
```

The SQL file creates the `lms_db` database, required tables, and the default admin account.

You can also import the database through the terminal:

```bash id="n7w3c5"
mysql -u root -p < database/lms.sql
```

### 4. Configure the Database

Open:

```text id="k3r9v1"
config/database.php
```

Make sure the database configuration matches your local MySQL setup:

```php id="d5m8q2"
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "lms_db";
```

These settings work with the common default XAMPP configuration where the MySQL `root` user has no password.

### 5. Run the Project

Open:

```text id="w2j6s4"
http://localhost/lms/
```

You will be redirected to the login page.

## Default Login

The database includes a default Admin account:

```text id="h9k4p7"
Email: admin@lms.com
Password: admin123
```

Teachers and Students can either register themselves through the **Register** page or be added directly by the Admin.

If the default admin password needs to be regenerated, you can create a new password hash using:

```bash id="q5n8x3"
php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
```

The generated hash can then be updated in the `user` table for `admin@lms.com`.

## Project Structure

```text id="c7m2v8"
lms/
├── index.php
├── login.php
├── register.php
├── logout.php
├── forgot_password.php
│
├── config/
│   └── database.php
│
├── includes/
│   └── Shared PHP functions, sidebars, and layouts
│
├── admin/
│   └── Admin dashboard and management pages
│
├── teacher/
│   └── Teacher dashboard and course management pages
│
├── student/
│   └── Student dashboard and learning pages
│
├── process/
│   └── PHP backend and AJAX handlers
│
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   └── uploads/
│       ├── materials/
│       ├── submissions/
│       └── profile/
│
└── database/
    └── lms.sql
```

## How the System Works

The main workflow of the LMS is:

1. The **Admin** manages teachers, students, and courses.
2. The Admin assigns a teacher to each course.
3. **Students** register or are added by the Admin.
4. Students browse available courses and enroll in multiple courses.
5. Enrolled students receive access to their course content and common course chat.
6. **Teachers** manage course content by adding lectures, learning materials, assignments, and projects.
7. Students submit their assignment work through file uploads.
8. Teachers review submissions and provide marks and feedback.
9. Teachers create project groups and assign students to them.
10. Students collaborate within their project groups and share progress updates.
11. Project groups have private chats for communication between the teacher and group members.
12. The system generates notifications when important course activities occur.

## Coding Style

The project uses a simple and beginner-friendly development approach.

### Frontend

* HTML
* CSS
* Bootstrap 5
* Vanilla JavaScript
* XMLHttpRequest/AJAX

AJAX requests are handled using raw `XMLHttpRequest`. The project does not use Fetch, jQuery, or Axios.

### Backend

The backend is built with PHP and uses **MySQLi prepared statements** for database operations.

The application uses:

```php id="v4s8n2"
mysqli_prepare()
mysqli_stmt_bind_param()
mysqli_stmt_execute()
mysqli_stmt_get_result()
```

### AJAX Responses

Backend AJAX handlers return simple responses such as:

```text id="b6q1x9"
success
error
duplicate
```

or simple HTML where required, such as chat content.

The application does not use JSON responses for its AJAX operations.

### File Uploads

Course images, learning materials, and assignment submissions are handled using normal HTML forms with:

```text id="j3m7c5"
enctype="multipart/form-data"
```

and PHP's `$_FILES`.

File uploads are not handled through AJAX or JavaScript `FormData`.

### Authentication & Security

Passwords are protected using PHP's built-in password functions:

```php id="r8p2w6"
password_hash()
password_verify()
```

User output is escaped with:

```php id="m5v9k1"
htmlspecialchars()
```

Database queries use prepared statements to help prevent SQL injection.

## Production Security

The project is intended primarily for learning, demonstration, and practical development.

Before deploying it to a production environment, additional security measures should be implemented, including:

* Serve the application over HTTPS.
* Configure secure session cookies.
* Enable appropriate `HttpOnly` and `Secure` cookie settings.
* Add CSRF protection to state-changing forms.
* Add rate limiting to login and registration.
* Restrict script execution inside upload directories.
* Configure the web server to prevent uploaded files from being executed as PHP scripts.
* Review file upload validation and permissions before public deployment.

## Project Purpose

This LMS was developed as a practical full-stack PHP project to demonstrate how a complete learning platform can be built using PHP, MySQL, JavaScript, AJAX, and Bootstrap.

It brings together authentication, role-based access, course management, database relationships, enrollment, learning materials, assignments, file uploads, grading, group projects, chat functionality, notifications, and admin management into one application.

The project provides practical experience with both frontend and backend development while keeping the implementation straightforward and understandable.
