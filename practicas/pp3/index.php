<?php

// Incluimos el archivo que contiene el array con todas las películas disponibles.
include 'pelicules.php';

$imagenesSlide = [
  "https://www.ocinemagic.es/images/banner_content/Cine-Senior.webp",
  "https://www.ocinemagic.es/images/banner_content/DESCARGA-LA-APP.webp",
  "https://www.ocinemagic.es/images/banner_content/Preestreno-Vieja-Loca.webp",
  "https://www.ocinemagic.es/images/banner_content/CHAINSAW-MAN-LA-PELICULA.-EL-ARCO-DE-REZE.webp",
  "https://www.ocinemagic.es/images/banner_content/Sorteo-Pepsi-x-Oxine-Viaje-a-Hollywood.webp",
  "https://www.ocinemagic.es/images/banner_content/Tron-Ares.webp",
  "https://www.ocinemagic.es/images/banner_content/Depeche-Mode-M.webp",
  "https://www.ocinemagic.es/images/banner_content/Dora-Aventuras-Magicas.webp",
  
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cartelera - Cinemes Màgic Badalona</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    
    body { color: #212529; }
    header { color: #212529; }
    header a { color: #212529; text-decoration: none; }
    header a:hover { color: #dc3545; } /* Rojo al pasar el cursor */

    /* ----------------------- REDES SOCIALES Y ENCABEZADO -------------------- */
    .redesSociales {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 5px 20px;
    }
    .redesSociales img { width: 24px; margin-right: 10px; }

    /* ----------------------------- MENÚ SUPERIOR ---------------------------- */
    .navbar-custom { padding: 10px 20px; }
    .navbar-custom .nav-link { color: #212529; margin: 0 10px; }
    .navbar-custom .nav-link:hover { color: #dc3545; }

    /* ----------------------------- CARRUSEL ---------------------------- */
    .carousel-item img {
      height: 400px;             /* Altura del slide */
      object-fit: cover;         /* Ajusta la imagen sin deformarla */
      border-radius: 15px;       /* Bordes redondeados */
    }

    /* ---------------------------- SUBTÍTULO Y FILTROS ---------------------- */
    .subtitle {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 30px 0;
    }
    .subtitle h1 { font-weight: bold; color: #dc3545; }

    /* ------------------------------ TARJETAS DE PELÍCULAS ------------------ */
    .movie-card {
      position: relative;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      cursor: pointer;
    }
    .movie-card img {
      width: 100%;
      transition: transform 0.3s ease;
    }
    .movie-card:hover img { transform: scale(1.1); }
    .movie-overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.85);
      color: #fff;
      opacity: 0;
      transition: opacity 0.3s ease;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 15px;
      text-align: center;
    }
    .movie-card:hover .movie-overlay { opacity: 1; }
    .movie-overlay h5 { color: #dc3545; font-weight: bold; }
    .movie-overlay a { margin-top: 8px; }

    /* ------------------------------ PIE DE PÁGINA -------------------------- */
    footer {
      background-color: #212529;
      color: #fff;
      text-align: center;
      padding: 15px;
      margin-top: 40px;
    }

    /* ------------------------------ MENÚ DE CATEGORÍAS --------------------- */
    .menu-categorias .nav-link {
      color: #dc3545 !important;
      font-weight: 500;
    }
    .menu-categorias .nav-link:hover { color: #000 !important; }

    .boton-horario:hover {
            background-color: darkred;
            transform: scale(1.1);
    }
  </style>
</head>
<body>

<header id="header">
  <div class="redesSociales">
    <div class="logos">
      <a href="#"><img src="/imagenes/facebook.png" alt="Facebook"></a>
      <a href="#"><img src="/imagenes/x.png" alt="X"></a>
      <a href="#"><img src="/imagenes/instagram.png" alt="Instagram"></a>
      <a href="#"><img src="/imagenes/tiktok.png" alt="TikTok"></a>
    </div>
    <div class="idiomas">
      <ul class="list-inline mb-0">
        <li class="list-inline-item"><a href="#">ES</a></li>
        <li class="list-inline-item"><a href="#">CA</a></li>
      </ul>
    </div>
  </div>

  <div class="navbar-custom d-flex justify-content-between align-items-center">
    <a href="#" class="logo"><img src="/imagenes/logo.png" alt="Logo Cine" height="40"></a>
    <nav>
      <ul class="nav">
        <li class="nav-item"><a class="nav-link" href="#">CARTELERA</a></li>
        <li class="nav-item"><a class="nav-link" href="#">BAR</a></li>
        <li class="nav-item"><a class="nav-link" href="#">EL CINE</a></li>
        <li class="nav-item"><a class="nav-link" href="#">SCREENX</a></li>
        <li class="nav-item"><a class="nav-link" href="#">SALA KIDS</a></li>
        <li class="nav-item"><a class="nav-link" href="#">FIDELITY</a></li>
        <li class="nav-item"><a class="nav-link" href="#">OTROS CINES</a></li>
      </ul>
    </nav>
  </div>

  <!-- ======================================================================
       CARRUSEL PRINCIPAL (SLIDE)
       ======================================================================
       - Usa las imágenes definidas manualmente en $imagenesSlide.
       - Bootstrap maneja automáticamente las transiciones.
       - La primera imagen debe tener la clase "active" para mostrarse al inicio.
       ====================================================================== -->
  <div id="carouselPrincipal" class="carousel slide mt-3" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php
      // Variable que marca cuál es la primera imagen del carrusel
      $active = "active";

      // Bucle que recorre todas las imágenes definidas manualmente
      foreach ($imagenesSlide as $img) {
        echo '
        <div class="carousel-item ' . $active . '">
          <img src="' . $img . '" class="d-block w-100" alt="Imagen del carrusel">
        </div>';
        $active = ""; // Después de la primera, se limpia para que las demás no sean "active"
      }
      ?>
    </div>

    <!-- Botones de control (anterior / siguiente) -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselPrincipal" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselPrincipal" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</header>

<!-- ========================================================================
     CUERPO PRINCIPAL (LISTA DE PELÍCULAS EN CARTELERA)
     ======================================================================== -->
<main>
  <div class="container">

    <!-- Título principal + botones de vista -->
    <div class="subtitle">
      <h1>Cartelera</h1>
      <div class="filters">
        <button class="btn btn-outline-dark"><img src="/imagenes/iconoTabla.png" width="20"></button>
        <button class="btn btn-outline-dark"><img src="/imagenes/iconoLista.png" width="20"></button>
      </div>
    </div>

    <!-- Menú de categorías y filtro por género -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
      <ul class="nav mb-2 menu-categorias">
        <li class="nav-item"><a class="nav-link" href="#">Cartelera</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Vose</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Screen X</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Sala Kids</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Eventos</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Venta Anticipada</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Próximamente</a></li>
      </ul>
      <div>
        <label for="genero" class="form-label me-2">Escoge un género:</label>
        <select id="genero" name="genero" class="form-select d-inline-block w-auto">
          <option value="todos">Todos los géneros</option>
          <option value="accion">Acción</option>
          <option value="animacion">Animación</option>
          <option value="anime">Animación-Anime</option>
          <option value="aventuras">Aventuras</option>
          <option value="drama">Drama</option>
          <option value="ciencia-ficcion">Ciencia ficción</option>
          <option value="terror">Terror</option>
        </select>
      </div>
    </div>

    <!-- ======================================================================
         GRID DE PELÍCULAS
         ======================================================================
         - Se genera automáticamente recorriendo el array $peliculas.
         - Cada tarjeta muestra la imagen, horarios y botones de acción.
         ====================================================================== -->
    <div class="row g-4">
      <?php
      // Bucle que recorre todas las películas del archivo "pelicules.php"
      foreach ($peliculas as $index => $pelicula) {

        // Cada película ocupa una columna del grid
        echo '<div class="col-md-4 col-lg-3">';
          echo '<div class="movie-card">';
            echo '<img src="' . $pelicula['imagen'] . '" alt="' . $pelicula['nombre'] . '">';
            echo '<div class="movie-overlay">';
              echo '<h5>' . $pelicula['nombre'] . '</h5>';
              echo '<p><strong>Horarios:</strong> ' . implode(", ", $pelicula['horarios']) . '</p>';
              echo '<a href="trailer.php?id=' . $index . '" class="btn btn-sm btn-danger w-100">Ver tráiler</a>';
              echo '<a href="detalle.php?id=' . $index . '" class="btn btn-sm btn-outline-light w-100 mt-2">Ver más info</a>';
            echo '</div>';
          echo '</div>';
        echo '</div>';
      }
      ?>
    </div>
  </div>
</main>

<!-- ========================================================================
     PIE DE PÁGINA
     ======================================================================== -->
<footer>
  <p>&copy; 2025 Cinemes Màgic Badalona - Todos los derechos reservados</p>
</footer>

<!-- Script de Bootstrap (necesario para el funcionamiento del carrusel) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
