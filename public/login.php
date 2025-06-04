<?php
session_start();
require_once __DIR__ . '/../src/models/Database.php';
header('Content-Type: application/json');

if (isset($_POST['username'], $_POST['password'])) {
    $con = Database::connect();
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['pword'] === $password) {
            $_SESSION["user"] = $user["username"];
            $_SESSION["lvl"] = $user["ulevel"];
            $_SESSION["login"] = true;
            if ($user['ulevel'] == 2) {
                $_SESSION["curso"] = $user['class'];
            }
            echo json_encode(["success" => true]);
            exit();
        }
    }

    echo json_encode(["success" => false, "message" => "Usuario o contraseña incorrectos"]);
    exit();
}
echo json_encode(["success" => false, "message" => "Datos incompletos"]);
