<?php

require_once __DIR__ . '/../models/Database.php';

// ========================== ADMIN PHP HANDLERS ====================================//

// ==================== CREATION HANDLERS ==================//


// ================ CLASS HANDLERS ================//

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
    //CONNECT TO DB
    $con = Database::connect();

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
