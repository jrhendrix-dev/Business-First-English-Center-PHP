<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!--width=device-width sets the width of the page to the screen-width of the device and initial-scale=1 sets the initial zoom level when the page is first loaded-->
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Academia de Idiomas' ?></title>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Index.php,login JS File -->
    <script src="/Business-First-English-Center/public/assets/js/common.js"></script>

    <!-- Icon Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- My Personal CSS Sheet -->
    <link href="/Business-First-English-Center/public/assets/css/index.css" rel="stylesheet" type="text/css"/>
</head>

<body>



<!--NAVBAR START-->
<a name="Top" class="anchor"></a>
<nav class="navbar navbar-expand-sm navbar-dark justify-content-sm-start sticky-top" id="Navegación">
    <a class="navbar-brand" href="/Business-First-English-Center/public/index.php">
        <img src="/Business-First-English-Center/public/assets/pics/logo.png" alt="" id="Navbar_Logo"/>
    </a>

    <ul class="navbar-nav list-color">
        <li class="nav-item">
            <a class="nav-link" href="/Business-First-English-Center/public/index.php">Home</a>
        </li>
        <li class="nav-item dropdown list-color">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Cursos</a>
            <div class="dropdown-menu Menu-Style" id="Menu-Cursos">
                <a class="dropdown-item" href="/Business-First-English-Center/public/ic.php">Inglés corporativo</a>
                <a class="dropdown-item" href="/Business-First-English-Center/public/examenes.php">Preparación exámenes oficiales</a>
                <a class="dropdown-item" href="/Business-First-English-Center/public/clasesesp.php">Español para extranjeros</a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="Navbar-Contacto">Contacto</a>
            <div class="dropdown-menu Menu-Style">
                <span class="dropdown-item">Tlf: 983 542 740</span>
                <span class="dropdown-item">businessfirstenglish@gmail.com</span>
                <span class="dropdown-item">Avd. De la Marina 52, Rota (Cádiz)</span>
            </div>
        </li>

        <?php if (!empty($_SESSION["user"])): ?>
            <?php if ($_SESSION["lvl"] == 1): ?>
                <li class="nav-item"><a class="nav-link" href="/Business-First-English-Center/views/dashboard.php">Administrador</a></li>
            <?php elseif ($_SESSION["lvl"] == 2): ?>
                <li class="nav-item"><a class="nav-link" href="/Business-First-English-Center/views/dashboard.php">Profesor</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="/Business-First-English-Center/views/dashboard.php">Estudiante</a></li>
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link" href="/Business-First-English-Center/public/logout.php">
                    <?= htmlspecialchars($_SESSION["user"]) ?>(logout)
                </a>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link modal-button" id="login" data-toggle="modal" data-target="#login-modal" href="#">Iniciar Sesión</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<!-- LOGIN MODAL -->
<div class="container">
    <div class="modal fade" id="login-modal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header text-center">
                    <h4 class="modal-title">Login</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- //////////////////////////////////////////////////////-->
                <!--                LOGIN FORM                             -->
                <!-- //////////////////////////////////////////////////////-->
                <form id="login-form" name="login" role="form">
                    <div class="modal-body">
                        <label>Name</label>
                        <input type="text" name="username" id="username" placeholder="Username..." class="form-control" required />
                        <br>
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password..." required />
                        <span id="login_error" class="text-danger"></span>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="login_button" class="btn btn-success" id="login_button"/>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!--NAVBAR END-->
