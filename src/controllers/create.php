<?php
/**
 * create.php
 *
 * Handles creation operations for the Business First English Center application.
 * Includes handlers for creating users (admin, teacher, student), classes, and public contact form submissions.
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
 * Handles creation of a new user (admin, teacher, or student).
 *
 * @param string $_POST['username'] The username for the new user.
 * @param string $_POST['email'] The email address for the new user.
 * @param string $_POST['pword'] The password for the new user (should be hashed).
 * @param int $_POST['ulevel'] The user level (1=admin, 2=teacher, 3=student).
 * @param string|int $_POST['class'] The class ID assigned to the user (may be empty for admin).
 * @return void Outputs "Yes" on success, "No. Error: ..." on failure, and error message if notas insertion fails.
 */
if (isset($_POST['username'], $_POST['email'], $_POST['pword'], $_POST['ulevel'], $_POST['class'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['pword']; // Should be hashed before storing!
    $ulevel = intval($_POST['ulevel']);
    $class = $_POST['class'];

    // Basic validation
    if ($username === '' || $email === '' || $password === '' || $ulevel < 1 || $ulevel > 3) {
        echo "No. Error: Invalid input.";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Find the lowest available user_id
    $result = $con->query("SELECT user_id FROM users ORDER BY user_id ASC");
    $expected_id = 1;
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if ((int)$row['user_id'] != $expected_id) {
                break;
            }
            $expected_id++;
        }
    }

    // Prepare insert statement with explicit user_id
    $stmt = $con->prepare("INSERT INTO users (user_id, username, email, pword, ulevel, class) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssii", $expected_id, $username, $email, $hashed_password, $ulevel, $class);

    if ($stmt->execute()) {
        // If student, create notas entry
        if ($ulevel == 3) {
            $user_id = $expected_id;
            $stmt2 = $con->prepare("INSERT INTO notas (idAlumno) VALUES (?)");
            $stmt2->bind_param("i", $user_id);
            if (!$stmt2->execute()) {
                echo "User created, but failed to create notas: " . $stmt2->error;
                exit;
            }
        }
        echo "Yes";
    } else {
        echo "No. Error: " . $stmt->error;
    }
    exit;
}

/**
 * Handles creation of a new class.
 *
 * @param string $_POST['createClass'] Must be set to trigger this handler.
 * @param string $_POST['classname'] The name of the new class.
 * @param string|int|null $_POST['profesor'] (Optional) The user ID of the teacher to assign to the class.
 * @return void Outputs "success" on success, "error" or "error: classname required" on failure.
 */
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['createClass'])
    && isset($_POST['classname'])
) {
    $classname = trim($_POST['classname']);
    $profesor_id = isset($_POST['profesor']) ? trim($_POST['profesor']) : '';

    // Validate classname
    if ($classname === '') {
        echo "error: classname required";
        exit;
    }

    // Find the lowest available classid
    $result = $con->query("SELECT classid FROM clases ORDER BY classid ASC");
    $expected_id = 1;
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if ((int)$row['classid'] != $expected_id) {
                break;
            }
            $expected_id++;
        }
    }

    // Insert the new class with explicit classid
    $stmt = $con->prepare("INSERT INTO clases (classid, classname) VALUES (?, ?)");
    $stmt->bind_param("is", $expected_id, $classname);
    $success1 = $stmt->execute();

    if (!$success1) {
        echo "error";
        exit;
    }

    $classid = $expected_id; // Use the assigned classid

    // If no teacher assigned, finish here
    if ($profesor_id === '' || $profesor_id === null) {
        echo "success";
        exit;
    }

    // Assign the teacher to the class
    $stmt2 = $con->prepare("UPDATE users SET class=? WHERE user_id=?");
    $stmt2->bind_param("ii", $classid, $profesor_id);
    $success2 = $stmt2->execute();

    echo ($success2) ? "success" : "error";
    exit;
}

/**
 * Handles public contact form submissions.
 *
 * @param string $_POST['nombre'] The sender's first name.
 * @param string $_POST['apellidos'] The sender's last name.
 * @param string $_POST['teléfono'] The sender's phone number.
 * @param string $_POST['email'] The sender's email address.
 * @param string $_POST['mensaje'] The message content.
 * @return void Redirects to gracias.php on success, outputs error message on failure.
 */
if (isset($_POST['nombre'], $_POST['apellidos'], $_POST['teléfono'], $_POST['email'], $_POST['mensaje'])) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $telefono = trim($_POST['teléfono']);
    $email = trim($_POST['email']);
    $mensaje = trim($_POST['mensaje']);

    // Basic validation
    if ($nombre === '' || $apellidos === '' || $email === '' || $mensaje === '') {
        echo "Error: Todos los campos obligatorios deben ser completados.";
        exit;
    }

    $stmt = $con->prepare("INSERT INTO formulario (nombre, apellidos, teléfono, email, mensaje) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $apellidos, $telefono, $email, $mensaje);

    if ($stmt->execute()) {
        header("Location: ../../public/gracias.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
        exit;
    }
}

$con->close();
?>