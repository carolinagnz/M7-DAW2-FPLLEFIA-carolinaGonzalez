<?php
$displayNombre = null;
$displayFoto = null;

if (isset($nombre) && trim($nombre) !== '') {
    $displayNombre = htmlspecialchars(trim($nombre), ENT_QUOTES, 'UTF-8', false);
}
if (isset($foto) && filter_var($foto, FILTER_VALIDATE_URL)) {
    $displayFoto = htmlspecialchars($foto, ENT_QUOTES, 'UTF-8', false);
}
?>

<header class="navbar navbar-expand-lg navbar-light bg-light mb-5">
  <div class="container-fluid d-flex justify-content-between">
    <!-- Logo -->
    <a class="navbar-brand" href="index.php">
      <img src="assets/logo-mercadona.jpg" alt="Logo" style="height:50px;">
    </a>

    <!-- Saludo + avatar -->
    <div class="d-flex align-items-center">
      <h2 class="me-3 mb-0 px-4">Bienvenido <?= $displayNombre ?? 'ALUMNO' ?>!</h2>
      <?php if ($displayFoto): ?>
        <img src="<?= $displayFoto ?>" alt="Avatar" class="rounded-circle" style="width:50px;height:50px;object-fit:cover;">
      <?php endif; ?>
    </div>
  </div>
</header>
