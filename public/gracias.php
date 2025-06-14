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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario enviado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Estilo personalizado -->
    <link href="assets/css/index.css" rel="stylesheet" type="text/css">
</head>
<body>

<div class="container text-center mt-5">
    <h1 class="mb-4">¡Gracias por contactarnos!</h1>
    <p class="lead">Tu mensaje ha sido recibido correctamente. Nos pondremos en contacto contigo lo antes posible.</p>
    <a href="index.php" class="btn btn-primary mt-3">Volver a la página principal</a>
</div>

</body>
</html>
