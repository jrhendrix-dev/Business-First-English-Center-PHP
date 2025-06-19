<?php

require_once __DIR__ . '/../models/Database.php';

// ==================== CREATION HANDLERS ===================//
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

// ==================== UPDATE HANDLERS ===================//
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
    $con = Database::connect();

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


// ==================== DELETE HANDLERS ===================//
/**
 * Deletes a user from the database.
 *
 * @param int $_POST['user_id'] The ID of the user to delete.
 * @return void Outputs "success" on success, "error" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
    $con = Database::connect();

    $id = $_POST['user_id'];
    $stmt = $con->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);

    echo $stmt->execute() ? "success" : "error";
    exit;
}
