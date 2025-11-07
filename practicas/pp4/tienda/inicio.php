<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Tienda Mercadona</title>
    <style>
        /* Estilos básicos para centrar el formulario */
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
  <h1>🛒 Bienvenido a la tienda Mercadona</h1>
  <p>Por favor, introduce tus datos para acceder:</p>
  
  <!-- ==================================================================== -->
  <!-- FORMULARIO: Recogida de datos del usuario -->
  <!-- ==================================================================== -->
  <!-- 
  CONCEPTOS CLAVE DE FORMULARIOS HTML:
  
  1. ATRIBUTO action="index.php":
     Define A DÓNDE se enviarán los datos cuando se envíe el formulario.
     En este caso, los datos van a index.php porque ese archivo es el que
     los procesará y mostrará la tienda.
  
  2. ATRIBUTO method="post":
     Define CÓMO se envían los datos. Hay dos métodos principales:
     
     - GET: Los datos viajan en la URL (visible en la barra de direcciones)
       Ejemplo: index.php?nombre=Juan&telefono=612345678
       Uso: búsquedas, filtros, datos no sensibles
     
     - POST: Los datos viajan en el cuerpo de la petición HTTP (ocultos)
       No aparecen en la URL
       Uso: formularios con datos personales, contraseñas, archivos
     
     Usamos POST aquí porque:
     - Los datos son personales (nombre, teléfono)
     - No queremos que aparezcan en la URL
     - Es más seguro para este tipo de información
  
  3. ATRIBUTO name en los <input>:
     El atributo "name" es CRUCIAL porque es la KEY que PHP usará
     para acceder al valor en el array $_POST.
     
     Si ponemos: <input type="text" name="nombre">
     En PHP accederemos con: $_POST['nombre']
     
     El nombre debe ser descriptivo y coincidir con cómo lo referenciaremos
     en el código PHP.
  
  4. ATRIBUTO required:
     Validación HTML5 en el lado del cliente (navegador).
     Impide enviar el formulario si el campo está vacío.
     
     IMPORTANTE: La validación HTML5 NO es suficiente para seguridad.
     Un usuario técnico puede desactivarla o enviar datos directamente.
     Por eso SIEMPRE debemos validar también en PHP (lado del servidor).
  
  5. ATRIBUTO type:
     Define el tipo de input y activa validaciones específicas:
     - type="text": texto genérico
     - type="tel": optimiza teclado en móviles para números de teléfono
     - type="url": valida que sea una URL bien formada
     - type="email": valida formato de email
     - etc.
  -->
  
  <form action="index.php" method="post">
    
    <!-- Campo: Nombre -->
    <label>
      <strong>Nombre:</strong><br>
      <!-- 
      name="nombre": PHP lo recibirá como $_POST['nombre']
      required: campo obligatorio (validación cliente)
      -->
      <input type="text" 
             name="nombre" 
             placeholder="Ej: Juan Pérez" 
             required>
    </label>
    
    <!-- Campo: Teléfono -->
    <label>
      <strong>Teléfono:</strong><br>
      <!-- 
      type="tel": en móviles muestra teclado numérico
      pattern: expresión regular para validar formato (opcional)
      -->
      <input type="tel" 
             name="telefono" 
             placeholder="Ej: 612345678" 
             required>
    </label>
    
    <!-- Campo: URL de la foto -->
    <label>
      <strong>URL foto de perfil:</strong><br>
      <!-- 
      type="url": valida que tenga formato de URL (http://... o https://...)
      placeholder: texto de ejemplo que desaparece al escribir
      -->
      <input type="url" 
             name="foto" 
             placeholder="https://ejemplo.com/mi-foto.jpg" 
             required>
    </label>
    
    <!-- Botón de envío -->
    <!-- 
    type="submit": indica que este botón envía el formulario
    Al hacer clic, el navegador:
    1. Valida los campos required y los types especiales
    2. Si todo es correcto, envía los datos a la URL del action
    3. Redirige automáticamente a esa URL
    -->
    <button type="submit">🚀 Entrar a la tienda</button>
    
  </form>
  
  <!-- ==================================================================== -->
  <!-- FLUJO DE DATOS: De este formulario a index.php -->
  <!-- ==================================================================== -->
  <!--
  1. Usuario rellena: nombre="Juan", telefono="612345678", foto="http://..."
  2. Usuario hace clic en "Entrar a la tienda"
  3. El navegador crea una petición HTTP POST a index.php con estos datos
  4. PHP en index.php recibe los datos automáticamente en $_POST:
     $_POST = [
       'nombre' => 'Juan',
       'telefono' => '612345678',
       'foto' => 'http://...'
     ]
  5. index.php usa esos datos para personalizar la experiencia
  -->
  
</body>
</html>