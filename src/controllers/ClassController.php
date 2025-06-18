<?php

require_once __DIR__ . '/../models/Database.php';

// ========================== ADMIN PHP HANDLERS ====================================//

// ==================== CREATION HANDLERS ==================//

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
// ==================== UPDATE HANDLERS ==================//

/**
 * Update class information and assign a teacher.
 *
 * Handles POST requests to update class data and assign a teacher to the class.
 * Unassigns the class from any previous teacher before assigning the new one.
 *
 * Expects the following POST parameters:
 * - updateClass: (any value, used as a flag)
 * - classid: int, the class ID to update
 * - classname: string, the new class name
 * - profesor: int, the user ID of the teacher to assign
 *
 * @return void Outputs "success" on success, "error" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateClass'])) {
    //CONNECT TO DB
    $con = Database::connect();

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

// ==================== DELETE HANDLERS ==================//
/**
 * Deletes a class from the database.
 *
 * @param int $_POST['classid'] The ID of the class to delete.
 * @return void Outputs "success" on success, "error" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteClass'])) {
    //CONNECT TO DB
    $con = Database::connect();

    $classid = $_POST['classid'];
    $stmt = $con->prepare("DELETE FROM clases WHERE classid = ?");
    $stmt->bind_param("i", $classid);

    echo $stmt->execute() ? "success" : "error";
    exit;
}
