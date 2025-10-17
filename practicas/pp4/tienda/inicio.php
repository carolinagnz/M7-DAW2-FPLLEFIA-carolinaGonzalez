<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Tienda</title>
</head>
<body>
  <h1>Bienvenido a la tienda</h1>

  <!--
    Formulario HTML:
    - action="index.php": especifica a qué URL se enviarán los datos cuando el usuario pulse "Entrar a la tienda".
      En este caso se envían a index.php porque ese archivo se encargará de mostrar la tienda y procesar los datos.
      Importante: enviarlo a index.php aquí es deliberado. Si se enviara a otro archivo, ese sería el que debería
      procesar los datos y mostrarlos.
    - method="post": indica que los datos se mandan por POST. Usamos POST para no exponer los datos en la URL.
      POST es apropiado para datos de formulario que no deben aparecer en la barra de direcciones.
    - required en los inputs obliga al navegador a que el campo no quede vacío antes de permitir enviar.
  -->
  <form action="index.php" method="post">
    <label>Nombre:<br>
      <!-- name="nombre": clave con la que PHP recibirá el valor en $_POST['nombre'] -->
      <input type="text" name="nombre" required>
    </label><br><br>

    <label>Teléfono:<br>
      <!-- type="tel" ayuda a navegadores móviles a mostrar teclado numérico -->
      <input type="tel" name="telefono" required>
    </label><br><br>

    <label>URL foto de perfil:<br>
      <!-- type="url" ayuda a validar en el cliente que el valor tiene formato de URL -->
      <input type="url" name="foto" placeholder="https://ejemplo.com/mi-foto.jpg" required>
    </label><br><br>

    <button type="submit">Entrar a la tienda</button>
  </form>

  <!--
    Concepto pedagógico:
    - Este archivo solo recoge datos y los envía. No debe contener lógica compleja ni acceso a la lista de productos.
    - Separar la pantalla de inicio del listado facilita la reutilización y el flujo: primero pides identidad, luego muestras contenido personalizado.
  -->
</body>
</html>