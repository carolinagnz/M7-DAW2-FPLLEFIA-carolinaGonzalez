<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>p1 php</title>
</head>
<body>
    <h1>Hola pp1 teoria</h1>

    <?php
    echo '<h2>Hola subtitulo</h2>';
    echo "Hola mundo con dos comillas";
    
    $nom = 'Miguel Angel';
    $apellido = "Saiz";
    $edad = 21;
    $frase = "Hola soy $nom $apellido y tengo $edad anyos";

    echo '<br>';
    echo "Hola me llamo". $nom . "tengo" . $edad . "años!";
    echo '<br>';
    echo "Hola me llamo {$nom}  tengo {$edad} y me apellido {$apellido}";
    echo '<br>';
    echo $frase;


    echo '<br>';


    //Condicionales

    //if

    if($edad<22){
        echo "eres mayor de edad";
    }else{
        echo "eres menor de edad";
    }
    //==
    //%
    //!=
    //<= >= < >

    ?>

    <section class="div-padre">
        <h1>Numeros del 0-10</h1>
        
        <?php

        //Bucles

        for($i=0; $i<=10; $i++){
            //echo "Numero: $i <br> ";
            echo "<div class=\"num-box\">Numero: $i <br></div>";
            echo '<div class="num-box">Numero: '. $i .' <br></div>';
            echo "<div class='num-box'>Numero: $i <br></div>";

        }

        ?>
    </section>    

    <style>
        .num-box{
            background-color: red;
            padding: 2rem;
        }
        .div-padre{
            background-color: green;
            gap: 1em;
            display: flex;
            flex-wrap: wrap;
        }
    </style>
</body>
</html>
