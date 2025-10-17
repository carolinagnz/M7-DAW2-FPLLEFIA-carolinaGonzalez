<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mini-wp</title>

    <link rel="stylesheet" href="../styles/index.css">
</head>
<body>
    <?php
        include_once 'inc/header.php';
    ?>

<main>
    <!-- Muestro las noticias -->
    <section class="news-grid-display">
        <?php
            include_once 'data.php';

            foreach($noticias as $noticia){
                echo "<article style='background-color: white; margin: 20px; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>";
                echo "<h2>" . htmlspecialchars($noticia['titulo']) . "</h2>";
                echo "<p><em>" . htmlspecialchars($noticia['fecha']) . " | " . htmlspecialchars($noticia['categoria']) . "</em></p>";
                echo "<img src='" . htmlspecialchars($noticia['imagen']) . "' alt='" . htmlspecialchars($noticia['titulo']) . "' style='max-width: 100%; height: auto; border-radius: 10px;'>";
                echo "<p>" . nl2br(htmlspecialchars($noticia['contenido'])) . "</p>";
                echo "</article>";
            }
        ?>
    </section>
</main>

    <?php
        include_once 'inc/footer.php';
    ?>
</body>
</html>