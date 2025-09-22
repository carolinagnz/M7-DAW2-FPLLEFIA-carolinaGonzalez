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
            $lista_imagenes = ["https://preview.redd.it/what-are-aspects-that-the-lotr-movies-do-better-than-other-v0-bsr780pcxjuc1.jpeg?width=1080&crop=smart&auto=webp&s=e034e119430492b7cac4882e33c0d202bb16f8c7",
             "https://m.media-amazon.com/images/I/81uKjJvJj5L._UF1000,1000_QL80_.jpg",
              "https://pics.filmaffinity.com/Interstellar-242418919-large.jpg",
                "https://masdecibelios.es/wp-content/uploads/2024/01/Posters-de-todas-las-peliculas-de-la-saga-Harry-Potter-jpg.webp",
                "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRj-6Z267bkdQgTWF9geqsnAEjVrVNIQKugUA&s",
                "https://img2.rtve.es/i/?w=1600&i=1696586176939.jpg",
                "https://upload.wikimedia.org/wikipedia/en/thumb/9/98/John_Wick_TeaserPoster.jpg/250px-John_Wick_TeaserPoster.jpg",
                "https://i.blogs.es/2af678/dune-cartel/1366_2000.jpeg",
                "https://i.blogs.es/8b8798/06-06-matrix/1366_521.jpg",
                "https://www.mubis.es/media/users/4750/271323/26-anos-de-entrevista-con-el-vampiro-original.jpeg" ];
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
        td img {
            width: 350px;   /* todas del mismo ancho */
            height: auto;   /* se ajusta automáticamente, sin deformar */
        }
        td, th {
            text-align: center;      /* centra horizontalmente */
            vertical-align: middle;  /* centra verticalmente */
        }

    </style>
</body>
</html>