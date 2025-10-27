<?php
session_start();
// Comprobamos si el usuario ya ha iniciado sesión
// - Si no existe $_SESSION['usuario'], redirigimos al login.php y detenemos la ejecución.
if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}
// Procesamos el formulario de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtenemos los datos del formulario
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Usuario simulado
    if ($usuario == 'vegeta' && $clave == '1234') {
        $_SESSION['usuario'] = 'Vegeta';
        header('Location: index.php');
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header style="background:#ccc; padding:10px;">
    <h2>Bienvenido al sitio de personajes</h2>
</header>

<div class="container">
    <h3>Iniciar Sesión</h3>

    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        <input type="submit" value="Entrar">
    </form>
    <!-- Mostrar error si existe -->
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
</div>

</body>
</html>


