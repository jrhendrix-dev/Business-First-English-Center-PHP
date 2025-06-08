<?php

/*
 These ini_set calls configure how PHP will create the session cookie.
They must be set before the session is started, so that the cookie is created with the correct flags.
If you set them after session_start(), the session cookie may already have been sent to the browser without those flags.
 */
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Only if using HTTPS

session_start();
?>

<!-- Header -->
<?php
$pageTitle = "Home";
include_once __DIR__ . '/../views/header.php';
?>
<div id="banner-container">
    <img src="assets/pics/banner.jpg" alt="vista de Nueva York desde oficina" id="Title-Image">
</div>

<div class="cuerpo">



</div>

<div class="Row-Header">
    <h2>Servicios que ofrecemos</h2>
</div>

<div class="rows">

    <div class="Row-Even">
        <a id="Sección-Inglés-Corporativo" class="anchor"></a>
        <h3>Inglés corporativo para empresas</h3>
        <img src="assets/pics/presentation.png" alt="office presentation" class="row-pic" />
        <p class="row-text" id="row-text">
            <br>
            <br>
            Contrata nuestros cursos adaptados a las necesidades de tu empresa.<br>
            Tus trabajadores no se encontrarán perdidos en una reunión, negociación, o presentación en inglés nunca más.<br>
            Formamos a tu equipo para que puedan interactuar y comunicarse con clientes de todo el mundo.<br>
            No te arrepentirás, tu inversión hará que tu negocio crezca.<br>
            Aprenderán de forma fácil, cómoda y sencilla<br>
            Somos la mejor solución para tu equipo.
        </p>
        <a class="panel-link" href="javascript:void(0)" onclick="window.location = 'ic.php'"></a>
    </div>

    <div class="Row-Uneven">
        <a  id="Sección-Exámenes-Oficiales" class="anchor"></a>
        <h3>Preparación de exámenes oficiales</h3>
        <img src="assets/pics/estudiantes1.png" alt="estudiantes universitarios" class="row-pic" />
        <p class="row-text">
            <br>
            Learn fast, expect the best. <br>
            Learn english to: <br>
            Attend a foreign University<br>
            Get into the Erasmus Program<br>
            Obtain an Official English Certificate<br>
            Matricúlate con nosotros en nuestra academia y te prepararemos para que apruebes los certificados oficiales de: TOELF, TOEIC y Cambridge.
        </p>
        <a class="panel-link" href="javascript:void(0)" onclick="window.location = 'examenes.php'"></a>
    </div>
    <div class="Row-Even">
        <a id="Sección-Español-Extranjeros" class="anchor"></a>
        <h3>Español para extranjeros</h3>
        <img src="assets/pics/espanol.png" alt="foto de aprende español" class="row-pic" />
        <p class="row-text"><br><br>El español es uno de los idiomas más hablados en todo el mundo.<br>
            Para dominar un idioma no es suficiente con hablarlo de oído, hay que conocer su estructura estudiando la gramática.<br>
            Si eres extranjero y quieres poder comunicarte y viajar, o necesitas hablar español para avanzar en tu empresa.<br>
            Aprende español con nosotros, matricúlate en nuestra Academia.<br>
        </p>
        <a class="panel-link" href="javascript:void(0)" onclick="window.location = 'clasesesp.php'"></a>

    </div>
</div>

<!-- Footer -->
<?php include_once __DIR__ . '/../views/footer.php'; ?>

