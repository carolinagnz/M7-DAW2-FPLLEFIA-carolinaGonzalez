<?php
// Incluimos data.php para:
// 1) Iniciar la sesión (session_start())
// 2) Inicializar las noticias de ejemplo en $_SESSION si aún no existen
include_once 'data.php';

// ------------------------------
// 1. COMPROBAMOS SI SE ENVIÓ EL FORMULARIO
// $_SERVER['REQUEST_METHOD'] devuelve el método HTTP usado: GET ó POST.
// Solo procesamos los datos si el formulario fue enviado con POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ------------------------------
    // 2. RECOGEMOS LOS DATOS DEL FORMULARIO
    // Usamos el operador null coalescing (??) para asignar valores por defecto
    // Esto evita errores si algún campo no se envía
    // Si no se envía, la variable se inicializa como cadena vacía
    $titulo    = $_POST['titulo'] ?? '';
    $contenido = $_POST['contenido'] ?? '';
    $fecha     = $_POST['fecha'] ?? '';
    $imagen    = $_POST['imagen'] ?? '';
    $categoria = $_POST['categoria'] ?? '';

    // ------------------------------
    // 3. VALIDAMOS QUE TODOS LOS CAMPOS ESTÉN LLENOS
    // trim() elimina espacios en blanco al inicio y al final
    // empty() verifica si la cadena está vacía
    if ( !empty(trim($titulo)) && 
        !empty(trim($contenido)) && 
        !empty(trim($fecha)) && 
        !empty(trim($imagen)) && 
        !empty(trim($categoria)) ) {

        // ------------------------------
        // 4. AÑADIMOS LA NOTICIA A LA SESIÓN
        // $_SESSION['noticias'] es un array multidimensional
        // Cada noticia es un array asociativo con título, contenido, fecha, imagen y categoría
        $_SESSION['noticias'][] = [
            'titulo'    => $titulo,
            'contenido' => $contenido,
            'fecha'     => $fecha,
            'imagen'    => $imagen,
            'categoria' => $categoria
        ];

        // ------------------------------
        // 5. MOSTRAMOS MENSAJE DE ÉXITO
        $mensaje = "<p style='color:green;font-weight:bold;'>Noticia añadida correctamente.</p>";

    } else {
        // ------------------------------
        // 6. MENSAJE DE ERROR SI ALGÚN CAMPO ESTÁ VACÍO
        $mensaje = "<p style='color:red;font-weight:bold;'>Error: Todos los campos son obligatorios.</p>";
    }
}
// Fin del bloque POST
?>

<?php 
// Incluimos el header común de la página
// Contiene <html>, <head>, el menú y el inicio del <body>
include 'inc/header.php'; 
?>

<main>
    <h2>Añadir nueva noticia</h2>

    <!-- Mostramos mensaje de éxito o error si existe -->
    <?= $mensaje ?? '' ?>

    <!-- ------------------------------
         FORMULARIO HTML
         ------------------------------
         Cada campo tiene 'required' para validación básica del navegador.
    -->
    <form method="POST" action="">
        <!-- Campo Título -->
        <label>Título:<br>
            <input type="text" name="titulo" required>
        </label><br><br>

        <!-- Campo Contenido -->
        <label>Contenido:<br>
            <textarea name="contenido" rows="4" required></textarea>
        </label><br><br>

        <!-- Campo Fecha -->
        <label>Fecha:<br>
            <input type="date" name="fecha" required>
        </label><br><br>

        <!-- Campo URL Imagen -->
        <label>URL de imagen:<br>
            <input type="url" name="imagen" placeholder="https://ejemplo.com/imagen.jpg" required>
        </label><br><br>

        <!-- Campo Categoría -->
        <label>Categoría:<br>
            <input type="text" name="categoria" required>
        </label><br><br>

        <!-- Botón de envío -->
        <button type="submit">Añadir noticia</button>
    </form>

    <!-- Enlace para volver al listado de noticias -->
    <p><a href="index.php">Volver a la lista de noticias</a></p>
</main>

<?php 
// Incluimos el footer común de la página
// Contiene <footer> y cierra </body> y </html>
include 'inc/footer.php'; 
?>
