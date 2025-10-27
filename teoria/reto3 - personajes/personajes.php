<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['personaje'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Personaje elegido</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h3>Tu personaje favorito:</h3>
        <p><strong>Nombre:</strong> <?php echo $_SESSION['personaje']['nombre']; ?></p>
        <p><strong>Habilidad:</strong> <?php echo $_SESSION['personaje']['habilidad']; ?></p>
        <!-- Mostrar imagen solo si la URL no está vacía -->
        <?php if (!empty($_SESSION['personaje']['imagen'])): ?>
            <img src="<?php echo $_SESSION['personaje']['imagen']; ?>" alt="Imagen del personaje">
        <?php endif; ?>
    </div>
</body>
</html>

