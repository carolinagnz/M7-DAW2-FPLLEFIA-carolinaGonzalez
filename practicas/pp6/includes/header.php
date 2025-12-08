<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title><?php echo isset($page_title) ? $page_title : 'TechSolutions Pro - Transformamos ideas en soluciones digitales'; ?></title>
  
  <!-- Mobile Specific Meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
  <!-- PLUGINS CSS STYLE -->
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/themify-icons/css/themify-icons.css">
  <link rel="stylesheet" href="plugins/slick/slick.css">
  
  <!-- CUSTOM CSS -->
  <link href="css/style.css" rel="stylesheet">
  
  <!-- COLORES CORPORATIVOS PERSONALIZADOS -->
  <style>
    :root {
      --primary-color: #2C3E50;
      --secondary-color: #E67E22;
    }
    
    /* Sobrescribir colores del template */
    .btn-main, .bg-primary {
      background: var(--primary-color) !important;
      border-color: var(--primary-color) !important;
    }
    
    .btn-main:hover {
      background: #1a252f !important;
    }
    
    .text-color {
      color: var(--primary-color) !important;
    }
    
    .btn-main-sm {
      background: var(--secondary-color) !important;
      border-color: var(--secondary-color) !important;
      color: white !important;
    }
    
    .btn-main-sm:hover {
      background: #d35400 !important;
    }
    
    .navigation {
      background: var(--primary-color) !important;
    }
    
    .navigation .navbar-nav .nav-link {
      color: rgba(255,255,255,0.9) !important;
    }
    
    .navigation .navbar-nav .nav-link:hover {
      color: var(--secondary-color) !important;
    }
  </style>
</head>

<body class="body-wrapper" data-spy="scroll" data-target=".privacy-nav">

<!-- HEADER/NAVIGATION -->
<header class="navigation fixed-top">
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand font-weight-bold" href="index.php">
        <span style="font-size: 24px; color: white;">TechSolutions Pro</span>
      </a>
      
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse text-center" id="navigation">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="portfolio.php">Portfolio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="blog.php">Blog</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contacto</a>
          </li>
          
          <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Usuario logueado -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ti-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                  <a class="dropdown-item" href="admin/index.php">
                    <i class="ti-dashboard"></i> Panel Admin
                  </a>
                  <div class="dropdown-divider"></div>
                <?php endif; ?>
                <a class="dropdown-item" href="logout.php">
                  <i class="ti-power-off"></i> Cerrar Sesión
                </a>
              </div>
            </li>
          <?php else: ?>
            <!-- Usuario no logueado -->
            <li class="nav-item">
              <a class="nav-link" href="login.php">
                <i class="ti-lock"></i> Login
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-main-sm ml-lg-2" href="register.php">Registro</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</header>