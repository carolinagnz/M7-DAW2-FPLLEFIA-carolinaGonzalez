<?php
// ------------------------------
// 1. INCLUIR DATA.PHP
// ------------------------------
// Esto hace dos cosas:
// 1) Inicia la sesión con session_start()
// 2) Inicializa las noticias de ejemplo si no existen en $_SESSION
include_once 'data.php';

// ------------------------------
// 2. ELIMINAR UNA NOTICIA SI SE SOLICITA
// ------------------------------
// Comprobamos si existe un parámetro GET llamado 'eliminar'
// Esto ocurre cuando el usuario hace clic en "Eliminar noticia"
if (isset($_GET['eliminar'])) {

    // Convertimos el valor recibido a entero para mayor seguridad
    $indice = (int) $_GET['eliminar'];

    // Verificamos que el índice exista dentro de $_SESSION['noticias']
    if (isset($_SESSION['noticias'][$indice])) {

        // Eliminamos la noticia correspondiente usando unset()
        unset($_SESSION['noticias'][$indice]);

        // Reordenamos los índices del array para evitar huecos
        // Esto es importante para que el foreach funcione correctamente
        $_SESSION['noticias'] = array_values($_SESSION['noticias']);

        // Creamos un mensaje de éxito que se mostrará al usuario
        $mensaje = "<p style='color:green;font-weight:bold;'>Noticia eliminada correctamente.</p>";
    }
}
?>

<?php
// ------------------------------
// 3. INCLUIR HEADER
// ------------------------------
// Contiene <html>, <head>, <body> y el menú de navegación
include 'inc/header.php';
?>

<main>
    <h2>Noticias actuales</h2>

    <!-- ------------------------------
         4. MOSTRAR MENSAJE DE ACCIÓN
         ------------------------------
         Si se eliminó alguna noticia, $mensaje contendrá el texto de confirmación
    -->
    <?= $mensaje ?? '' ?>

    <!-- ------------------------------
         5. RECORRER TODAS LAS NOTICIAS
         ------------------------------
         foreach recorre $_SESSION['noticias']
         $indice es la posición en el array, útil para eliminar
         $noticia es el array asociativo con datos de la noticia
    -->
    <?php foreach ($_SESSION['noticias'] as $indice => $noticia): ?>
        <article style="background-color:white; margin:20px; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1);">

            <!-- Título de la noticia -->
            <!-- htmlspecialchars protege contra inyección de HTML -->
            <h3><?= htmlspecialchars($noticia['titulo']) ?></h3>

            <!-- Fecha y categoría -->
            <p>
                <em>
                    <?= htmlspecialchars($noticia['fecha']) ?> |
                    <?= htmlspecialchars($noticia['categoria']) ?>
                </em>
            </p>

            <!-- Imagen de la noticia -->
            <!-- Usamos htmlspecialchars en el src y alt para seguridad -->
            <img src="<?= htmlspecialchars($noticia['imagen']) ?>" 
                 alt="<?= htmlspecialchars($noticia['titulo']) ?>" 
                 style="max-width:100%; height:auto; border-radius:10px;">

            <!-- Contenido de la noticia -->
            <!-- nl2br convierte saltos de línea en <br> -->
            <p><?= nl2br(htmlspecialchars($noticia['contenido'])) ?></p>

            <!-- Enlace para eliminar la noticia -->
            <!-- Pasamos el índice de la noticia por GET -->
            <p><a href="?eliminar=<?= $indice ?>" style="color:red;">Eliminar noticia</a></p>
            <!-- ? → indica inicio de parámetros GET.
             eliminar= → nombre del parámetro que PHP recibirá.
             <?= $indice ?> → imprime el valor del índice actual.
             Resultado final: ?eliminar=2
             PHP recibe esto en $_GET['eliminar'] y sabe qué noticia eliminar. -->
        </article>
    <?php endforeach; ?>

    <!-- Enlace para ir al formulario y añadir nuevas noticias -->
    <p><a href="form.php">Añadir nueva noticia</a></p>
</main>

<?php
// ------------------------------
// 6. INCLUIR FOOTER
// ------------------------------
// Contiene <footer> y cierra </body> y </html>
include 'inc/footer.php';
?>
