<?php
// Cabecera de la tienda online. Muestra el logo, un saludo con el nombre del usuario
// y, si está disponible, la foto de perfil enviada desde el formulario.
// Este archivo se carga dentro de index.php, después de recibir los datos POST.

// -------------------------------------------------------------
// 1) Inicialización de variables de visualización
// -------------------------------------------------------------
// Definimos las variables $displayNombre y $displayFoto con valor inicial null.
// Esto evita avisos de "variable indefinida" si luego intentamos usarlas sin haberlas asignado.
// Además deja explícito que, por defecto, no hay nada que mostrar (estado inicial vacío).
$displayNombre = null;
$displayFoto = null;

// -------------------------------------------------------------
// 2) Procesado del nombre ($nombre)
// -------------------------------------------------------------
// Primero comprobamos que la variable $nombre exista y contenga algún valor no vacío.
//
//   isset($nombre) -> devuelve true si la variable está definida.
//   trim($nombre)  -> elimina los espacios en blanco al inicio y al final de la cadena.
//
// Por qué usamos trim():
//   - Un usuario podría escribir solo espacios ("   ") en el formulario. Sin trim, eso contaría como texto.
//   - trim() limpia espacios, tabulaciones o saltos de línea al inicio y final,
//     permitiendo detectar realmente si el campo está vacío.
//   - No modifica los espacios internos (entre palabras).
//
// Usamos trim en este punto antes de escapar o validar, porque necesitamos asegurarnos
// de que el valor no sea "vacío visualmente". Si hiciéramos el escape antes,
// escaparíamos incluso espacios innecesarios.
//
// Si el nombre pasa la validación, lo guardamos en $displayNombre aplicando htmlspecialchars()
// para que sea seguro imprimirlo en HTML más adelante.
if (isset($nombre) && trim($nombre) !== '') {
    // htmlspecialchars convierte caracteres especiales (<, >, &, ", ')
    // en entidades HTML seguras. Evita ataques XSS o inyección de código en el navegador.
    // ENT_QUOTES: convierte comillas simples y dobles.
    // 'UTF-8': define la codificación (coincide con la de la página).
    // false: evita el doble escape si la cadena ya fue escapada en otro lugar.
    $displayNombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8', false);
}

// -------------------------------------------------------------
// 3) Procesado de la foto ($foto)
// -------------------------------------------------------------
// Validamos que haya una URL válida antes de mostrar la imagen.
//   isset($foto) -> comprueba que la variable exista.
//   filter_var($foto, FILTER_VALIDATE_URL) -> valida que el valor tenga formato de URL.
// Si la URL es válida, también la escapamos con htmlspecialchars() para imprimirla en el HTML.
if (isset($foto) && filter_var($foto, FILTER_VALIDATE_URL)) {
    $displayFoto = htmlspecialchars($foto, ENT_QUOTES, 'UTF-8', false);
}

// -------------------------------------------------------------
// 4) Renderizado del header (HTML)
// -------------------------------------------------------------
?>
<header style="display:flex;align-items:center;gap:10px;padding:10px;border-bottom:1px solid #ccc;">
  <!-- Logo de la tienda -->
  <img src="/tienda/assets/logo-mercadona.jpg" alt="Logo" style="height:50px;">

  <div>
    <?php if ($displayNombre): ?>
      <!-- Si hay nombre válido, mostramos saludo personalizado -->
      <div>Bienvenido, <?php echo $displayNombre; ?></div>
    <?php else: ?>
      <!-- Si no hay nombre, mostramos texto genérico -->
      <div>Bienvenido a la tienda</div>
    <?php endif; ?>
  </div>

  <?php if ($displayFoto): ?>
    <!-- Si hay foto válida, la mostramos a la derecha -->
    <img src="<?php echo $displayFoto; ?>" alt="Foto de perfil" style="height:50px;border-radius:50%;margin-left:auto;">
  <?php endif; ?>
</header>

<?php
// -------------------------------------------------------------
// 5) Explicación técnica y de seguridad
// -------------------------------------------------------------
//
// htmlspecialchars()
// - Convierte caracteres especiales en entidades HTML seguras:
//     <  → &lt;
//     >  → &gt;
//     &  → &amp;
//     "  → &quot;
//     '  → &#039;
// - Esto evita que un valor enviado por el usuario (por ejemplo un script)
//   se ejecute en el navegador.
//
// trim()
// - Elimina espacios en blanco, tabulaciones y saltos de línea
//   al inicio y final de una cadena. Ejemplo:
//      trim("   Juan  ") → "Juan"
// - Previene falsos positivos de campos "vacíos" (solo espacios).
// - Es útil en validaciones de formularios, especialmente antes de comprobar si una cadena está vacía.
//
// Orden lógico:
// 1) trim() -> limpiar valor recibido de caracteres inútiles.
// 2) Validar formato (isset, filter_var).
// 3) Escapar con htmlspecialchars() justo antes de imprimir en HTML.
// Transformar ciertos caracteres especiales en una representación 
// segura para que no sean interpretados como código, sino mostrados como texto literal en la página.
//
// Este orden garantiza:
// - Los datos están limpios (sin espacios sobrantes).
// - Son válidos (cumplen formato).
// - Son seguros de mostrar.
