<?php

/*
 These ini_set calls configure how PHP will create the session cookie.
They must be set before the session is started, so that the cookie is created with the correct flags.
If you set them after session_start(), the session cookie may already have been sent to the browser without those flags.
 */
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Only if using HTTPS
session_start();

$pageTitle = "Dashboard | Business First English Center";
include_once __DIR__ . '/../views/header.php';


require_once __DIR__ . '/../includes/adminHandlers.php';
require_once __DIR__ . '/../src/models/Database.php';

if (!check_login()) {
    header("Location: login.php");
    exit();
}

$con = Database::connect();
include_once __DIR__ . '/header.php';
?>

<div class="wrapper">


    <div class="content">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION["user"]); ?>.</h2>

        <?php
        switch ($_SESSION["lvl"]) {
            case 1:
                include __DIR__ . '/dashboard_admin.php';
                break;
            case 2:
                include __DIR__ . '/dashboard_teacher.php';
                break;
            case 3:
                include __DIR__ . '/dashboard_student.php';
                break;
        }
        ?>
    </div>

</div>

<?php

$con->close();
include_once __DIR__ . '/footer.php';
?>
