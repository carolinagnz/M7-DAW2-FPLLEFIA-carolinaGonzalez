<?php

$host = 'mysql-carolinagnz.alwaysdata.net';
$username = '439982';
$password = 'Odin2021!';
$bdname = 'carolinagnz_gestor_notas_uab';

$mysqli = new mysqli($host, $username, $password, $bdname);

if ($mysqli->connect_error) {
    die("Error de conexion: " . $mysqli->connect_error);
}else{
    echo '<br><br>';
    echo "Conexion exitosa a la base de datos.";
}
?>