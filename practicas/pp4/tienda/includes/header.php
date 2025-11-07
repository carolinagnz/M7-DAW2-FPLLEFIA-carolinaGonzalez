<?php
// ============================================================================
// ARCHIVO: header.php
// PROPÓSITO: Cabecera reutilizable que muestra el logo y mensaje de bienvenida
// ============================================================================
//
// Este archivo es un COMPONENTE reutilizable.
// Se incluye en todas las páginas que necesitan mostrar la cabecera,
// manteniendo así un diseño consistente en toda la aplicación.
//
// CONCEPTO: Variables compartidas
// Este archivo tiene acceso a las variables definidas en el archivo que lo incluye.
// Por ejemplo, si index.php define $nombre y $foto, este archivo puede usarlas.
// Esto se debe a que require_once simplemente "pega" el código aquí,
// compartiendo el mismo ámbito de variables.
//
// ============================================================================

// ----------------------------------------------------------------------------
// PASO 1: Preparar variables para mostrar
// ----------------------------------------------------------------------------
// Verificamos si existe la variable $nombre y si tiene contenido.
// Si existe y no está vacía, la usamos. Si no, usamos un valor por defecto.

// OPERADOR ?? (null coalescing):
// Lee: "si $nombre existe y no es null, úsalo; si no, usa 'Invitado'"
$displayNombre = isset($nombre) && trim($nombre) !== '' ? trim($nombre) : 'Invitado';

// Hacemos lo mismo con la foto
// Si no hay foto válida, no la mostramos (dejamos null)
$displayFoto = isset($foto) && trim($foto) !== '' ? trim($foto) : null;

?>
<!-- Salimos del modo PHP para escribir HTML -->

<!-- ========================================================================== -->
<!-- HEADER: Barra de navegación con logo y bienvenida -->
<!-- ========================================================================== -->
<!--
ESTRUCTURA:
- <header>: etiqueta semántica HTML5 que indica que esto es la cabecera
- navbar: clase de Bootstrap para crear una barra de navegación
- navbar-expand-lg: la barra se expande en pantallas grandes
- navbar-light: esquema de colores claro
- bg-light: fondo de color claro
- mb-5: margen inferior de 5 unidades (Bootstrap spacing)
-->
<header class="navbar navbar-expand-lg navbar-light bg-light mb-5">
    <div class="container-fluid d-flex justify-content-between">
        
        <!-- ================================================================== -->
        <!-- LOGO de la tienda -->
        <!-- ================================================================== -->
        <!-- 
        El logo es un enlace que apunta a index.php (la página principal).
        Si el usuario hace clic, vuelve al inicio.
        -->
        <a class="navbar-brand" href="inicio.php">
            <img src="../assets/logo_mercadona.jpg" 
                 alt="Logo Mercadona" 
                 style="height:50px;">
        </a>
        
        <!-- ================================================================== -->
        <!-- SALUDO y AVATAR del usuario -->
        <!-- ================================================================== -->
        <!--
        Esta sección muestra:
        1. Un mensaje de bienvenida con el nombre del usuario
        2. La foto de perfil (avatar) si se proporcionó
        
        Clases de Bootstrap usadas:
        - d-flex: activa flexbox (sistema de layout flexible)
        - align-items-center: centra elementos verticalmente
        - me-3: margen derecho de 3 unidades
        - mb-0: margen inferior de 0 (quita el margen por defecto del h2)
        - px-4: padding horizontal de 4 unidades
        -->
        <div class="d-flex align-items-center">
            
            <!-- Mensaje de bienvenida -->
            <!-- Usamos <?= $displayNombre ?> para insertar el nombre -->
            <h2 class="me-3 mb-0 px-4">
                ¡Bienvenido, <?= $displayNombre ?>!
            </h2>
            
            <!-- Avatar (solo si hay foto) -->
            <!-- Usamos un if de PHP para mostrar la imagen solo si $displayFoto tiene valor -->
            <?php if ($displayFoto): ?>
                <img src="<?= $displayFoto ?>" 
                     alt="Avatar de <?= $displayNombre ?>" 
                     class="rounded-circle" 
                     style="width:50px;height:50px;object-fit:cover;">
            <?php endif; ?>
            <!--
            NOTA sobre la sintaxis PHP en HTML:
            <?php if ($condicion): ?>
                HTML que se muestra si es verdadero
            <?php endif; ?>
            
            Esta es la sintaxis alternativa de if en PHP, diseñada específicamente
            para mezclar PHP y HTML. Es más legible que usar llaves { }.
            
            Equivalente con llaves:
            <?php if ($displayFoto) { ?>
                <img ...>
            <?php } ?>
            -->
            
        </div>
        
    </div>
</header>

<!-- Volvemos al modo PHP -->
<?php
// ============================================================================
// FIN DEL ARCHIVO header.php
// ============================================================================
//
// RESUMEN DE CONCEPTOS:
// ✓ Componentes reutilizables con require_once
// ✓ Variables compartidas entre archivos incluidos
// ✓ Valores por defecto con operador ternario
// ✓ Sintaxis alternativa de if (if: ... endif;)
// ✓ Bootstrap para diseño responsivo
// ✓ Clases de utilidad de Bootstrap (d-flex, align-items-center, etc.)
//
// CONEXIÓN CON LA PRÁCTICA:
// - Este archivo se incluye en index.php con: require_once 'includes/header.php'
// - Tiene acceso a $nombre y $foto porque index.php las define antes de incluirlo
// - Proporciona una cabecera consistente que podría reutilizarse en otras páginas
// ============================================================================ ?>