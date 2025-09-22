<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio Extra 2 - El hombre del tiempo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            text-align: center;
        }
        .contenedor {
            display: inline-block;
            padding: 20px 40px;
            margin-top: 30px;
            border: 2px solid #333;
            border-radius: 8px;
            background: #fff;
        }
        h2 {
            margin-bottom: 20px;
            color: #222;
        }
        .temperaturas {
            display: grid;
            grid-template-columns: repeat(3, auto);
            gap: 15px;
            justify-content: center;
        }
        .temp {
            padding: 15px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            text-align: center;
        }
        .frio { background: #007bff; }      
        .suave { background: #f1c40f; color: black; } 
        .calor { background: #e74c3c; }     
        .resultado {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>Clasificación de Temperaturas</h2>
        <div class="temperaturas">
            <?php
                $suma = 0;
                $cantidad = 10;

                for ($i = 0; $i < $cantidad; $i++) {
                    $temp = rand(-10, 40);
                    $suma = $suma + $temp;

                    if ($temp < 10) {
                        echo "<div class='temp frio'>$temp °C<br>Frío</div>";
                    } elseif ($temp <= 25) {
                        echo "<div class='temp suave'>$temp °C<br>Temperatura Suave</div>";
                    } else {
                        echo "<div class='temp calor'>$temp °C<br>Calor</div>";
                    }
                }

                $media = $suma / $cantidad;
                echo "</div>"; 
                echo "<p class='resultado'>Media de las temperaturas: " . number_format($media, 2) . " °C</p>"; // con . por que estoy incluyendo una funcion en el medio del HTML que despliego
            ?>
        </div>
    </div>
    <p><a href="index.php">Volver al índice</a></p>
</body>
</html>
