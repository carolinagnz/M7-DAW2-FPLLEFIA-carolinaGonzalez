<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio Extra 1 - Divisores y primo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: #f8f9fa;
        }
        .contenedor {
            display: inline-block;
            padding: 20px;
            margin-top: 40px;
            border: 2px solid #ddd;
            border-radius: 10px;
            background: #fffbea;
        }
        h2 {
            margin-top: 0;
            color: #333;
        }
        .divisores {
            margin: 15px 0;
        }
        .divisores div {
            display: inline-block;
            margin: 5px;
            padding: 10px 15px;
            background: #cce5ff;
            border-radius: 5px;
            font-weight: bold;
        }
        .resultado {
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        .no-primo {
            color: red;
        }
        .primo {
            color: green;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <?php
            $num = rand(1, 100);
            echo "<h2>Número generado: <strong>$num</strong></h2>";
            echo "<p>Divisores de $num:</p>";

            $contador = 0;
            echo "<div class='divisores'>";
            for ($i = 1; $i <= $num; $i++) {
                if ($num % $i == 0) {
                    echo "<div>$i</div>";
                    $contador++;
                }
            }
            echo "</div>";

            if ($contador == 2) {
                echo "<p class='resultado primo'>$num es un número primo.</p>";
            } else {
                echo "<p class='resultado no-primo'>$num no es un número primo.</p>";
            }
        ?>
    </div>
    <p><a href="index.php">Volver al índice</a></p>
</body>
</html>
