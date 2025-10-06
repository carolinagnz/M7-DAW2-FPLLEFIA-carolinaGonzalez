<?php
// -----------------------------------------------------------------------------
// BLOQUE PHP INICIAL: PREPARACIÓN DE DATOS
// -----------------------------------------------------------------------------
// Este bloque se ejecuta antes de que comience el HTML.
// Aquí se incluye el archivo con el array de películas,
// se valida el parámetro recibido por URL y se selecciona la película correspondiente.

// Incluimos el archivo que contiene el array con toda la información de las películas.
include 'pelicules.php';

// Capturamos el parámetro "id" enviado por la URL, por ejemplo: detalle.php?id=3
// isset($_GET['id']) verifica si el parámetro 'id' existe.
// Si existe, lo convertimos a entero con (int) para evitar inyecciones o errores.
// Si no existe, le damos el valor 0 por defecto.
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Verificamos si el índice $id existe dentro del array $peliculas.
// Si no existe, significa que no hay película con ese ID, y mostramos un mensaje de error.
if (!isset($peliculas[$id])) {
    // La función die() detiene inmediatamente la ejecución del script
    // y muestra el mensaje entre paréntesis.
    die("Película no encontrada.");
}

// Si el ID es válido, seleccionamos esa película del array.
$pelicula = $peliculas[$id];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle - <?php echo $pelicula['nombre']; ?></title>

    <!-- Enlace a Bootstrap (CSS) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #111; /* Fondo negro */
            color: white; /* Texto blanco */
        }
        header {
            background-color: black; /* Encabezado negro */
            padding: 1rem;
        }
        .navbar a {
            color: red !important; /* Enlaces del menú en rojo */
        }
        .btn-custom {
            background-color: red;
            color: white;
        }
        .btn-custom:hover {
            background-color: darkred;
        }
        .carousel-inner img {
            height: 450px;
            object-fit: cover;
        }
        .boton-horario:hover {
            background-color: darkred;
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<!-- --------------------------------------------------------------------------
    HEADER: Encabezado principal del sitio
--------------------------------------------------------------------------- -->
<header id="header">
    <div class="container text-center">
        <h1>Cartelera Cines Màgic Badalona</h1>
    </div>
</header>

<!-- --------------------------------------------------------------------------
    CONTENIDO PRINCIPAL
--------------------------------------------------------------------------- -->
<main class="container my-5">
    <div class="row">
        <!-- COLUMNA IZQUIERDA: Imagen principal de la película -->
        <div class="col-md-4">
            <!-- Mostramos la imagen de la película -->
            <img src="<?php echo $pelicula['imagen']; ?>" 
                 alt="Imagen de <?php echo $pelicula['nombre']; ?>" 
                 class="img-fluid rounded">
        </div>

        <!-- COLUMNA DERECHA: Información de la película -->
        <div class="col-md-8">
            <?php
            // -----------------------------------------------------------------
            // BLOQUE PHP PRINCIPAL: IMPRESIÓN DE DATOS DE LA PELÍCULA
            // -----------------------------------------------------------------

            // Título de la película
            echo '<h2>' . $pelicula['nombre'] . '</h2>';

            // Sinopsis
            echo '<p><strong>Sinopsis:</strong> ' . $pelicula['sinopsis'] . '</p>';

            // Duración
            echo '<p><strong>Duración:</strong> ' . $pelicula['duracion'] . '</p>';

            // Director
            echo '<p><strong>Director:</strong> ' . $pelicula['director'] . '</p>';

            // Reparto (solo si existe y es un array)
            if (!empty($pelicula['reparto']) && is_array($pelicula['reparto'])) {
                echo '<p><strong>Reparto:</strong> ' . implode(", ", $pelicula['reparto']) . '</p>';
            }

            // Clasificación por edad
            echo '<p><strong>Clasificación:</strong> ' . $pelicula['calificacion'] . '</p>';

            // Género
            echo '<p><strong>Género:</strong> ' . $pelicula['genero'] . '</p>';


            // Algunas películas tienen una valoración numérica (de 0 a 10).
            // Queremos mostrarla como estrellas (de 1 a 5) y el valor numérico.

            // Supongamos que tenemos una película con:
            // $pelicula["valoracion"] = 8.4;
            //
            // El objetivo es convertir esa valoración de 0 a 10
            // en un sistema visual de estrellas (de 1 a 5) y mostrar ambas formas.
            //
            // Verificamos que el arreglo $pelicula contenga la clave "valoracion".
            // Convertimos el valor a número decimal (float).
            // Reducimos la escala de 10 a 5 dividiendo por 2 y redondeando al entero mas cercano
            // Generamos una cadena con estrellas llenas (⭐) y vacías (☆).
            // Mostramos el resultado con formato HTML.
            //
            // Resultado esperado (para 8.4):
            // Valoración: ⭐⭐⭐⭐☆ (8.4/10)
            if (isset($pelicula["valoracion"])) {
                // Convertimos la valoración de 10 puntos a 5 estrellas
                $valoracion10 = (float)$pelicula["valoracion"];
                $valoracion5 = round($valoracion10 / 2);

                // Generamos las estrellas visuales con str_repeat()
                // "⭐" se repite tantas veces como $valoracion5
                // "☆" se repite el resto hasta completar 5
                $estrellas = str_repeat("⭐", $valoracion5) . str_repeat("☆", 5 - $valoracion5);

                // La función str_repeat(cadena, número) repite una cadena tantas veces
                // como se indique en el segundo parámetro.
                //
                // Ejemplo básico:
                // str_repeat("⭐", 3) -> "⭐⭐⭐"
                //
                // En este contexto:
                // - str_repeat("⭐", $valoracion5) genera las estrellas llenas,
                //   según la valoración redondeada (por ejemplo, 4 -> "⭐⭐⭐⭐").
                // - str_repeat("☆", 5 - $valoracion5) genera las estrellas vacías
                //   para completar un total de 5 (por ejemplo, si $valoracion5 = 4,
                //   entonces 5 - 4 = 1 -> "☆").
                //
                // Luego ambas cadenas se concatenan:
                // "⭐⭐⭐⭐" . "☆" = "⭐⭐⭐⭐☆"


                // Mostramos el resultado final
                echo '<p><strong>Valoración:</strong> ' . $estrellas . ' (' . $valoracion10 . '/10)</p>';
            }

            // Horarios como enlaces
            echo '<p><strong>Horarios:</strong> ';
            foreach ($pelicula['horarios'] as $horario) {
                echo '<a href="#" class=".boton-horario:hover" style="
                    display: inline-block;
                    background-color: red;
                    color: white;
                    padding: 5px 10px;
                    margin: 2px;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                    transition: background-color 0.3s, transform 0.2s;
                ">' . $horario . '</a>';
            }
            echo '</p>';

            // Botón para ver el tráiler (lleva a trailer.php?id)
            // Botón para regresar a la cartelera (index.php)
            echo '<a href="trailer.php?id=' . $id . '" class="btn btn-custom">Ver tráiler</a> ';
            echo '<a href="index.php" class="btn btn-secondary">Volver a cartelera</a>';
            ?>
        </div>
    </div>

    <!-- ----------------------------------------------------------------------
        CARRUSEL DE IMÁGENES EXTRA
    ---------------------------------------------------------------------- -->
    <!-- Este carrusel mostrará imágenes adicionales de la película -->
    <div id="carousel<?php echo $id; ?>" class="carousel slide mt-5" data-bs-ride="carousel">
        <!-- contenedor de las diapositivas -->
        <div class="carousel-inner">
            <?php
            // Verificamos si existen imágenes extra en la película
            if (!empty($pelicula['imagenes_extra']) && is_array($pelicula['imagenes_extra'])) {

                // Inicializamos la variable $active con "active"
                // (Bootstrap necesita que una de las imágenes tenga esta clase para mostrarse primero)
                $active = "active";

                // Recorremos cada imagen extra y la insertamos dentro del carrusel
                foreach ($pelicula['imagenes_extra'] as $img) {
                    // Imprimimos el contenedor de la imagen
                    echo '
                    <div class="carousel-item ' . $active . '">
                        <img src="' . $img . '" class="d-block w-100 rounded" 
                             alt="Imagen extra de ' . $pelicula['nombre'] . '">
                    </div>';
                    // Después de la primera imagen, eliminamos la clase "active"
                    // para que solo la primera tenga ese atributo.
                    $active = "";
                }
            } else {
                // Si la película no tiene imágenes extra, mostramos un mensaje alternativo
                echo '<div class="carousel-item active">
                        <p class="text-center">No hay imágenes adicionales disponibles.</p>
                      </div>';
            }
            ?>
        </div>

        <!-- Botón de navegación hacia la imagen anterior -->
        <button class="carousel-control-prev" type="button" 
                data-bs-target="#carousel<?php echo $id; ?>" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <!-- Botón de navegación hacia la imagen siguiente -->
        <button class="carousel-control-next" type="button" 
                data-bs-target="#carousel<?php echo $id; ?>" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</main>

<!-- Scripts de Bootstrap (JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

