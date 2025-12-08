<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 6 - Análisis de valores aleatorios con cambios de zona</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #fafafa; }
        .contenedor { margin: 20px auto; padding: 20px; border: 2px solid #333; border-radius: 8px; display: inline-block; }
        .grid { display: grid; grid-template-columns: repeat(10, 1fr); gap: 8px; }
        .valor { padding: 10px; border-radius: 5px; font-weight: bold; color: white; }
        .azul { background: #007bff; }   /* Valores menores a 33 */
        .gris { background: #7f8c8d; }   /* Valores entre 33 y 66 */
        .verde { background: #27ae60; }  /* Valores mayores a 66 */
        .resultado { margin-top: 15px; font-weight: bold; }
        .leyenda { margin-top: 10px; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>Análisis de 30 valores aleatorios</h2>
        <div class="grid">
            <?php
                // Inicializamos los arrays y contadores
                $valores = [];      // Array para guardar los números generados
                $pares = 0;         
                $impares = 0;       
                $cambios = 0;       // Contador de cambios de zona
                $zonaAnterior = ""; // Variable para guardar la zona anterior

                
                for ($i = 0; $i < 30; $i++) {
                    $num = rand(0, 100); 
                    $valores[] = $num;   // Lo guardamos en el array

                    // Determinar la clase de color según la zona
                    $clase = "gris";     // Por defecto gris (33–66)
                    if ($num < 33) $clase = "azul";
                    elseif ($num > 66) $clase = "verde";

                    // Contar pares e impares
                    if ($num % 2 == 0) {
                        $pares++; 
                    }
                    else {
                        $impares++;
                    }

                    // Contar cambios de zona comparando con el valor anterior
                    if ($i > 0 && $clase != $zonaAnterior) {
                        $cambios++;
                    }
                    $zonaAnterior = $clase; // Actualizamos la zona anterior

                    // Mostrar el número en su div correspondiente
                    echo "<div class='valor $clase'>$num</div>";
                }

                // Calcular mínimo, máximo y media
                $min = min($valores);
                $max = max($valores);
                $media = array_sum($valores) / count($valores);

                echo "</div>"; // cerrar grid

                // Mostrar resultados estadísticos
                echo "<p class='resultado'>Mínimo: $min | Máximo: $max | Media: " . number_format($media, 2) . "</p>";
                echo "<p class='resultado'>Pares: $pares | Impares: $impares | Cambios de zona: $cambios</p>";

                // Leyenda de colores
                echo "<p class='leyenda'>Leyenda: <span class='azul'>Azul &lt; 33</span> | <span class='gris'>Gris 33–66</span> | <span class='verde'>Verde &gt; 66</span></p>";
            ?>
        </div>
    </div>
    <p><a href="index.php">Volver al índice</a></p>
</body>
</html>
