<?php

// Incluimos el archivo pelicules.php que contiene el array $peliculas
// Esto nos da acceso a todos los datos de las películas (nombre, imagen, trailer, etc.)
include 'pelicules.php';

// Obtenemos el id de la película desde la URL mediante $_GET
// Ejemplo de URL: trailer.php?id=2
// isset() verifica que el parámetro exista para evitar errores
// (int) convierte el valor a entero
// Si no se pasa el id, se asigna 0 por defecto
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verificamos que exista la película con ese id en el array
// isset($peliculas[$id]) comprueba que el índice existe
// Si no existe, detenemos el script y mostramos un mensaje
if (!isset($peliculas[$id])) {
    die("Película no encontrada."); // Mensaje de error seguro
}

// Guardamos la película seleccionada en una variable más cómoda
// Esto evita escribir $peliculas[$id] en cada línea
$pelicula = $peliculas[$id];

/**
 * Función youtube_to_embed($url)
 * Convierte cualquier URL de YouTube a formato "embed" para usar en iframe
 * Funciona con estos formatos:
 *   - https://www.youtube.com/watch?v=VIDEOID
 *   - https://youtu.be/VIDEOID
 *   - https://www.youtube.com/embed/VIDEOID
 */
function youtube_to_embed($url) {
    // preg_match busca un patrón en la URL y captura el ID del video (11 caracteres)
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtube\.com\/embed\/|youtu\.be\/)([A-Za-z0-9_-]{11})/', $url, $m)) {
        // Retornamos la URL en formato embed listo para iframe
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    // Si la URL no coincide con los formatos conocidos, devolvemos la URL original
    return $url;
}

// Construimos la URL final para el iframe
// Añadimos parámetros para autoplay y mute (muchos navegadores bloquean autoplay con sonido)
$trailerEmbed = youtube_to_embed($pelicula['trailer']) . '?autoplay=1&mute=1';
?>

<!-- ============================ HTML de la página ============================ -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  
  <!-- Título de la página, escapando caracteres especiales para seguridad -->
  <title><?= htmlspecialchars($pelicula['nombre'], ENT_QUOTES); ?> - Tráiler</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    body { 
      background-color: #f8f9fa; 
      color: #212529;             
      text-align: center;         
    }
    header { 
      background-color: #000; 
      color: #fff; 
      padding: 15px; 
    }
    header h1 { 
      margin: 0; 
      color: #dc3545; 
    }
    iframe { 
      border-radius: 10px; 
      box-shadow: 0 4px 12px rgba(0,0,0,0.3); 
    }
  </style>
</head>
<body>

<!-- ============================ HEADER ============================ -->
<header>
  <h1>Tráiler - <?= htmlspecialchars($pelicula['nombre'], ENT_QUOTES); ?></h1>
</header>

<!-- ============================ MAIN ============================ -->
<main class="container my-4">
  
  <!-- Proporción 16:9 usando Bootstrap -->
  <div class="ratio ratio-16x9">
    
    <!-- ============================ IFRAME DEL TRÁILER   ============================ -->
    <iframe 
      src="<?= $trailerEmbed; ?>" 
      title="Tráiler de <?= htmlspecialchars($pelicula['nombre'], ENT_QUOTES); ?>" 
      allow="autoplay; encrypted-media; fullscreen; picture-in-picture" 
      allowfullscreen>
    </iframe>
  </div>

  <a href="index.php" class="btn btn-dark mt-4">Volver a la cartelera</a>
</main>

<!-- ============================ Bootstrap JS ============================ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
