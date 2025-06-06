<?php
/**
 * login.php
 *
 * Handles user authentication for the Business First English Center application.
 * Accepts POST requests with username and password, verifies credentials,
 * and starts a session on successful login.
 *
 * PHP version 7+
 *
 * @package    BusinessFirstEnglishCenter
 * @author     Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license    MIT License
 */

session_start();
require_once __DIR__ . '/../src/models/Database.php';
header('Content-Type: application/json');

/**
 * Sends a JSON response and exits.
 *
 * @param array $data The data to encode as JSON.
 * @return void
 */
function send_json_response(array $data) {
    echo json_encode($data);
    exit();
}

/**
 * Handles the login process.
 *
 * @return void
 */
function handle_login() {
    if (!isset($_POST['username'], $_POST['password'])) {
        send_json_response([
            "success" => false,
            "message" => "Datos incompletos"
        ]);
    }

    $con = Database::connect();
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password using password_verify
        if (password_verify($password, $user['pword'])) {
            // Set session variables on successful login
            $_SESSION["user"] = $user["username"];
            $_SESSION["lvl"] = $user["ulevel"];
            $_SESSION["login"] = true;
            if ($user['ulevel'] == 2) {
                $_SESSION["curso"] = $user['class'];
            }
            send_json_response([
                "success" => true
            ]);
        }
    }

    // If login fails
    send_json_response([
        "success" => false,
        "message" => "Usuario o contraseña incorrectos"
    ]);
}

// Main execution
handle_login();