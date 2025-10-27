<?php
session_start();
// Comprobamos si el usuario ha iniciado sesión
// - Si no existe $_SESSION['usuario'], redirigimos al login.php y detenemos la ejecución.
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Guardar datos del personaje si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['personaje'] = [
        'nombre' => trim($_POST['nombre']),
        'habilidad' => trim($_POST['habilidad']),
        'imagen' => trim($_POST['imagen'])
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Elegir personaje</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h3>Hola <?php echo $_SESSION['usuario']; ?> 👋</h3>

        <p>Introduce los datos de tu personaje favorito:</p>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre del personaje" required>
            <input type="text" name="habilidad" placeholder="Habilidad del personaje" required>
            <input type="text" name="imagen" placeholder="URL de la imagen del personaje" required>
            <input type="submit" value="Guardar">
        </form>
        <!-- Mensaje si ya se ha ingresado un personaje -->
        <?php if (isset($_SESSION['personaje'])): ?>
            <p>Ya ingresaste tu personaje. Puedes verlo en la página <a href="personajes.php">Personajes</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>

