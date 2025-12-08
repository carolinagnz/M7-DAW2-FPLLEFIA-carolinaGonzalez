<?php
include 'config.php';
echo "<h1>HOLA MUNDO uab</h1>";

//consulta para que salga lo de la tabla que consideres(id, nombre, apellido y role.) formato de salida -> OBJECT..NO ME INTERESA
$users = $mysqli->query("SELECT * FROM 'users'");

//CAPA 2: CONVIERTO EL RESULTADO ANTERIOR (objeto) EN UN ARRAY ASOCIATIVO
$resultUsers = $users->fetch_all(MYSQLI_ASSOC);

//echo de users
var_dump($users);
echo '<br';
var_dump($resultUsers);

print_r($resultUsers);

//echo $users;


?>

