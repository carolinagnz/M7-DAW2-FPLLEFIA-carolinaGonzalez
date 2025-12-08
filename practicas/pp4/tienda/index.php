<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercadona - Productos</title>
    <!-- Bootstrap CSS para estilos bonitos y responsivos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
          crossorigin="anonymous">
</head>
<body>

<?php
// ============================================================================
// SECCIÓN PHP: LÓGICA DEL SERVIDOR
// ============================================================================
// Esta sección se ejecuta en el servidor ANTES de enviar cualquier HTML al navegador.
// Aquí procesamos datos, incluimos archivos y preparamos variables.

// ----------------------------------------------------------------------------
// PASO 1: Recibir los datos del formulario enviado desde inicio.php
// ----------------------------------------------------------------------------
// CONCEPTO: Superglobal $_POST
// 
// $_POST es un array asociativo que PHP crea automáticamente con los datos
// que se envían mediante el método POST de un formulario.
//
// ¿Cómo llegan los datos aquí?
// 1. El usuario rellena el formulario en inicio.php
// 2. Al pulsar "Entrar a la tienda", el navegador envía los datos al servidor
// 3. Como el action del form es "index.php", este archivo los recibe
// 4. PHP automáticamente crea $_POST con los valores, usando los atributos
//    "name" de los inputs como keys del array
//
// Ejemplo de qué contiene $_POST cuando llega aquí:
// $_POST = [
//     'nombre' => 'Juan',
//     'telefono' => '612345678',
//     'foto' => 'https://ejemplo.com/foto.jpg'
// ]
//
// OPERADOR ?? (null coalescing):
// Es un operador moderno de PHP que significa "usa esto, o si no existe, usa aquello"
// Sintaxis: $variable = $valor ?? $valorPorDefecto
//
// Equivalente en if tradicional:
// if (isset($_POST['nombre'])) {
//     $nombre = $_POST['nombre'];
// } else {
//     $nombre = '';
// }
//
// ¿Por qué usarlo?
// Porque si alguien accede directamente a index.php sin pasar por el formulario,
// $_POST['nombre'] no existirá y daría error. Con ??, obtenemos un valor vacío
// en su lugar, evitando el error.

$nombre = $_POST['nombre'] ?? '';     // Si no existe, será string vacío
$telefono = $_POST['telefono'] ?? ''; // Ídem
$foto = $_POST['foto'] ?? '';         // Ídem

// ----------------------------------------------------------------------------
// PASO 2: Incluir el archivo de funciones
// ----------------------------------------------------------------------------
// CONCEPTO: require_once
//
// require_once incluye y ejecuta el archivo especificado.
// "once" significa que PHP recordará que ya lo incluyó y no lo volverá a incluir
// aunque haya múltiples require_once del mismo archivo (evita duplicados).
//
// ¿Diferencia entre include, require, include_once, require_once?
// - include: incluye el archivo, pero si no existe, sólo muestra warning y continúa
// - require: incluye el archivo, pero si no existe, genera error fatal y se detiene
// - include_once / require_once: igual que los anteriores pero sólo una vez
//
// ¿Cuándo usar cada uno?
// - require_once: para archivos críticos (sin ellos no puede funcionar la app)
// - include_once: para archivos opcionales (como widgets)
//
// __DIR__: constante mágica de PHP que contiene la ruta del directorio actual
// Es mejor que usar rutas relativas simples porque funciona siempre, no importa
// desde dónde se ejecute el script.

require_once __DIR__ . '/includes/funciones.php';

// Al ejecutar esta línea, PHP lee funciones.php y ahora tenemos disponibles:
// - generarTablaProductos()
// - muestraInfoContacto()

// ----------------------------------------------------------------------------
// PASO 3: Incluir el array de productos
// ----------------------------------------------------------------------------
// Incluimos el archivo que define $productos
// Después de esta línea, tendremos la variable $productos disponible
// con todos los datos de los productos de la tienda.

require_once __DIR__ . '/data/productos.php';

// Ahora $productos contiene:
// [
//   ['nombre' => 'manzana', 'precio' => 0.75, 'disponible' => true, ...],
//   ['nombre' => 'pan', 'precio' => 1.20, 'disponible' => true, ...],
//   ...
// ]

// ----------------------------------------------------------------------------
// PASO 4: Incluir el header
// ----------------------------------------------------------------------------
// El header tiene acceso a las variables $nombre y $foto que definimos arriba
// porque PHP comparte el scope (ámbito) de las variables entre archivos incluidos.
// Por eso el header puede usarlas para mostrar el saludo personalizado.

require_once __DIR__ . '/includes/header.php';

?>

<!-- ========================================================================== -->
<!-- SECCIÓN HTML: CONTENIDO VISIBLE -->
<!-- ========================================================================== -->
<!-- A partir de aquí empieza el HTML que ve el usuario -->

<div class="container">
    <!-- Título de la sección -->
    <div class="mb-4">
        <h2>Productos disponibles</h2>
    </div>

    <!-- ====================================================================== -->
    <!-- TABLA DE PRODUCTOS -->
    <!-- ====================================================================== -->
    <!-- Aquí llamamos a la función generarTablaProductos() -->
    <!-- 
    ¿Qué pasa cuando ejecutamos esto?
    1. PHP llama a la función generarTablaProductos() que definimos en funciones.php
    2. Le pasamos como parámetro el array $productos
    3. La función recorre el array, genera todo el HTML de la tabla
    4. La función retorna ese HTML como string
    5. echo muestra ese string en la página
    6. El navegador recibe el HTML y lo renderiza como tabla visual
    
    Esto es MUY potente porque:
    - Si mañana queremos cambiar cómo se ve la tabla, sólo editamos funciones.php
    - Si queremos usar la misma tabla en otra página, sólo llamamos a la función
    - El código de index.php queda limpio y fácil de leer
    -->
    <div class="mb-5">
        <?php echo generarTablaProductos($productos); ?>
    </div>

    <!-- ====================================================================== -->
    <!-- BOTÓN PARA ABRIR MODAL CON INFO DE CONTACTO -->
    <!-- ====================================================================== -->
    <!-- 
    Este botón usa atributos data-bs-* de Bootstrap para controlar el modal:
    - data-bs-toggle="modal": indica que es un trigger de modal
    - data-bs-target="#staticBackdrop": especifica qué modal abrir (por ID)
    -->
    <button type="button" 
            class="btn btn-primary btn-lg mb-3" 
            data-bs-toggle="modal" 
            data-bs-target="#staticBackdrop">
        👤 Ver mi perfil
    </button>

    <!-- ====================================================================== -->
    <!-- MODAL: INFORMACIÓN DE CONTACTO -->
    <!-- ====================================================================== -->
    <!-- 
    CONCEPTO: Modal de Bootstrap
    
    Un modal es una ventana emergente que aparece sobre el contenido principal.
    Bootstrap maneja toda la funcionalidad (abrir/cerrar, overlay oscuro, etc.)
    mediante JavaScript, nosotros sólo definimos el HTML.
    
    Estructura de un modal:
    - .modal: contenedor principal (invisible por defecto)
    - .modal-dialog: define el tamaño y posición
    - .modal-content: el contenido real del modal
      - .modal-header: cabecera (título + botón cerrar)
      - .modal-body: cuerpo (aquí va el contenido)
      - .modal-footer: pie (opcional, para botones de acción)
    
    Atributos importantes:
    - id="staticBackdrop": identificador único para referenciarlo desde el botón
    - data-bs-backdrop="static": el modal NO se cierra al clicar fuera
    - data-bs-keyboard="false": el modal NO se cierra con la tecla ESC
    - tabindex="-1": gestión de foco del teclado
    - aria-*: atributos para accesibilidad (lectores de pantalla)
    -->
    <div class="modal fade" 
         id="staticBackdrop" 
         data-bs-backdrop="static" 
         data-bs-keyboard="false" 
         tabindex="-1" 
         aria-labelledby="staticBackdropLabel" 
         aria-hidden="true">
        
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                
                <!-- Cabecera del modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        📋 Información de contacto
                    </h5>
                    <button type="button" 
                            class="btn-close" 
                            data-bs-dismiss="modal" 
                            aria-label="Close">
                    </button>
                </div>
                
                <!-- Cuerpo del modal: aquí mostramos la info de contacto -->
                <div class="modal-body">
                    <?php
                    // ===================================================
                    // LLAMADA A LA FUNCIÓN muestraInfoContacto()
                    // ===================================================
                    // Esta función mostrará (echo) directamente el HTML
                    // con la información del usuario.
                    //
                    // ¿Qué recibe?
                    // - $nombre: lo recibimos de $_POST al inicio
                    // - $telefono: ídem
                    // - $foto: ídem
                    //
                    // La función validará los datos y generará una tarjeta
                    // bonita con la información.
                    
                    muestraInfoContacto($nombre, $telefono, $foto);
                    ?>
                </div>
                
                <!-- Pie del modal (opcional) -->
                <div class="modal-footer">
                    <button type="button" 
                            class="btn btn-secondary" 
                            data-bs-dismiss="modal">
                        Cerrar
                    </button>
                </div>
                
            </div>
        </div>
    </div>

</div>

<!-- ========================================================================== -->
<!-- FOOTER -->
<!-- ========================================================================== -->
<!-- Incluimos el pie de página que muestra el copyright -->
<?php require_once __DIR__ . '/includes/footer.php'; ?>

<!-- ========================================================================== -->
<!-- JAVASCRIPT DE BOOTSTRAP -->
<!-- ========================================================================== -->
<!-- 
Este script es necesario para que funcionen los componentes interactivos
de Bootstrap como modales, dropdowns, tooltips, etc.

Debe ir justo antes de </body> para que se cargue después del HTML,
asegurando que todos los elementos existan cuando el JS intente manipularlos.
-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
        crossorigin="anonymous">
</script>

</body>
</html>

<!-- ============================================================================
RESUMEN DEL FLUJO COMPLETO DE LA APLICACIÓN:

1. Usuario accede a inicio.php
   ↓
2. Rellena el formulario (nombre, teléfono, foto)
   ↓
3. Envía el formulario (POST) a index.php
   ↓
4. index.php recibe los datos en $_POST
   ↓
5. index.php incluye funciones.php (carga las funciones)
   ↓
6. index.php incluye productos.php (carga el array de productos)
   ↓
7. index.php incluye header.php (muestra saludo personalizado)
   ↓
8. index.php llama a generarTablaProductos() (muestra la tabla)
   ↓
9. Usuario hace clic en "Ver mi perfil"
   ↓
10. Se abre el modal con muestraInfoContacto()
    ↓
11. index.php incluye footer.php (muestra el pie de página)

CONCEPTOS CLAVE APRENDIDOS:
✓ Formularios HTML y envío POST
✓ Recepción de datos con $_POST
✓ Operador null coalescing (??)
✓ require_once para incluir archivos
✓ Ámbito de variables entre archivos incluidos
✓ Llamada a funciones con parámetros
✓ Diferencia entre echo directo y return
✓ Modales de Bootstrap
✓ Separación de lógica en componentes reutilizables
============================================================================ -->