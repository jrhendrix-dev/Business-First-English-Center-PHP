<?php
/**
 * login.php
 *
 * Handles user authentication for the Business First English Center application.
 * - Accepts POST requests with username and password.
 * - Verifies credentials against the database.
 * - Implements session-based brute force protection.
 * - Sets secure session cookie parameters.
 * - Returns JSON responses for all outcomes.
 *
 * PHP version 7+
 *
 * @package    BusinessFirstEnglishCenter
 * @author     Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license    MIT License
 */

// ===================== SESSION COOKIE SECURITY SETTINGS =====================
/*
 * Configure session cookie parameters for security.
 * These must be set before session_start().
 * - HttpOnly: Prevents JavaScript access to session cookie.
 * - Secure: Only send cookie over HTTPS (set to true if using HTTPS).
 * - SameSite: Lax to help prevent CSRF.
 */
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Set to 1 only if using HTTPS

session_set_cookie_params([
    'lifetime' => 0, // Session cookie (expires on browser close)
    'path' => '/',
    'secure' => false, // Set to true if using HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

// ===================== DEPENDENCIES & HEADERS =====================
require_once __DIR__ . '/../src/models/Database.php';
header('Content-Type: application/json');

// ===================== HELPER FUNCTIONS =====================

/**
 * Sends a JSON response and terminates the script.
 *
 * @param array $data The data to encode as JSON.
 * @return void
 */
function send_json_response(array $data) {
    echo json_encode($data);
    exit();
}

/**
 * Handles the login process, including brute force protection.
 *
 * Implements session-based rate limiting: after a number of failed attempts,
 * login is temporarily blocked for a cooldown period.
 *
 * @return void
 */
function handle_login() {
    // --- Brute Force Protection Settings ---
    $max_attempts = 5;         // Maximum allowed failed attempts
    $lockout_time = 300;       // Lockout time in seconds (5 minutes)

    // Initialize session variables for tracking attempts
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt_time'] = 0;
    }

    // Check if user is currently locked out
    if ($_SESSION['login_attempts'] >= $max_attempts) {
        $time_since_last = time() - $_SESSION['last_attempt_time'];
        if ($time_since_last < $lockout_time) {
            $wait = $lockout_time - $time_since_last;
            send_json_response([
                "success" => false,
                "message" => "Demasiados intentos fallidos. Intenta de nuevo en $wait segundos.",
                "wait" => $wait
            ]);
        } else {
            // Reset after lockout period
            $_SESSION['login_attempts'] = 0;
        }
    }

    // Check for required POST parameters
    if (!isset($_POST['username'], $_POST['password'])) {
        send_json_response([
            "success" => false,
            "message" => "Datos incompletos"
        ]);
    }

    // --- Database Authentication ---
    $con = Database::connect();
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // --- Password Verification ---
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password using password_verify
        if (password_verify($password, $user['pword'])) {
            // Successful login: reset brute force counters
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = 0;

            // Set session variables on successful login
            session_regenerate_id(true); // Prevent session fixation attacks
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION["user"] = $user["username"];
            $_SESSION["lvl"] = $user["ulevel"];
            $_SESSION['curso'] = $user['class'];
            $_SESSION["login"] = true;
            if ($user['ulevel'] == 2) {
                $_SESSION["curso"] = $user['class'];
            }
            send_json_response([
                "success" => true
            ]);
        }
    }

    // --- Failed Login: Increment Brute Force Counters ---
    $_SESSION['login_attempts'] += 1;
    $_SESSION['last_attempt_time'] = time();

    send_json_response([
        "success" => false,
        "message" => "Usuario o contraseña incorrectos"
    ]);
}



// ===================== MAIN EXECUTION =====================
handle_login();


//=== LOGIN EXPIRATION ===//
if (!check_login()) {
    header("Location: login.php?expired=1"); // Optional query param to explain
    exit();
}


// LOGIN EXPIRATION HTML //
if (isset($_GET['expired'])): ?>
    <div class="alert alert-warning">Tu sesión ha expirado. Por favor inicia sesión de nuevo.</div>
<?php endif; ?>
