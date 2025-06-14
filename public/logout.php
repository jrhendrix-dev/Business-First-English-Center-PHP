<?php

/*
 These ini_set calls configure how PHP will create the session cookie.
They must be set before the session is started, so that the cookie is created with the correct flags.
If you set them after session_start(), the session cookie may already have been sent to the browser without those flags.
 */
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Only if using HTTPS

session_start();

//unset all session variables
$_SESSION = array();
session_unset();
session_destroy();
header("Location:index.php");
//header("Location:test.php");
exit;
?>