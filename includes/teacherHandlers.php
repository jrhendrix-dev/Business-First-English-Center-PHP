<?php
/**
 * teacherHandlers.php
 *
 * Handles AJAX requests for the teacher dashboard, including:
 * - Fetching students and grades
 * - Updating student grades
 * - Fetching class schedule
 *
 * PHP version 7+
 *
 * @package    BusinessFirstEnglishCenter
 * @author     Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license    MIT License
 */

// ===================== SESSION & DATABASE INITIALIZATION =====================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($con)) {
    require_once __DIR__ . '/../src/models/Database.php';
    try {
        $con = Database::connect();
    } catch (Exception $e) {
        // Log error and show generic message
        error_log('Database connection error: ' . $e->getMessage());
        echo "<div class='alert alert-danger'>Ha ocurrido un error. Por favor, inténtelo de nuevo más tarde.</div>";
        exit;
    }
}

// ===================== SESSION VARIABLES =====================
$teacher_id = $_SESSION['user_id'] ?? null;
$teacherClassId = $_SESSION['curso'] ?? null;

// ===================== FETCH CLASS NAME =====================
/**
 * Loads the class name for the teacher's assigned class.
 * Sets $className if $teacherClassId is set.
 */
$className = '';
if ($teacherClassId) {
    $stmt = $con->prepare("SELECT classname FROM clases WHERE classid = ?");
    $stmt->bind_param("i", $teacherClassId);
    $stmt->execute();
    $stmt->bind_result($className);
    $stmt->fetch();
    $stmt->close();
}

// ===================== ACTION: GET STUDENTS AND GRADES =====================
/**
 * Handles GET requests for students and their grades.
 * Responds with an HTML table or an error message.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getStudentsAndGrades') {
    if (!$teacherClassId) {
        http_response_code(400);
        echo "<div class='alert alert-warning'>No tienes una clase asignada.</div>";
        exit;
    }

    $stmt = $con->prepare(
        "SELECT u.user_id, u.username, c.classname, n.Nota1, n.Nota2, n.Nota3
         FROM users u
         LEFT JOIN clases c ON u.class = c.classid
         LEFT JOIN notas n ON u.user_id = n.idAlumno
         WHERE u.ulevel = 3 AND u.class = ?
         ORDER BY u.username"
    );
    $stmt->bind_param("i", $teacherClassId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo "<table class='table table-striped'><thead><tr>
                <th>Alumno</th><th>Curso</th><th>Nota 1</th><th>Nota 2</th><th>Nota 3</th><th>Acciones</th>
              </tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-id='" . htmlspecialchars($row['user_id']) . "'>
                    <td class='alumno'>" . htmlspecialchars($row['username']) . "</td>
                    <td class='curso'>" . htmlspecialchars($row['classname']) . "</td>
                    <td class='nota1'>" . (isset($row['Nota1']) ? htmlspecialchars($row['Nota1']) : '') . "</td>
                    <td class='nota2'>" . (isset($row['Nota2']) ? htmlspecialchars($row['Nota2']) : '') . "</td>
                    <td class='nota3'>" . (isset($row['Nota3']) ? htmlspecialchars($row['Nota3']) : '') . "</td>
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

    $stmt->close();
    exit;
}

// ===================== ACTION: SAVE/UPDATE GRADES =====================
/**
 * Handles POST requests to update a student's grades.
 * Validates that the student belongs to the teacher's class.
 * Responds with "success" or an error message.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateNota'], $_POST['idAlumno'])) {
    $alumno_id = $_POST['idAlumno'];

    /**
     * Sanitizes and converts grade input.
     * @param mixed $val
     * @return float|null
     */
    function parseNota($val) {
        return ($val === '' || strtolower($val) === 'null') ? null : floatval($val);
    }

    $nota1 = parseNota($_POST['nota1'] ?? null);
    $nota2 = parseNota($_POST['nota2'] ?? null);
    $nota3 = parseNota($_POST['nota3'] ?? null);

    // Verify student belongs to the teacher's class
    $stmt = $con->prepare("SELECT class FROM users WHERE user_id = ? AND ulevel = 3");
    $stmt->bind_param("i", $alumno_id);
    $stmt->execute();
    $stmt->bind_result($alumno_class);
    $stmt->fetch();
    $stmt->close();

    if ((int)$alumno_class !== (int)($teacherClassId ?? -1)) {
        http_response_code(403);
        echo "No tienes permiso para modificar este alumno.";
        exit;
    }

    // Insert or update grades
    $idClase = $teacherClassId;
    $stmt = $con->prepare(
        "INSERT INTO notas (idAlumno, idClase, Nota1, Nota2, Nota3)
         VALUES (?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
            Nota1 = VALUES(Nota1),
            Nota2 = VALUES(Nota2),
            Nota3 = VALUES(Nota3)"
    );
    $stmt->bind_param("iiddd", $alumno_id, $idClase, $nota1, $nota2, $nota3);

    if ($stmt->execute()) {
        echo "success";
    } else {
        http_response_code(500);
        echo "error";
    }

    $stmt->close();
    exit;
}

// ===================== ACTION: GET CLASS SCHEDULE =====================
/**
 * Handles GET requests for the teacher's class schedule.
 * Responds with an HTML table or a message if no schedule is found.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getClassSchedule') {
    $classId = $_SESSION['curso'] ?? null;

    $stmt = $con->prepare(
        "SELECT s.day_id, s.week_day,
                c1.classname AS firstclass_name,
                c2.classname AS secondclass_name,
                c3.classname AS thirdclass_name
         FROM schedule s
         LEFT JOIN clases c1 ON s.firstclass = c1.classid
         LEFT JOIN clases c2 ON s.secondclass = c2.classid
         LEFT JOIN clases c3 ON s.thirdclass = c3.classid
         WHERE s.firstclass = ? OR s.secondclass = ? OR s.thirdclass = ?
         ORDER BY FIELD(s.day_id, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes')"
    );
    $stmt->bind_param("iii", $classId, $classId, $classId);

    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        echo "<table class='table table-striped'><thead><tr>
                <th>Día</th>
                <th>Primera Clase</th>
                <th>Segunda Clase</th>
                <th>Tercera Clase</th>
              </tr></thead><tbody>";

        while ($row = $res->fetch_assoc()) {
            echo "<tr data-id='" . htmlspecialchars($row['day_id']) . "'>
                    <td class='weekday'>" . htmlspecialchars($row['week_day']) . "</td>
                    <td class='firstclass'>" . ($row['firstclass_name'] ?? '') . "</td>
                    <td class='secondclass'>" . ($row['secondclass_name'] ?? '') . "</td>
                    <td class='thirdclass'>" . ($row['thirdclass_name'] ?? '') . "</td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No hay horarios registrados para esta clase.</p>";
    }

    $stmt->close();
    exit;
}