<?php

session_start();

//destruyo toda la sesión
session_destroy();

//me voy al index.php
header('Location: index.php');

exit();
?>