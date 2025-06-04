<?php
/**
 * update.php
 *
 * Handles update operations for the Business First English Center application.
 * Includes handlers for updating users, classes, grades, and class schedules via AJAX requests.
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

// USERS

/**
 * Updates user data.
 *
 * @param int    $_POST['user_id']   The ID of the user to update.
 * @param string $_POST['username']  The new username.
 * @param string $_POST['email']     The new email address.
 * @param string $_POST['class']     The class ID assigned to the user.
 * @param int    $_POST['ulevel']    The user level (1=admin, 2=teacher, 3=student).
 * @return void  Outputs "success" on success, "error" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $class = $_POST['class'];
    $ulevel = $_POST['ulevel'];

    $stmt = $con->prepare("UPDATE users SET username=?, email=?, class=?, ulevel=? WHERE user_id=?");
    $stmt->bind_param("ssssi", $username, $email, $class, $ulevel, $id);

    echo $stmt->execute() ? "success" : "error";
    exit;
}

// CLASSES

/**
 * Updates class data and assigns a teacher.
 *
 * @param int    $_POST['classid']   The ID of the class to update.
 * @param string $_POST['classname'] The new class name.
 * @param int    $_POST['profesor']  The user ID of the teacher to assign.
 * @return void  Outputs "success" on success, "error" on failure.
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

// GRADES

/**
 * Saves or updates student grades.
 *
 * @param int   $_POST['idAlumno'] The student user ID.
 * @param float $_POST['nota1']    The first grade (0-10).
 * @param float $_POST['nota2']    The second grade (0-10).
 * @param float $_POST['nota3']    The third grade (0-10).
 * @return void Outputs "success" on success, "error" or "error: valores de nota fuera de rango" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateNota'])) {
    $id = $_POST['idAlumno'];
    $nota1 = floatval($_POST['nota1']);
    $nota2 = floatval($_POST['nota2']);
    $nota3 = floatval($_POST['nota3']);

    /**
     * Validates that a grade is numeric and between 0 and 10.
     *
     * @param mixed $n Grade value.
     * @return bool True if valid, false otherwise.
     */
    function validarNota($n) {
        return is_numeric($n) && $n >= 0 && $n <= 10;
    }

    if (!validarNota($nota1) || !validarNota($nota2) || !validarNota($nota3)) {
        echo "error: valores de nota fuera de rango";
        exit;
    }

    $res = $con->query("SELECT * FROM notas WHERE idAlumno = $id");
    if ($res && $res->num_rows > 0) {
        $stmt = $con->prepare("UPDATE notas SET Nota1=?, Nota2=?, Nota3=? WHERE idAlumno=?");
        $stmt->bind_param("dddi", $nota1, $nota2, $nota3, $id);
    } else {
        $stmt = $con->prepare("INSERT INTO notas (Nota1, Nota2, Nota3, idAlumno) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("dddi", $nota1, $nota2, $nota3, $id);
    }

    echo $stmt->execute() ? "success" : "error";
    exit;
}

// SCHEDULE

/**
 * Updates the class schedule for a day.
 *
 * @param int    $_POST['day_id']     The ID of the day to update.
 * @param string $_POST['firstclass'] The class ID for the first period.
 * @param string $_POST['secondclass'] The class ID for the second period.
 * @param string $_POST['thirdclass'] The class ID for the third period.
 * @return void Outputs "success" on success, "error" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateHorario'])) {
    $id = $_POST['day_id'];
    $first = $_POST['firstclass'];
    $second = $_POST['secondclass'];
    $third = $_POST['thirdclass'];

    $stmt = $con->prepare("UPDATE schedule SET firstclass=?, secondclass=?, thirdclass=? WHERE day_id=?");
    $stmt->bind_param("sssi", $first, $second, $third, $id);

    echo $stmt->execute() ? "success" : "error";
    exit;
}

?>