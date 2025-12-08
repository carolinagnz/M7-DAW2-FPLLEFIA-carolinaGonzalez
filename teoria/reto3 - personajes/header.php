<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <h2>Mi sitio de personajes</h2>
    <nav>
        <a href="index.php">Inicio</a> |
        <a href="personajes.php">Personajes</a>
    </nav>

    <div>
        <?php if (isset($_SESSION['usuario'])): ?>
            <span>Bienvenido, <b><?php echo $_SESSION['usuario']; ?></b></span>
            <a href="logout.php" class="logout-icon" title="Cerrar sesión">&#x1F512;</a>
        <?php endif; ?>
    </div>
</header>
