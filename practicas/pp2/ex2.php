<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 2 - Tablas de multiplicar</title>
    <style>
        .tabla {
            display: inline-block;
            margin: 15px;
            padding: 10px;
            border: 1px solid gray;
            border-radius: 8px;
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <h2>Tablas de multiplicar (1 al 10)</h2>
    <?php
        for ($n = 1; $n <= 10; $n++) {
            echo "<div class='tabla'><h3>Tabla del $n</h3>";
            for ($m = 1; $m <= 9; $m++) {
                $res = $n * $m;
                echo "$n x $m = $res <br>";
            }
            echo "</div>";
        }
    ?>
    <p><a href="index.php">Volver al índice</a></p>
</body>
</html>
