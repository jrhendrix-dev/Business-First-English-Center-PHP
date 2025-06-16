<?php

require_once __DIR__ . '/../models/Database.php';


// ========================== ADMIN PHP HANDLERS ====================================//

// ==================== CREATION HANDLERS ===================//

// ================ USER HANDLERS ================//
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
    //CONNECT TO DB
    $con = Database::connect();

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['pword']; // Should be hashed before storing!
    $ulevel = intval($_POST['ulevel']);
    $class = $_POST['class'];

    if ($class === "") {
        $class = 0; // or $class = '';
    }

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
            $user_id = $con->insert_id;
            $stmt2 = $con->prepare("INSERT INTO notas (idAlumno, idClase) VALUES (?, ?)");
            $stmt2->bind_param("ii", $user_id, $class);
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