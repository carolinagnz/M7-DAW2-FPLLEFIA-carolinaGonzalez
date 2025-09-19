<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reto 1</title>
</head>
<body>
    <h1>Pelis Favoritas - Top 10</h1>
   
    <table>
        <thead>
            <tr class='encabezado'>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Puntuacion</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $lista_peliculas = ["LOTR", "Orgullo y Prejuicio", "Interestelar", "Harry Potter", "Indiana Jones", "El Diablo viste de Prada", "John Wick", "Dune", "Matrix", "Entrevista con el Vampiro" ];
            $lista_imagenes = ["https://i.pinimg.com/236x/7b/a8/aa/7ba8aa8a1711ccde9476d958d6345c56.jpg", "https://i.pinimg.com/236x/7b/a8/aa/7ba8aa8a1711ccde9476d958d6345c56.jpg", "https://i.pinimg.com/236x/7b/a8/aa/7ba8aa8a1711ccde9476d958d6345c56.jpg" ];
            $lista_puntuacion = ["8", "5", "2" ,"8", "5", "2" ,"8", "5", "2" , "7" ];
            
            for($i=0; $i<=9; $i++){
                echo $i;
                
                echo "<tr>";
                echo "<td>$lista_peliculas[$i]</td>";
                echo "<td>
                    <img src='$lista_imagenes[$i]' alt='Foto'>
                    </td>";
                if($lista_puntuacion[$i]<5){
                    //echo "<tr>";
                    echo "<td class= 'puntosr'>$lista_puntuacion[$i]</td>";
                    //echo "</tr>";
                }else{
                    echo "<td class= 'puntosg'>$lista_puntuacion[$i]</td>";
                }
                echo "</tr>";

                
            }       
            
            ?>
        </tbody>
    </table>

    <style>
        .puntosr{
            background-color: red;
        }
        .puntosg{
            background-color: green;
        }
        .encabezado{
            background-color: yellow;
        }

    </style>
</body>
</html>