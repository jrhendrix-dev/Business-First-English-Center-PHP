<?php


$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/'; // adjust if you're in a subdirectory

switch ($request) {
    case $basePath:
    case $basePath . 'home':
        require_once '../views/home.php';
        break;

    case $basePath . 'dashboard':
        require_once '../views/dashboard.php';
        break;

    case $basePath . 'login':
        require_once 'login.php';
        break;

    case $basePath . 'thanks':
        require_once 'gracias.php';
        break;

        case $basePath . 'login_screen':
        require_once 'login_screen.php';
        break;

    case $basePath . 'ingles-corporativo':
        require_once 'ic.php';
        break;

    case $basePath . 'examenes':
        require_once 'examenes.php';
        break;

    case $basePath . 'clases-espanol':
        require_once 'clasesesp.php';
        break;

    //  API ROUTES
    case $basePath . 'api/admin':
        require_once __DIR__ . '/../src/api/dashboard_admin.php';
        exit;

    case $basePath . 'api/student':
        require_once __DIR__ . '/../src/api/dashboard_student.php';
        exit;

    case $basePath . 'api/teacher':
        require_once __DIR__ . '/../src/api/dashboard_teacher.php';
        exit;

    default:
        http_response_code(404);
        echo '404 Not Found';
        break;
}

