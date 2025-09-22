<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 1 - Números pares</title>
    <style>
        div {
            display: inline-block;
            margin: 5px;
            padding: 10px;
            background: lightgreen;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2>Números pares del 50 al 500</h2>
    <?php
        for ($i = 50; $i <= 500; $i += 2) {
            echo "<div>$i</div>";
        }
    ?>
    <p><a href="index.php">Volver al índice</a></p>
</body>
</html>
