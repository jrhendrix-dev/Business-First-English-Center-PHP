# Business First English Center

## Overview

Business First English Center is a web-based application designed to streamline the management of an academic institution. It enables administrators to manage users, classes, grades, and schedules efficiently. The system offers dedicated views and interactions for administrators, teachers, and students.

## Features

- 🔐 **User Authentication**: Secure login system with session management.
- 🧑‍🏫 **Role-Based Access Control**: Different interfaces and privileges for Admins, Teachers, and Students.
- 📋 **Class Management**: Create, edit, assign, and delete classes.
- 📝 **Grade Book**: Record, update, and view student grades across trimesters.
- 🗓️ **Schedule Management**: Assign daily class schedules dynamically.
- 📱 **Responsive Design**: Built with Bootstrap for compatibility on all devices.
- 💾 **Persistent Storage**: MySQL backend with robust data structure.

## Screenshots

### Home Banner
![BannerFrontPage](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/pics/BannerFrontPage.png)  
The main landing page of the application, featuring the banner and introductory interface.

---

### Responsive Layout
![Responsive](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/pics/Responsive.png)  
Demonstrates how the application adapts to various screen sizes, ensuring usability across devices.

---

### Admin User List
![AdminUserList](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/pics/AdminUserList.png)  
Displays the administrative interface for managing users, including search and filtering options.

---

### Create User Form
![AdminUserCreate](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/pics/AdminUserCreate.png)  
Form used to register new users, including roles, classes, and validation constraints.

---

### Admin Form Controls
![AdminFormControl](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/pics/AdminFormControl.png)  
Field-level controls and validation logic implemented for user and class data.

---

### Grade Editing Panel
![AdminGradesEdit](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/pics/AdminGradesEdit.png)  
Interface for administrators to view, assign, or update student grades per subject.

---

### Trigger: Create Student
![TriggerStudentCreate](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/pics/TriggerStudentCreate.png)  
Internal trigger logic that handles the creation of a new student record in the system.

---

### Trigger: Update Student
![TriggerStudentUpdate](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/pics/TriggerStudentUpdate.png)  
Trigger and interface to update student data and reflect changes in the database.

---

### Trigger: Delete Student
![TriggerStudentDelete](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/pics/TriggerStudentDelete.png)  
Deletion workflow with confirmation steps to ensure safe record removal.

---

### Application Footer
![Footer](https://github.com/jrhendrix-dev/Business-First-English-Center-PHP/blob/main/public/assets/pics/Footer.png)  
The footer section of the application, containing version info and navigation links.


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