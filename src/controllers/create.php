<?php
require_once __DIR__ . '/../../bootstrap.php'; // Asegúrate que exista
require_once __DIR__ . '/../models/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $con = Database::connect();

    date_default_timezone_set('Europe/Madrid'); //Controla que la hora marcada sea la que yo quiero, no la del server
    $fecha = date('Y-m-d H:i:s');



    $nombre    = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $telefono  = trim($_POST['teléfono'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $mensaje   = trim($_POST['mensaje'] ?? '');

    if ($nombre && $apellidos && $email && $mensaje) {
        $stmt = $con->prepare("INSERT INTO formulario (nombre, apellidos, teléfono, email, mensaje, submitted_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nombre, $apellidos, $telefono, $email, $mensaje, $fecha);

        if ($stmt->execute()) {
            header("Location: /thanks");
            exit;
        } else {
            error_log("MySQL Error: " . $stmt->error);
            echo "Error al insertar: " . $stmt->error;
        }
    } else {
        echo "Por favor, complete todos los campos obligatorios.";
    }
} else {
    echo "Método no permitido.";
}
echo "TEST";