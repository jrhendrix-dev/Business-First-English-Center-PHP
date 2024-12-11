<?php
////original
$con = mysqli_connect('localhost', 'root', 'admin', 'academy_db');
if (mysqli_connect_errno()){
    printf("Falló la conexión: %s \n", mysqli_connect_error());
    exit();
}



