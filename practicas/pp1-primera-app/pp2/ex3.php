<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 3 - Número aleatorio</title>
    <style>
        .par { background: lightblue; padding: 20px; border-radius: 8px; }
        .impar { background: lightcoral; padding: 20px; border-radius: 8px; }
    </style>
</head>
<body>
    <h2>Número aleatorio entre 0 y 100</h2>
    <?php
        $num = rand(0, 100);
        if ($num % 2 == 0) {
            echo "<div class='par'>$num es PAR</div>";
        } else {
            echo "<div class='impar'>$num es IMPAR</div>";
        }
    ?>
    <p><a href="index.php">Volver al índice</a></p>
</body>
</html>
