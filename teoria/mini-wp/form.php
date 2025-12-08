<?php
include_once 'data.php';


//comprueba si se ha enviado el formulario

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //recojo datos del formulario
    $titulo = $_POST['titulo'] ?? '';
    $contenido = $_POST['contenido'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $imagen = $_POST['imagen'] ?? '';
    $categoria = $_POST['categoria'] ?? '';


// validamos los datos con trim con empty y con isset
if(isset($titulo) && !empty(trim($titulo)) && 
 isset($contenido) && !empty(trim($contenido)) &&
 isset($fecha) && !empty(trim($fecha)) &&
 isset($imagen) && !empty(trim($imagen)) &&
 isset($categoria) && !empty(trim($categoria))
    ){
        //creamos un array asociativo con los datos de la noticia
        array_push($noticias,[
            'titulo' => $titulo,
            'contenido' => $contenido,
            'fecha' => $fecha,
            'imagen' => $imagen,
            'categoria' => $categoria
        ]);

        //mensaje de exito
        echo "<p style='color:green;font-weight:bold;'>Noticia añadida correctamente</p>";
    }else{
        //mensaje de error
        echo "<p style='color:red;font-weight:bold;'>Error al añadir la noticia. Todos los campos son obligatorios.</p>";   
    }
}else{
    //mensaje de error
    echo "<p style='color:red;font-weight:bold;'>Error al añadir la noticia. El formulario no se ha enviado correctamente.</p>";    }

//fin del if del formulario

//Ahora agrego el formulario html
?>
<form>
    <label>Título:<br>
      <input type="text" id="titulo" name="titulo" required>
    </label><br><br>

    <label>Contenido:<br>
      <textarea name="contenido" id="contenido" rows="4" cols="50" required></textarea>
    </label><br><br>

    <label>Fecha:<br>
      <input type="date" id="fecha" name="fecha" required>
    </label><br><br>

    <label>URL imagen:<br>
      <input type="url" id="imagen" name="imagen" placeholder="https://ejemplo.com/mi-imagen.jpg" required>
    </label><br><br>

    <label>Categoría:<br>
      <input type="text" id="categoria" name="categoria" required>
    </label><br><br>

    <button type="submit">Añadir noticia</button>
</form>
<?php
//fin del formulario
echo "<h2>Noticias actuales:</h2>";
//muestro las noticias actuales
foreach($noticias as $noticia){
    echo "<article style='background-color: white; margin: 20px; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>";
    echo "<h2>" . htmlspecialchars($noticia['titulo']) . "</h2>";
    echo "<p><em>" . htmlspecialchars($noticia['fecha']) . " | " . htmlspecialchars($noticia['categoria']) . "</em></p>";
    echo "<img src='" . htmlspecialchars($noticia['imagen']) . "' alt='" . htmlspecialchars($noticia['titulo']) . "' style='max-width: 100%; height: auto; border-radius: 10px;'>";
    echo "<p>" . nl2br(htmlspecialchars($noticia['contenido'])) . "</p>";
    echo "</article>";
}
//fin del foreach
    
  