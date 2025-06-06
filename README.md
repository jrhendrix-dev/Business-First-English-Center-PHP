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