<?php
//PRIMERA CLASES CON SESIONES
session_start();

echo 'Sesion iniciada con exito<br>';
echo "Usuario: ".$_SESSION['user'];
echo "<br>Role: ".$_SESSION['role'];
