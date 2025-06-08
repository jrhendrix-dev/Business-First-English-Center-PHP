# Business First English Center

## Overview

Business First English Center is a web-based application designed to streamline the management of an academic institution. It enables administrators to manage users, classes, grades, and schedules efficiently. The system offers dedicated views and interactions for administrators, teachers, and students.

## Features

- **Secure HTTPS**: All data transmission is encrypted using HTTPS, ensuring user credentials and sensitive information are protected.
- **User Authentication**: Secure login system with session management, brute-force protection, and session security best practices.
- **Role-Based Access Control**: Different interfaces and privileges for Admins, Teachers, and Students.
- **Class Management**: Create, edit, assign, and delete classes.
- **Grade Book**: Record, update, and view student grades across trimesters.
- **Schedule Management**: Assign daily class schedules dynamically.
- **Responsive Design**: Built with Bootstrap for compatibility on all devices.
- **Persistent Storage**: MySQL backend with robust data structure.
- **SQL Triggers**: Uses MySQL triggers to automate and enforce data integrity for student creation, updates, and deletions.
- **Brute Force Protection**: Login system includes rate limiting and lockout countdown to prevent brute force attacks.

## Screenshots

### Home Banner
![Banner](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/pics/banner.jpg)
The main landing page of the application, featuring the banner and introductory interface.

---

### Responsive Layout
![Responsive](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/screenshots/Responsive.png)  
Demonstrates how the application adapts to various screen sizes, ensuring usability across devices.

---

### Admin User List
![AdminUserList](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/screenshots/AdminUserList.png)  
Displays the administrative interface for managing users, including search and filtering options.

---

### Create User Form
![AdminUserCreate](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/screenshots/AdminUserCreate.png)  
Form used to register new users, including roles, classes, and validation constraints.

---

### Admin Form Controls
![AdminFormControl](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/screenshots/AdminFormControl.png)  
Field-level controls and validation logic implemented for user and class data.

---

### Grade Editing Panel
![AdminGradesEdit](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/screenshots/AdminGradesEdit.png)  
Interface for administrators to view, assign, or update student grades per subject.

---

### Trigger: Create Student
![TriggerStudentCreate](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/screenshots/TriggerStudentCreate.png)  
Internal trigger logic that handles the creation of a new student record in the system.

---

### Trigger: Update Student
![TriggerStudentUpdate](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/screenshots/TriggerStudentUpdate.png)  
Trigger and interface to update student data and reflect changes in the database.

---

### Trigger: Delete Student
![TriggerStudentDelete](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/screenshots/TriggerStudentDelete.png)  
Deletion workflow with confirmation steps to ensure safe record removal.

---

### Application Footer
![Footer](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/screenshots/Footer.png)  
The footer section of the application, containing version info and navigation links.

## Security

- **HTTPS**: All pages and forms are served over HTTPS for encrypted communication.
- **Session Security**: Session cookies are set with `HttpOnly` and `Secure` flags.
- **Brute Force Protection**: Login attempts are rate-limited with a lockout countdown and visual feedback.
- **Password Hashing**: Passwords are securely hashed using PHP's `password_hash()` and verified with `password_verify()`.

## Database & Triggers

- **MySQL Triggers**: The application uses SQL triggers to automatically manage related records when students are created, updated, or deleted. This ensures data consistency and reduces the risk of orphaned or inconsistent data.
- **Schema**: See `/schema.sql` for table definitions and trigger logic.

## Technologies Used

- PHP 7+
- MySQL
- jQuery + AJAX
- Bootstrap 4
- HTML5/CSS3

## Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/business-first-english-center.git
   cd business-first-english-center
   ```
2. **Configure the Database**

   - Import the schema.sql (or equivalent) into your MySQL server.

   - Update your DB credentials in /src/models/Database.php.

3. **Run the Server**

   - Serve the project using Apache or PHP’s built-in server:
```bash
      php -S localhost:8000 -t public/
```

4. **Access the App**
   - Open your browser and go to http://localhost:8000.

## Project Structure
```
Business-First-English-Center/
│
├── public/                  # Public-facing entry point and assets
│   ├── assets/              # CSS, JS, images
│   └── index.php
│
├── src/
│   ├── controllers/         # Backend handlers (CRUD)
│   └── models/              # DB connection & logic
│
├── views/                   # Reusable view fragments (HTML/PHP)
├── includes/                # Shared functions and session logic
└── README.md

```

# Project Workflow

This section explains the typical workflow of the **Business First English Center** web application, from user entry to feature usage, covering both user experience and system processes.

---

## 1. **User Access and Entry Point**

- Users (Admins, Teachers, Students, or Visitors) access the application via the main entry point:  
  `public/index.php`
- The application enforces secure session management by setting strict session cookie parameters before starting the session.
- The header (`views/header.php`) is included, providing navigation and login/logout controls based on session state.

---

## 2. **Authentication and Role-Based Access**

- **Visitors** can browse public information and view available courses and services.
- **Login Modal**:  
  - Users can log in via a modal form in the header.
  - Credentials are submitted (typically via AJAX) and validated against the database.
  - Upon successful login, the session is updated with user information and role (`lvl`).
- **Role-Based Navigation**:  
  - The navigation bar dynamically changes based on the user's role:
    - **Admin**: Access to admin dashboard and management features.
    - **Teacher**: Access to teacher dashboard and class/grade management.
    - **Student**: Access to student dashboard and personal grades/schedule.

---

## 3. **Feature Access and Core Functionality**

- **Class Management** (Admins):
  - Create, edit, assign, and delete classes.
  - Assign teachers and students to classes.
- **Grade Book** (Teachers):
  - Record, update, and view student grades for each trimester.
- **Schedule Management** (Admins/Teachers):
  - Assign and view daily class schedules.
- **Student Portal**:
  - View assigned classes, schedules, and grades.

---

## 4. **Data Flow and Persistence**

- All data operations (CRUD) are handled by controllers in `src/controllers/`.
- Business logic and database interactions are managed by models in `src/models/`.
- Data is stored in a MySQL database, with triggers enforcing integrity for student records.
- AJAX is used for dynamic updates and form submissions, providing a responsive user experience.

---

## 5. **Security and Best Practices**

- **HTTPS** is enforced for all data transmission.
- **Session Security**:  
  - Cookies are set as `HttpOnly` and `Secure` (when using HTTPS).
  - Session management prevents unauthorized access.
- **Brute Force Protection**:  
  - Login attempts are rate-limited and lockouts are enforced after repeated failures.
- **Role-Based Access Control**:  
  - Each user role has access only to permitted features and views.

---

## 6. **Frontend and User Experience**

- **Responsive Design**:  
  - The UI is built with Bootstrap 4 for compatibility across devices.
- **Reusable Views**:  
  - Common UI components (header, footer, modals) are included across pages for consistency.
- **Dynamic Content**:  
  - jQuery and AJAX are used for interactive elements and real-time updates.

---


## 7. **Typical User Workflow Example**

1. **Visitor** lands on the homepage and browses available courses.
2. **User** clicks "Iniciar Sesión" and logs in.
3. **System** authenticates and redirects user to their dashboard based on role.
4. **Admin** manages classes, teachers, and students.
5. **Teacher** records grades and manages class schedules.
6. **Student** views their grades and class schedule.
7. **All users** can log out securely, ending their session.

---

For more details on installation and setup, see the [Installation](#installation) section above.
## Contributing
1. Fork the repository

2. Create a feature branch: git checkout -b feature-name

3. Commit your changes: git commit -m "Add feature"

4. Push to the branch: git push origin feature-name

5. Submit a Pull Request

## Future Improvements
- Add Teacher and Student dashboards

- Add CSV export for reports

- Enable messaging between users

- Add automated testing (PHPUnit)

- Translate UI for multilingual support

## License
This project is licensed under the [MIT License](https://mit-license.org/).