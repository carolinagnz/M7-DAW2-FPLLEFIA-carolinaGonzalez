<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="inicio.php" method="get"> 
        
        <input type="text" name="nombre" placeholder="Nombre">
        <input type="number" name="contraseña" placeholder="Contraseña">
        <input type="submit" value="Enviar">
    </form>


    <form action="inicio.php" method="post"> 
        
        <input type="text" name="nombre" placeholder="Nombre">
        <input type="number" name="contraseña" placeholder="Contraseña">
        <input type="submit" value="Enviar">
    </form>
    
    
    <?php
    //recoger datos en el formulario CON GET
    $nombre = "Carolina";
    $contraseña =  23541027;

    echo '<br>';
    echo $_GET['nombre'];
    echo '<br>';
    echo $_GET['contraseña'];

    //ahora envio con post
    echo '<br>';
    echo $_POST['nombre'];
    echo '<br>';
    echo $_POST['contraseña'];
    ?>
</body>
</html>