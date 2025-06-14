# 📄 `dashboard_admin.php` — Admin Dashboard View

##  Purpose
This file serves as the **main admin interface** where the administrator can manage:

- Users (`usuarios.js`)
- Classes (`clases.js`)
- Grades (`notas.js`)
- Schedules (`horarios.js`)

It loads server-side PHP logic, injects data into the frontend via JavaScript, and ties into modular JS handlers for interactivity.

---

##  Load Order & Flow

### 1. Includes Dependencies

```php
require_once '../includes/adminHandlers.php';
require_once '../src/controllers/create.php';
require_once '../src/controllers/delete.php';
require_once '../src/controllers/update.php';
```

These include backend logic for creating, deleting, and updating entities.

### 2. Connects to the Database

```php
require_once '../src/models/Database.php';
$con = Database::connect();
```

### 3. Defines HTML `<option>` Lists

```php
if (!isset($classOptions)) $classOptions = '';
if (!isset($teacherOptions)) $teacherOptions = '';
```

Injected into JavaScript:
```html
<script>
    window.classOptions = `<?= $classOptions ?>`;
    window.teacherOptions = `<?= $teacherOptions ?>`;
</script>
```

---

##  Frontend Logic

### Tabbed Dashboard Layout

Each tab (Usuarios, Clases, Notas, Horarios):

- Loads its respective data.
- Submits forms (create/edit/delete).
- Hooks into modular JS (one per section).
- Renders using Bootstrap components.

### JavaScript Event Hooks

Each JS module handles:

- Tab switching
- AJAX submissions
- Dynamic dropdown refreshing
- Form resets

---

##  Data Flow Summary

| Action              | Source           | Sends to            | Method | Description                              |
|---------------------|------------------|----------------------|--------|------------------------------------------|
| Load dashboard      | PHP view         | —                    | —      | Renders admin layout                     |
| Create/edit user    | `usuarios.js`    | `create.php` / `update.php` | AJAX   | Handles user form submission             |
| Assign teacher      | `clases.js`      | `update.php`         | AJAX   | Assigns teacher to class, unassigns old  |
| Save grades         | `notas.js`       | `update.php`         | AJAX   | Validates and stores grade data          |
| Update schedule     | `horarios.js`    | `update.php`         | AJAX   | Saves class schedule per day             |
| Delete user/class   | delete logic     | `delete.php`         | AJAX   | Removes from DB                          |

---

## Security Considerations

- Server-side validation/sanitization is handled in PHP.
- `password_hash()` and `password_verify()` secure credentials.
- Inputs are validated before executing queries.

---

## Connected Files

- PHP Includes:
  - `adminHandlers.php`
  - `Database.php`
- JS Modules:
  - `usuarios.js`, `clases.js`, `notas.js`, `horarios.js`
- Styles:
  - `index.css`