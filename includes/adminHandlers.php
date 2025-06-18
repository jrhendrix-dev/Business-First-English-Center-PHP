<?php
/**
 * adminHandlers.php
 *
 * AJAX and utility handlers for the Business First English Center admin dashboard.
 * Handles:
 *   - User authentication
 *   - Dynamic dropdowns (classes, teachers)
 *   - Data retrieval for users, classes, grades, and schedules
 *   - Outputs HTML fragments for AJAX consumption
 *
 * PHP version 7+
 *
 * @package    BusinessFirstEnglishCenter
 * @author     Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license    MIT LICENSE
 */

// ============================================================================
// INITIALIZATION: Database Connection
// ============================================================================

if (!isset($con)) {
    require_once __DIR__ . '/../src/models/Database.php';
    try {
        $con = Database::connect();
    } catch (Exception $e) {
        // Optionally log error
    }
}

// ============================================================================
// DROPDOWN DATA HANDLERS
// ============================================================================

/**
 * Returns <option> elements for all available classes.
 * If a class ID is passed as `include`, that class is also shown even if it's already assigned.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['availableClasses'])) {
    // Optional override: allow a specific class to appear even if already assigned
    $includeClassId = isset($_GET['include']) ? intval($_GET['include']) : 0;

    // Query all unassigned classes (no teacher)
    $query = "
        SELECT classid, classname FROM clases
        WHERE classid NOT IN (
            SELECT class FROM users
            WHERE ulevel = 2 AND class IS NOT NULL AND class != $includeClassId
        )
    ";

    // Ensure the teacher's current class is included if specified
    if ($includeClassId > 0) {
        $query .= " UNION SELECT classid, classname FROM clases WHERE classid = $includeClassId"; //UNION adds this query to the other
    }

    $query .= " ORDER BY classname ASC";

    // Execute and output options
    $result = $con->query($query);
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['classid']}'>" . htmlspecialchars($row['classname'] ?? '') . "</option>";
    }
    exit;
}



/**
 * Handler for AJAX request: Get available teachers (not assigned to any class).
 * Outputs <option> elements for each available teacher.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['availableTeachers'])) {
    $query = "SELECT user_id, username FROM users WHERE ulevel = 2 AND (class IS NULL OR class = '' OR class = '0')";
    $result = $con->query($query);
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['user_id']}'>" . htmlspecialchars($row['username'] ?? '') . "</option>";
    }
    exit;
}

// ============================================================================
// PRELOAD DROPDOWN OPTIONS FOR JS (not direct AJAX handlers)
// ============================================================================

/**
 * Preloads all classes and teachers as HTML <option> strings for use in JS.
 */
$classOptions = "";
$classResult = $con->query("SELECT classid, classname FROM clases ORDER BY classid ASC");
if ($classResult && $classResult->num_rows > 0) {
    while ($row = $classResult->fetch_assoc()) {
        $classOptions .= "<option value='{$row['classid']}'>" . htmlspecialchars($row['classname'] ?? '') . "</option>";
    }
}

$teacherOptions = "";
$profResult = $con->query("SELECT user_id, username FROM users WHERE ulevel = 2 ORDER BY username ASC");
if ($profResult && $profResult->num_rows > 0) {
    while ($row = $profResult->fetch_assoc()) {
        $teacherOptions .= "<option value='{$row['user_id']}'>" . htmlspecialchars($row['username'] ?? '') . "</option>";
    }
}

$classOptionsJS = json_encode($classOptions);
$teacherOptionsJS = json_encode($teacherOptions);

// ============================================================================
// USER HANDLERS
// ============================================================================

/**
 * Handler for AJAX request: Load users list.
 * Outputs an HTML table with user data.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['loadUsers'])) {
    $res = $con->query("SELECT users.*, clases.classname FROM users LEFT JOIN clases ON users.class = clases.classid ORDER BY users.user_id ASC");
    if ($res && $res->num_rows > 0) {
        echo "<table class='table table-striped'><thead><tr><th>ID</th><th>Usuario</th><th>Email</th><th>Clase</th><th>Nivel</th><th>Acciones</th></tr></thead><tbody>";
        while ($row = $res->fetch_assoc()) {
            echo "<tr data-id='{$row['user_id']}'>
                    <td>{$row['user_id']}</td>
                    <td class='username'>" . htmlspecialchars($row['username'] ?? ''). "</td>
                    <td class='email'>" . htmlspecialchars($row['email'] ?? '') . "</td>
                    <td class='class' data-classid='{$row['class']}'>" . htmlspecialchars($row['classname'] ?? '') . "</td>
                    <td class='ulevel'>{$row['ulevel']}</td>
                    <td>
                        <button class='btn btn-sm btn-warning edit-btn'>Editar</button>
                        <button class='btn btn-sm btn-danger delete-btn'>Borrar</button>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No hay usuarios registrados.</p>";
    }
    exit;
}

// ============================================================================
// CLASS HANDLERS
// ============================================================================

/**
 * Handler for AJAX request: Load classes list.
 * Outputs an HTML table with class data.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['loadClasses'])) {
    $res = $con->query("SELECT * FROM clases LEFT JOIN users ON clases.classid=users.class AND users.ulevel='2'");
    if ($res && $res->num_rows > 0) {
        echo "<table class='table table-striped'><thead><tr><th>ID</th><th>Nombre Curso</th><th>Profesor</th><th>Acciones</th></tr></thead><tbody>";
        while ($row = $res->fetch_assoc()) {
            echo "<tr data-id='{$row['classid']}'>
                    <td>{$row['classid']}</td>
                    <td class='classname'>" . htmlspecialchars($row['classname']) . "</td>
                    <td class='profesor' data-profid='{$row['user_id']}'>" . htmlspecialchars($row['username'] ?? '') . "</td>
                    <td>
                        <button class='btn btn-sm btn-warning edit-class-btn'>Editar</button>
                        <button class='btn btn-sm btn-danger delete-class-btn'>Borrar</button>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No hay clases registradas.</p>";
    }
    exit;
}

// ============================================================================
// GRADES HANDLERS
// ============================================================================

/**
 * Handler for AJAX request: Load grades for all students.
 * Outputs an HTML table with grades.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['loadNotas'])) {
    $res = $con->query("SELECT u.user_id, u.username, c.classname, n.Nota1, n.Nota2, n.Nota3
                         FROM users u
                         LEFT JOIN clases c ON u.class = c.classid
                         LEFT JOIN notas n ON u.user_id = n.idAlumno
                         WHERE u.ulevel = 3
                         ORDER BY c.classname, u.username");
    if ($res && $res->num_rows > 0) {
        echo "<table class='table table-striped'><thead><tr>
                <th>Alumno</th><th>Curso</th><th>Nota 1</th><th>Nota 2</th><th>Nota 3</th><th>Acciones</th>
              </tr></thead><tbody>";
        while ($row = $res->fetch_assoc()) {
            echo "<tr data-id='{$row['user_id']}'>
                    <td class='alumno'>" . htmlspecialchars($row['username'] ?? '') . "</td>
                    <td class='curso'>" . htmlspecialchars($row['classname'] ?? '') . "</td>
                    <td class='nota1'>" . (isset($row['Nota1']) ? htmlspecialchars($row['Nota1'] ?? '') : '') . "</td>
                    <td class='nota2'>" . (isset($row['Nota2']) ? htmlspecialchars($row['Nota2'] ?? '') : '') . "</td>
                    <td class='nota3'>" . (isset($row['Nota3']) ? htmlspecialchars($row['Nota3'] ?? '') : '') . "</td>
                    <td>
                        <button class='btn btn-sm btn-warning edit-nota-btn'>Editar</button>
                        <button class='btn btn-sm btn-success save-nota-btn d-none'>Guardar</button>
                        <button class='btn btn-sm btn-secondary cancel-nota-btn d-none'>Cancelar</button>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No hay notas registradas.</p>";
    }
    exit;
}

// ============================================================================
// SCHEDULE HANDLERS
// ============================================================================

/**
 * Handler for AJAX request: Load class schedules.
 * Outputs an HTML table with schedule data.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['loadHorarios'])) {
    $res = $con->query("
    SELECT s.day_id, s.week_day,
           c1.classname AS firstclass_name,
           c2.classname AS secondclass_name,
           c3.classname AS thirdclass_name
    FROM schedule s
    LEFT JOIN clases c1 ON s.firstclass = c1.classid
    LEFT JOIN clases c2 ON s.secondclass = c2.classid
    LEFT JOIN clases c3 ON s.thirdclass = c3.classid
    ORDER BY s.day_id ASC
    ");

    if ($res && $res->num_rows > 0) {
        echo "<table class='table table-striped'><thead><tr>
                <th>Día</th><th>Primera Clase</th><th>Segunda Clase</th><th>Tercera Clase</th><th>Acciones</th>
              </tr></thead><tbody>";
        while ($row = $res->fetch_assoc()) {
            echo "<tr data-id='{$row['day_id']}'>
                <td class='weekday'>" . htmlspecialchars($row['week_day'] ?? '') . "</td>
                <td class='firstclass'>" . ($row['firstclass_name'] ?? '') . "</td>
                <td class='secondclass'>" . ($row['secondclass_name'] ?? '') . "</td>
                <td class='thirdclass'>" . ($row['thirdclass_name'] ?? '') . "</td>
                <td>
                    <button class='btn btn-sm btn-warning edit-horario-btn'>Editar</button>
                    <button class='btn btn-sm btn-success save-horario-btn d-none'>Guardar</button>
                    <button class='btn btn-sm btn-secondary cancel-horario-btn d-none'>Cancelar</button>
                </td>
              </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No hay horarios registrados.</p>";
    }
    exit;
}

