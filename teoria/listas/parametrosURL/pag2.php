<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>p2</title>
</head>
<body>
    <h1>Esta es la pagina 2</h1>
    <?php
    echo $_GET['nom'];
    echo '<br>';
    echo $_GET['edat'];

    //isset: lo usamos para comprobar que existe un parametro con ese nombre
    if(isset($_GET['nom'])){

        $n = $_GET['nom'];
        echo $n;
    }
    else{
        echo 'no existe el parametro nombre';
    }
    ?>
</body>
</html>