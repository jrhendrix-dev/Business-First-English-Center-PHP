<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../models/Database.php';

$con = Database::connect();

// --- INSERTAR USUARIO NUEVO (ADMIN) ---
if (isset($_POST['username'], $_POST['email'], $_POST['pword'], $_POST['ulevel'], $_POST['class'])) {
    echo  $_POST['username'];
    $addUser = $_POST['username'];
    $addEmail = $_POST['email'];
    $addPassword = password_hash($_POST['pword'], PASSWORD_BCRYPT);
    $addLevel = $_POST['ulevel'];
    $addClass = $_POST['class'];

    // Calcular el próximo user_id disponible
    $result = $con->query("SELECT user_id FROM users ORDER BY user_id ASC");
    $addID = 1;
    if ($result && $result->num_rows > 0) {
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            if ($count != $row['user_id']) {
                $addID = $count;
                break;
            }
            $count++;
        }
        $addID = $count;
    }

    $sql = "INSERT INTO users (user_id, username, email, pword, ulevel, class)
            VALUES ('$addID', '$addUser', '$addEmail', '$addPassword', '$addLevel', '$addClass')";
    $insert = $con->query($sql);

    if ($insert === TRUE) {
        echo "Yes";
    } else {
        echo "No. Error: " . $con->error;
    }

    // Si es estudiante, crear entrada en notas
    if ($addLevel == 3) {
        $sqlNotas = "INSERT INTO notas (idAlumno, idClase) VALUES ('$addID', '$addClass')";
        $insertNotas = $con->query($sqlNotas);

        if ($insertNotas !== TRUE) {
            echo "Error al insertar en notas: " . $con->error;
        }
    }

    $result && $result->free();
}

// --- CREAR CLASE ---
if (isset($_POST['classname'], $_POST['username'])) {
    $addClass = $_POST['classname'];
    $addTeacher = $_POST['username'];

    $result = $con->query("SELECT classid FROM clases ORDER BY classid ASC");
    $addID = 1;
    if ($result && $result->num_rows > 0) {
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            if ($count != $row['classid']) {
                $addID = $count;
                break;
            }
            $count++;
        }
        $addID = $count;
    }

    $insertClass = $con->query("INSERT INTO clases (classid, classname) VALUES('$addID', '$addClass')");
    $updateUser = $con->query("UPDATE users SET class='$addID' WHERE username='$addTeacher'");

    if (!$insertClass) echo "Error creando clase: " . $con->error;
    if (!$updateUser) echo "Error actualizando profesor: " . $con->error;

    $result && $result->free();
}

// --- FORMULARIO PÚBLICO DE CONTACTO ---
if (isset($_POST['nombre'], $_POST['apellidos'], $_POST['teléfono'], $_POST['email'], $_POST['mensaje'])) {
    echo $_POST['nombre'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['teléfono'];
    $email = $_POST['email'];
    $mensaje = $_POST['mensaje'];

    $sql = "INSERT INTO formulario (nombre, apellidos, teléfono, email, mensaje)
            VALUES ('$nombre', '$apellidos', '$telefono', '$email', '$mensaje')";
    $insert = $con->query($sql);

    if ($insert !== TRUE) {
        echo "Error al guardar formulario: " . $con->error;
    }

    header("Location: ../../public/gracias.php");
    exit;
}

$con->close();


?>
