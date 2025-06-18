<?php
/**
 * studentHandlers.php
 *
 * Handles AJAX requests for the student dashboard, including:
 * - Fetching student grades (read-only)
 * - Fetching class schedule (read-only)
 *
 * PHP version 7+
 *
 * @package    BusinessFirstEnglishCenter
 * @author     Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license    MIT License
 */

// ========================== DATABASE CONNECTION ==========================
if (!isset($con)) {
    require_once __DIR__ . '/../src/models/Database.php';
    try {
        $con = Database::connect();
    } catch (Exception $e) {
        http_response_code(500);
        echo "Error de conexión a la base de datos.";
        exit;
    }
}

// ========================== SESSION VARIABLES ==========================
$student_id = $_SESSION['user_id'] ?? null;
$studentClassId = $_SESSION['curso'] ?? null;

// ========================== FETCH CLASS NAME ==========================
/**
 * Loads the class name for the student's assigned class.
 * Sets $className if $studentClassId is set.
 */
$className = '';
if (!empty($studentClassId)) {
    $stmt = $con->prepare("SELECT classname FROM clases WHERE classid = ?");
    $stmt->bind_param("i", $studentClassId);
    $stmt->execute();
    $stmt->bind_result($className);
    $stmt->fetch();
    $stmt->close();
}

// ========================== ACTION: GET STUDENT GRADES ==========================
/**
 * Handles GET requests for the student's grades.
 * Responds with an HTML table or an error message.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getStudentGrades') {
    if (!$student_id || !$studentClassId) {
        http_response_code(400);
        echo "<div class='alert alert-warning'>No tienes una clase asignada o no estás autenticado.</div>";
        exit;
    }

    $stmt = $con->prepare(
        "SELECT u.user_id, u.username, c.classname, n.Nota1, n.Nota2, n.Nota3
         FROM users u
         LEFT JOIN clases c ON u.class = c.classid
         LEFT JOIN notas n ON u.user_id = n.idAlumno
         WHERE u.user_id = ?"
    );
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table class='table table-striped'><thead><tr>
            <th>Alumno</th><th>Curso</th><th>Nota 1</th><th>Nota 2</th><th>Nota 3</th>
          </tr></thead><tbody>";

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-id='" . htmlspecialchars($row['user_id']) . "'>
                    <td class='alumno'>" . htmlspecialchars($row['username']) . "</td>
                    <td class='curso'>" . htmlspecialchars($row['classname']) . "</td>
                    <td class='nota1'>" . (isset($row['Nota1']) ? htmlspecialchars($row['Nota1']) : '') . "</td>
                    <td class='nota2'>" . (isset($row['Nota2']) ? htmlspecialchars($row['Nota2']) : '') . "</td>
                    <td class='nota3'>" . (isset($row['Nota3']) ? htmlspecialchars($row['Nota3']) : '') . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5' class='text-center'>No hay notas registradas para este estudiante.</td></tr>";
    }

    echo "</tbody></table>";
    $stmt->close();
    exit;
}

// ========================== ACTION: GET CLASS SCHEDULE ==========================
/**
 * Handles GET requests for the student's class schedule.
 * Responds with an HTML table or a message if no schedule is found.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getClassSchedule') {
    $classId = $_SESSION['curso'] ?? null;

    if (!$classId) {
        http_response_code(400);
        echo "<div class='alert alert-warning'>No tienes una clase asignada.</div>";
        exit;
    }

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
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo "<table class='table table-bordered'>
                <thead>
                    <tr><th>Día</th><th>Primera Clase</th><th>Segunda Clase</th><th>Tercera Clase</th></tr>
                </thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['week_day']) . "</td>
                    <td>" . htmlspecialchars($row['firstclass_name']) . "</td>
                    <td>" . htmlspecialchars($row['secondclass_name']) . "</td>
                    <td>" . htmlspecialchars($row['thirdclass_name']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='text-muted'>Tu clase no tiene horario asignado todavía.</p>";
    }

    $stmt->close();
    exit;
}
?>