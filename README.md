# Academic Management System

A PHP and MySQL web application for managing courses, assignments, submissions, grades, and student-teacher workflows.

## Overview

The Academic Management System is a web application developed as part of my Computer Science studies.

The application provides separate functionality for students and teachers, allowing users to manage academic activities through role-based dashboards.

Students can view their assignments, submit their work, update existing submissions, and view grades and teacher feedback.

Teachers can manage their courses, create assignments, review student submissions, and provide grades and feedback.

## Features

### Authentication

- User registration and login
- Student and teacher roles
- Role-based access control
- Session-based authentication
- Secure password hashing
- Automatic migration of older passwords to hashed passwords

### Student Features

- View enrolled courses
- View available assignments
- Submit assignment work
- Update existing submissions
- View submission status
- View grades
- View teacher feedback

### Teacher Features

- View managed courses
- View enrolled student counts
- Create assignments for specific courses
- View student submissions
- Grade student work
- Provide feedback
- Access only courses and submissions belonging to the teacher

## Technologies

- PHP
- MySQL
- HTML
- CSS
- XAMPP
- phpMyAdmin

## Database

The application uses a MySQL database named:

```text
university_db
```

Main database tables include:

```text
users
courses
enrollments
assignments
submissions
```

The database stores information about users, courses, student enrollments, assignments, submissions, grades, and feedback.

## Security

The project includes several security and validation measures:

- Password hashing using PHP `password_hash()`
- Password verification using `password_verify()`
- Prepared statements for sensitive database operations
- Session regeneration after successful login
- Role-based page protection
- Server-side validation
- Teacher ownership checks for courses and submissions
- Student enrollment checks before assignment submission
- HTML output escaping using `htmlspecialchars()`

## Running the Project

### Requirements

- XAMPP
- Apache
- MySQL
- PHP
- phpMyAdmin

### Setup

1. Clone or download the repository.

2. Place the project folder inside the XAMPP `htdocs` directory.

Example:

```text
C:\xampp\htdocs\academic-management-system
```

3. Start Apache and MySQL from the XAMPP Control Panel.

4. Create a MySQL database named:

```text
university_db
```

5. Import the provided database SQL file into `university_db` using phpMyAdmin.

6. Open the project through localhost in your browser.

Example:

```text
http://localhost/academic-management-system/
```

## Database Connection

The project uses the following local XAMPP database configuration:

```php
$conn = mysqli_connect("localhost", "root", "", "university_db");
```

If your MySQL configuration is different, update the database connection settings accordingly.

## What I Learned

Through this project I gained practical experience with:

- PHP web development
- MySQL databases
- User authentication
- Password hashing
- Sessions
- Role-based access control
- Database relationships
- Prepared statements
- Server-side validation
- CRUD operations
- Student and teacher workflows
- Assignment submission systems
- Grading and feedback functionality

## Author

**Stavros Tzanavaris**

Computer Science Student
