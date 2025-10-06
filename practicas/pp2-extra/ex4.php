<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 4 - Pares agrupados</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #fafafa; }
        .grupo { margin: 20px auto; padding: 10px; border: 2px solid #ccc;  display: inline-block; border-radius: 8px; }
        .titulo { background: #ffd60a; padding: 5px; font-weight: bold; }
        .numeros { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin-top: 10px; }
        .num {
            padding: 10px;
            border-radius: 5px;
            width: 50px;
            text-align: center;
            font-weight: bold;
        }
        .mult4 { background: lightblue; }
        .mult6 { background: lightcoral; }
        .mult12 { background: orange; border: 2px solid red; }
        .resumen { margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Pares entre 50 y 500 agrupados por decenas</h2>
    <?php
        $totalContador = 0;
        $totalSum = 0;

        for ($decena = 50; $decena <= 500; $decena += 10) {
            $contador = 0;
            $sum = 0;
            echo "<div class='grupo'>";
            echo "<div class='titulo'>De $decena a " . ($decena + 9) . "</div>"; //con punto por que debo incluir la operacion en medio del HTML que quiero mostrar con el echo
            echo "<div class='numeros'>";
            for ($i = $decena; $i <= $decena + 9 && $i <= 500; $i++) {
                if ($i % 2 == 0) {
                    $class = "num";
                    if ($i % 12 == 0) {
                        $class .= " mult12"; //.= concatena strings con el espacio para pegar una clase detras de otra en el CSS
                    } elseif ($i % 6 == 0) {
                        $class .= " mult6";
                    } elseif ($i % 4 == 0) {
                        $class .= " mult4";
                    }
                    echo "<div class='$class'>$i</div>";
                    $contador++;
                    $sum += $i;
                }
            }
            echo "</div>";
            echo "<div class='resumen'>Cantidad de elementos: $contador | Suma del Grupo De $decena a " . ($decena + 9) . ": $sum</div>";
            echo "</div>";
            echo "<br>";
            $totalContador += $contador;
            $totalSum += $sum;
        }

        echo "<h3>Total global de pares: $totalContador | Suma global: $totalSum</h3>";
    ?>
    <p><a href="index.php">Volver al índice</a></p>
</body>
</html>
