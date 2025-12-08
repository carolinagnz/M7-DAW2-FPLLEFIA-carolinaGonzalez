<?php
//PRIMERA CLASES CON SESIONES
session_start();

$_SESSION['user']= 'Maria';
$_SESSION['role']= 'admin';

echo 'Sesion iniciada con exito<br>';
echo "Usuario: ".$_SESSION['user'];
echo "<br>Role: ".$_SESSION['role'];

