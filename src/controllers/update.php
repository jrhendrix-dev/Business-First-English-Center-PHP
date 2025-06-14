<?php
/**
 * update.php
 *
 * Handles update operations for the Business First English Center application.
 * Supports updating users, classes, grades, and class schedules via AJAX requests.
 *
 * PHP version 7+
 *
 * @package    BusinessFirstEnglishCenter
 * @author     Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license    MIT License
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Database.php';

$con = Database::connect();

/**
 * Update user information.
 *
 * Handles POST requests to update user data. If the user's level is set to student (ulevel == 3),
 * ensures a corresponding row exists in the 'notas' table for grade tracking.
 *
 * Expects the following POST parameters:
 * - updateUser: (any value, used as a flag)
 * - user_id: int, the user's ID
 * - username: string, the user's name
 * - email: string, the user's email address
 * - class: string, the class ID or name
 * - ulevel: int, the user's level (e.g., 3 for student)
 *
 * @return void Outputs "success" on success, "error" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $class = $_POST['class'];
    $ulevel = $_POST['ulevel'];

    $stmt = $con->prepare("UPDATE users SET username=?, email=?, class=?, ulevel=? WHERE user_id=?");
    $stmt->bind_param("ssssi", $username, $email, $class, $ulevel, $id);

    if ($stmt->execute()) {
        // If the user is now a student, ensure they have a notas row
        if ($ulevel == 3) {
            // Check if notas row exists
            $checkNotas = $con->prepare("SELECT 1 FROM notas WHERE idAlumno = ?");
            $checkNotas->bind_param("i", $id);
            $checkNotas->execute();
            $checkNotas->store_result();

            if ($checkNotas->num_rows === 0) {
                // Insert notas row for this student
                $insertNotas = $con->prepare("INSERT INTO notas (idAlumno) VALUES (?)");
                $insertNotas->bind_param("i", $id);
                $insertNotas->execute();
                // Optionally, you can check for errors here as well
            }
            $checkNotas->close();
        }
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

/**
 * Update class information and assign a teacher.
 *
 * Handles POST requests to update class data and assign a teacher to the class.
 * Unassigns the class from any previous teacher before assigning the new one.
 *
 * Expects the following POST parameters:
 * - updateClass: (any value, used as a flag)
 * - classid: int, the class ID to update
 * - classname: string, the new class name
 * - profesor: int, the user ID of the teacher to assign
 *
 * @return void Outputs "success" on success, "error" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateClass'])) {
    $classid = $_POST['classid'];
    $classname = $_POST['classname'];
    $profesor_id = $_POST['profesor'];

    // 1. Update class name
    $stmt1 = $con->prepare("UPDATE clases SET classname=? WHERE classid=?");
    $stmt1->bind_param("si", $classname, $classid);
    $success1 = $stmt1->execute();

    // 2. Unassign this class from any previous teacher
    $stmt2 = $con->prepare("UPDATE users SET class='' WHERE ulevel=2 AND class=?");
    $stmt2->bind_param("s", $classid);
    $success2 = $stmt2->execute();

    // 3. Assign the new teacher to the class
    $stmt3 = $con->prepare("UPDATE users SET class=? WHERE user_id=?");
    $stmt3->bind_param("si", $classid, $profesor_id);
    $success3 = $stmt3->execute();

    if ($success1 && $success2 && $success3) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

/**
 * Save or update student grades.
 *
 * Handles POST requests to save or update a student's grades in the 'notas' table.
 * If the student does not have a row in 'notas', one is created.
 * Validates that grades are either null or between 0 and 10.
 *
 * Expects the following POST parameters:
 * - updateNota: (any value, used as a flag)
 * - idAlumno: int, the student user ID
 * - nota1: float|string|null, the first grade (0-10 or empty)
 * - nota2: float|string|null, the second grade (0-10 or empty)
 * - nota3: float|string|null, the third grade (0-10 or empty)
 *
 * @return void Outputs "success" on success, "error" or "error: valores de nota fuera de rango" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateNota'])) {
    $id = $_POST['idAlumno'];
    $nota1 = isset($_POST['nota1']) && $_POST['nota1'] !== "" ? floatval($_POST['nota1']) : null;
    $nota2 = isset($_POST['nota2']) && $_POST['nota2'] !== "" ? floatval($_POST['nota2']) : null;
    $nota3 = isset($_POST['nota3']) && $_POST['nota3'] !== "" ? floatval($_POST['nota3']) : null;

    /**
     * Validate a grade value.
     *
     * @param float|null $n The grade value.
     * @return bool True if valid, false otherwise.
     */
    function validarNota($n) {
        return is_null($n) || (is_numeric($n) && $n >= 0 && $n <= 10);
    }

    if (!validarNota($nota1) || !validarNota($nota2) || !validarNota($nota3)) {
        echo "error: valores de nota fuera de rango";
        exit;
    }

    $res = $con->query("SELECT * FROM notas WHERE idAlumno = $id");
    if ($res && $res->num_rows > 0) {
        // UPDATE
        $sql = "UPDATE notas SET 
            Nota1=" . (is_null($nota1) ? "NULL" : "?") . ", 
            Nota2=" . (is_null($nota2) ? "NULL" : "?") . ", 
            Nota3=" . (is_null($nota3) ? "NULL" : "?") . " 
            WHERE idAlumno=?";
        $stmt = $con->prepare($sql);

        $bindTypes = "";
        $bindValues = [];
        if (!is_null($nota1)) { $bindTypes .= "d"; $bindValues[] = $nota1; }
        if (!is_null($nota2)) { $bindTypes .= "d"; $bindValues[] = $nota2; }
        if (!is_null($nota3)) { $bindTypes .= "d"; $bindValues[] = $nota3; }
        $bindTypes .= "i";
        $bindValues[] = $id;

        $stmt->bind_param($bindTypes, ...$bindValues);
    } else {
        // INSERT
        $sql = "INSERT INTO notas (Nota1, Nota2, Nota3, idAlumno) VALUES (" .
            (is_null($nota1) ? "NULL" : "?") . ", " .
            (is_null($nota2) ? "NULL" : "?") . ", " .
            (is_null($nota3) ? "NULL" : "?") . ", ?)";
        $stmt = $con->prepare($sql);

        $bindTypes = "";
        $bindValues = [];
        if (!is_null($nota1)) { $bindTypes .= "d"; $bindValues[] = $nota1; }
        if (!is_null($nota2)) { $bindTypes .= "d"; $bindValues[] = $nota2; }
        if (!is_null($nota3)) { $bindTypes .= "d"; $bindValues[] = $nota3; }
        $bindTypes .= "i";
        $bindValues[] = $id;

        $stmt->bind_param($bindTypes, ...$bindValues);
    }

    echo $stmt->execute() ? "success" : "error: " . $stmt->error;
    exit;
}

/**
 * Update class schedule for a specific day.
 *
 * Handles POST requests to update the schedule for a given day, setting the class IDs for each period.
 *
 * Expects the following POST parameters:
 * - updateHorario: (any value, used as a flag)
 * - day_id: int, the ID of the day to update
 * - firstclass: string, the class ID for the first period
 * - secondclass: string, the class ID for the second period
 * - thirdclass: string, the class ID for the third period
 *
 * @return void Outputs "success" on success, "error" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateHorario'])) {
    $id = $_POST['day_id'];
    $first = $_POST['firstclass'];
    $second = $_POST['secondclass'];
    $third = $_POST['thirdclass'];

    $stmt = $con->prepare("UPDATE schedule SET firstclass=?, secondclass=?, thirdclass=? WHERE day_id=?");
    $stmt->bind_param("sssi", $first, $second, $third, $id);

    echo $stmt->execute() ? "success" : "error: " . $stmt->error;
    exit;
}

?>