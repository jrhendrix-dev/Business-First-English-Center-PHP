
# Business First English Center — Documentation

A complete web-based management system for an English academy focused on corporate language training.

---

## Overview

This system is designed to manage users, classes, grades, and schedules within an educational institution. Built with core PHP, MySQL, JavaScript, and Bootstrap, it demonstrates modular MVC-inspired architecture, strong input validation, and AJAX-based interactivity.

- **GitHub**: [github.com/jrhendrix-dev](https://github.com/jrhendrix-dev)
- **LinkedIn**: [linkedin.com/in/jonathan-hendrix-dev](https://www.linkedin.com/in/jonathan-hendrix-dev)

---

## Project Structure

```
Business-First-English-Center/
├── public/               # Public entry point (index.php), assets, and routes
│   ├── index.php         # Landing page
│   └── assets/           # Static assets (CSS, JS, images)
├── src/
│   ├── controllers/      # Backend logic: CRUD handlers (create.php, update.php, delete.php)
│   ├── models/           # Database logic (Database.php)
│   ├── views/            # HTML/PHP templates (admin UI, forms)
│   └── includes/         # Session management and utility functions
├── README.md             # Project introduction and usage guide
└── documentation.md      # Technical breakdown and file purpose
```

---

## File and Folder Purposes

### `/public/`
- `index.php`: Entry point and main layout.
- `assets/css/index.css`: Custom styles including navbar, forms, layout.
- `assets/js/`: Modular JavaScript files (`usuarios.js`, `clases.js`, etc.) for AJAX and UI interactivity.
- `assets/pics/`: Static images used across the site.

### `/src/controllers/`
Handles all asynchronous logic via AJAX (CRUD operations).

- `create.php`: Create users, classes, schedules, and auto-fill missing IDs.
- `update.php`: Update existing records and maintain consistency (e.g., assign teacher/class).
- `delete.php`: Secure deletion with dependency cleanup.
- `adminHandlers.php`: Loads HTML tables on page load (users, grades, classes).

### `/src/models/`
- `Database.php`: Centralized database connection using MySQLi. Used across all controllers.

### `/src/views/`
- `dashboard.php`: Admin interface with Bootstrap tabs.
- `create.php`, `update.php`, `delete.php`: Reusable HTML fragments and logic.

### `/src/includes/`
- `functions.php`: Validates login, generates dropdowns, tracks session.

---

## Key Features

- **Secure login** with password hashing
- **Role-based access**: Admin, Teacher, Student
- **Real-time dropdown filtering** (e.g., only unassigned teachers appear in class creation)
- **AJAX-powered** table updates for smooth UX
- **Auto-gap ID logic**: Skips deleted IDs when creating new users/classes
- **Automatic foreign-key linking**: Student creation triggers notas row
- **Input validation and error handling** to prevent misuse or inconsistent state
- **Responsive layout** styled with Bootstrap
- **Documented JavaScript functions and PHPDoc**

---

## Deployment

Currently runs locally with **XAMPP**. For production:

- Migrate to Apache/Nginx with PHP 8.x
- Export SQL schema and configure env variables
- Protect `/controllers/`, `/models/`, and `/includes/` from public access

---






## Credits

Developed by **Jonathan Ray Hendrix**  
Email: jrhendrixdev@gmail.com

---

