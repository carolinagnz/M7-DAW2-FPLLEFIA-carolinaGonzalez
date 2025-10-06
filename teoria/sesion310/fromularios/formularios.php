<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="inicio.php" method="get"> 
        ////action a donde lo enviamos y method get o post
        <input type="text" name="nombre" placeholder="Nombre">
        <input type="number" name="edad" placeholder="Edad">
        <input type="submit" value="Enviar">
    </form>
    
    //recoger datos en el formulario
    <?php

    echo $_GET['nom'];

    //ahora envio con post
    ?>
</body>
</html>