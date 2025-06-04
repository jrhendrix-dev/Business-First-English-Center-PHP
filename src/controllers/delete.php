<?php
/**
 * delete.php
 *
 * Handles deletion operations for the Business First English Center application.
 * Includes handlers for deleting users and classes via AJAX requests.
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

// USERS

/**
 * Deletes a user from the database.
 *
 * @param int $_POST['user_id'] The ID of the user to delete.
 * @return void Outputs "success" on success, "error" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
    $id = $_POST['user_id'];
    $stmt = $con->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);

    echo $stmt->execute() ? "success" : "error";
    exit;
}

// CLASSES

/**
 * Deletes a class from the database.
 *
 * @param int $_POST['classid'] The ID of the class to delete.
 * @return void Outputs "success" on success, "error" on failure.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteClass'])) {
    $classid = $_POST['classid'];
    $stmt = $con->prepare("DELETE FROM clases WHERE classid = ?");
    $stmt->bind_param("i", $classid);

    echo $stmt->execute() ? "success" : "error";
    exit;
}

?>