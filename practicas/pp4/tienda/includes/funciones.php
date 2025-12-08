<?php
// ============================================================================
// ARCHIVO: funciones.php
// PROPÓSITO: Definir funciones reutilizables que generan contenido HTML dinámico
// ============================================================================
//
// Este archivo contiene funciones auxiliares que separan la lógica de presentación
// del resto del código. Es una práctica fundamental en programación llamada
// "separación de responsabilidades" o "modularización".
//
// ¿Por qué crear funciones en un archivo separado?
// 1. REUTILIZACIÓN: Podemos usar estas funciones en múltiples páginas sin copiar código
// 2. MANTENIMIENTO: Si queremos cambiar cómo se muestra algo, solo editamos aquí
// 3. ORGANIZACIÓN: El código queda más limpio y fácil de entender
// 4. TESTEO: Podemos probar estas funciones de forma independiente
//
// Conceptos clave que veremos:
// - Funciones con parámetros
// - Interpolación de variables en strings
// - Operador ternario (condición ? verdadero : falso)
// - Bucles foreach para recorrer arrays
// - Formateo de números con number_format()
// - Manipulación de strings con ucfirst()
// - Alternancia entre modo PHP y modo HTML
// ============================================================================

/**
 * FUNCIÓN 1: generarTablaProductos
 * 
 * PROPÓSITO:
 * Recibe un array de productos y genera una tabla HTML completa con Bootstrap
 * mostrando nombre, precio, disponibilidad y descripción de cada producto.
 * 
 * @param array $productos - Array asociativo con los datos de los productos
 *                           Cada elemento debe tener: nombre, precio, disponible, descripcion
 * 
 * CONCEPTOS APLICADOS:
 * - Parámetros de función: $productos es la información que "entra" a la función
 * - Foreach: estructura que recorre cada elemento del array uno por uno
 * - Operador ternario: forma compacta de escribir if-else en una línea
 * - Interpolación: insertar variables dentro de strings
 * - Funciones de string: ucfirst() convierte primera letra en mayúscula
 * - Formateo numérico: number_format() formatea números con decimales
 * 
 * FLUJO DE EJECUCIÓN:
 * 1. Se inicia la construcción del HTML de la tabla
 * 2. Se crea el encabezado con los nombres de las columnas
 * 3. Se recorre cada producto del array
 * 4. Para cada producto se genera una fila <tr> con sus datos
 * 5. Se aplica formato condicional (rojo si está agotado)
 * 6. Se cierra la tabla y se retorna todo el HTML generado
 */
function generarTablaProductos($productos) {
    // -----------------------------------------------------------------------
    // PASO 1: Inicializar la variable que contendrá todo el HTML
    // -----------------------------------------------------------------------
    // Usamos una variable string que iremos "acumulando" con el operador .=
    // Esto es más eficiente que hacer múltiples "echo" y nos permite
    // retornar todo el HTML de una vez para que quien llame a la función
    // decida dónde y cuándo mostrarlo.
    
    $html = ''; // String vacío que iremos llenando
    
    // -----------------------------------------------------------------------
    // PASO 2: Construir el inicio de la tabla con clases de Bootstrap
    // -----------------------------------------------------------------------
    // Bootstrap proporciona clases CSS para tablas bonitas y responsivas:
    // - "table": estilo base de tabla
    // - "table-striped": alterna colores en las filas (zebra)
    // - "table-hover": efecto hover al pasar el ratón
    // - "table-bordered": añade bordes a las celdas
    
    $html .= '<table class="table table-striped table-hover table-bordered">';
    
    // -----------------------------------------------------------------------
    // PASO 3: Crear el encabezado de la tabla (<thead>)
    // -----------------------------------------------------------------------
    // El thead contiene los nombres de las columnas.
    // Usamos <th> (table header) en lugar de <td> (table data) para indicar
    // que son encabezados, lo que tiene beneficios semánticos y de accesibilidad.
    
    $html .= '<thead class="table-dark">';  // table-dark: fondo oscuro para el header
    $html .= '<tr>';  // <tr> = table row (fila)
    $html .= '<th>Producto</th>';           // Columna 1: nombre del producto
    $html .= '<th>Precio</th>';             // Columna 2: precio
    $html .= '<th>Disponibilidad</th>';     // Columna 3: stock
    $html .= '<th>Descripción</th>';        // Columna 4: descripción adicional
    $html .= '</tr>';
    $html .= '</thead>';
    
    // -----------------------------------------------------------------------
    // PASO 4: Crear el cuerpo de la tabla (<tbody>)
    // -----------------------------------------------------------------------
    // Aquí es donde irán todas las filas con los datos de los productos.
    
    $html .= '<tbody>';
    
    // -----------------------------------------------------------------------
    // PASO 5: Recorrer el array de productos con FOREACH
    // -----------------------------------------------------------------------
    // CONCEPTO CLAVE - FOREACH:
    // foreach es una estructura de control que recorre arrays.
    // Sintaxis: foreach ($array as $elemento)
    // En cada iteración, $elemento toma el valor del siguiente item del array.
    //
    // En nuestro caso:
    // - $productos es el array completo que contiene todos los productos
    // - $producto (singular) es la variable temporal que en cada vuelta del bucle
    //   contendrá UN producto específico (con sus keys: nombre, precio, etc.)
    //
    // ¿Por qué es útil?
    // Porque no sabemos cuántos productos hay. Puede haber 5, 10 o 100.
    // El foreach automáticamente se encarga de recorrerlos todos sin que
    // tengamos que usar contadores o índices manuales.
    
    foreach ($productos as $producto) {
        // En este punto, $producto contiene un array asociativo como:
        // [
        //   'nombre' => 'manzana',
        //   'precio' => 0.75,
        //   'disponible' => true,
        //   'descripcion' => 'Manzana roja, paquete 1kg'
        // ]
        
        // -------------------------------------------------------------------
        // PASO 5.1: Determinar si la fila debe ser roja (producto agotado)
        // -------------------------------------------------------------------
        // CONCEPTO: Operador ternario (condición ? si_verdadero : si_falso)
        // Es una forma compacta de escribir un if-else en una sola línea.
        //
        // Equivalente en if-else tradicional:
        // if (!$producto['disponible']) {
        //     $claseFilaRoja = 'table-danger';
        // } else {
        //     $claseFilaRoja = '';
        // }
        //
        // El operador ternario hace lo mismo pero es más conciso.
        // Si el producto NO está disponible, asignamos la clase 'table-danger'
        // (clase de Bootstrap que pinta la fila de rojo).
        
        $claseFilaRoja = !$producto['disponible'] ? 'table-danger' : '';
        
        // -------------------------------------------------------------------
        // PASO 5.2: Formatear el nombre del producto
        // -------------------------------------------------------------------
        // FUNCIÓN ucfirst(): convierte el primer carácter de un string en mayúscula
        // 
        // ¿Por qué usar ucfirst()?
        // En el array de productos guardamos los nombres en minúscula ('manzana')
        // pero queremos mostrarlos con mayúscula inicial ('Manzana') en la tabla.
        // Esto demuestra cómo podemos almacenar datos en un formato y mostrarlos
        // en otro diferente.
        
        $nombreFormateado = ucfirst($producto['nombre']);
        
        // -------------------------------------------------------------------
        // PASO 5.3: Formatear el precio
        // -------------------------------------------------------------------
        // FUNCIÓN number_format(): da formato a números con decimales
        // Sintaxis: number_format(número, decimales, separador_decimal, separador_miles)
        //
        // En España usamos:
        // - Coma (,) para separar decimales: 1,50
        // - Punto (.) para separar miles: 1.000
        //
        // Ejemplos:
        // 0.75  → "0,75 €"
        // 1.2   → "1,20 €"  (añade el cero para tener 2 decimales)
        // 1000  → "1.000,00 €"
        
        $precioFormateado = number_format(
            $producto['precio'],  // El número a formatear
            2,                    // Queremos siempre 2 decimales
            ',',                  // Separador de decimales (coma en España)
            '.'                   // Separador de miles (punto en España)
        ) . ' €';                 // Añadimos el símbolo del euro
        
        // -------------------------------------------------------------------
        // PASO 5.4: Determinar el texto de disponibilidad
        // -------------------------------------------------------------------
        // Otro uso del OPERADOR TERNARIO:
        // Si $producto['disponible'] es true, mostramos "✅ En stock"
        // Si es false, mostramos "❌ Agotado"
        //
        // Esto es más legible para el usuario que mostrar "true/false"
        
        $textoDisponibilidad = $producto['disponible'] 
            ? '✅ En stock'   // Si es true
            : '❌ Agotado';   // Si es false
        
        // -------------------------------------------------------------------
        // PASO 5.5: Obtener la descripción
        // -------------------------------------------------------------------
        // Simplemente guardamos la descripción en una variable para usarla
        // en el HTML de la tabla.
        
        $descripcion = $producto['descripcion'];
        
        // -------------------------------------------------------------------
        // PASO 5.6: Construir la fila <tr> con todas las celdas <td>
        // -------------------------------------------------------------------
        // Ahora que tenemos todos los datos formateados,
        // construimos el HTML de la fila.
        //
        // NOTA sobre la sintaxis:
        // Usamos .= para ir "acumulando" strings en la variable $html
        // El operador .= es equivalente a: $html = $html . 'nuevo texto'
        
        $html .= '<tr class="' . $claseFilaRoja . '">';  // Aplicamos clase roja si está agotado
        $html .= '<td>' . $nombreFormateado . '</td>';
        $html .= '<td>' . $precioFormateado . '</td>';
        $html .= '<td>' . $textoDisponibilidad . '</td>';
        $html .= '<td>' . $descripcion . '</td>';
        $html .= '</tr>';
        
    } // Fin del foreach - vuelve al inicio para el siguiente producto
    
    // -----------------------------------------------------------------------
    // PASO 6: Cerrar el tbody y la tabla
    // -----------------------------------------------------------------------
    
    $html .= '</tbody>';
    $html .= '</table>';
    
    // -----------------------------------------------------------------------
    // PASO 7: Retornar el HTML completo
    // -----------------------------------------------------------------------
    // La palabra clave "return" devuelve el valor al código que llamó a la función.
    // En index.php haremos: echo generarTablaProductos($productos);
    // y eso mostrará toda la tabla HTML que hemos construido aquí.
    
    return $html;
}

/**
 * FUNCIÓN 2: muestraInfoContacto
 * 
 * PROPÓSITO:
 * Genera un bloque HTML con la información de contacto del usuario
 * (nombre, teléfono y foto de perfil) dentro de un modal de Bootstrap.
 * 
 * @param string $nombre   - Nombre del usuario
 * @param string $telefono - Teléfono del usuario
 * @param string $foto     - URL de la foto de perfil
 * 
 * CONCEPTOS APLICADOS:
 * - Validación de datos con isset() y empty()
 * - Alternancia entre modo PHP y modo HTML
 * - Sintaxis corta de echo: <?= variable ?>
 * 
 * FLUJO DE EJECUCIÓN:
 * 1. Validar que los parámetros tengan valores válidos
 * 2. Preparar los datos para mostrar
 * 3. Salir del modo PHP y escribir HTML limpio
 * 4. Insertar variables PHP en el HTML usando <?= ?>
 * 5. Volver al modo PHP al finalizar
 */
function muestraInfoContacto($nombre, $telefono, $foto) {
    // -----------------------------------------------------------------------
    // PASO 1: Validar los datos recibidos
    // -----------------------------------------------------------------------
    // CONCEPTO: Programación defensiva
    // Nunca debemos asumir que los datos que recibimos son correctos.
    // Siempre validamos antes de usarlos.
    //
    // isset(): verifica si una variable está definida y no es null
    // empty(): verifica si una variable está vacía (null, "", 0, false, [])
    //
    // Si algún dato crítico falta, mostramos un mensaje de error y salimos.
    
    if (!isset($nombre) || empty(trim($nombre))) {
        echo '<div class="alert alert-warning">No se proporcionó nombre de contacto.</div>';
        return;  // Salimos de la función sin continuar
    }
    
    // -----------------------------------------------------------------------
    // PASO 2: Limpiar los datos
    // -----------------------------------------------------------------------
    // CONCEPTO: Limpieza básica de datos
    // 
    // trim(): elimina espacios en blanco al inicio y final del string
    // ¿Por qué? Porque el usuario podría haber escrito "  Juan  " con espacios
    // extra que no queremos mostrar.
    
    $nombre = trim($nombre);
    $telefono = trim($telefono);
    
    // -----------------------------------------------------------------------
    // PASO 3: Validar la foto
    // -----------------------------------------------------------------------
    // Si no proporcionaron una foto o está vacía, usamos una imagen
    // placeholder genérica de un servicio público que genera avatares.
    
    if (!isset($foto) || empty($foto)) {
        // ui-avatars.com genera imágenes con las iniciales del nombre
        // Es útil para tener siempre una imagen, aunque el usuario no suba una
        $foto = 'https://ui-avatars.com/api/?name=' . urlencode($nombre);
    }
    
    // -----------------------------------------------------------------------
    // PASO 4: Construir el HTML de la información de contacto
    // -----------------------------------------------------------------------
    // CONCEPTO CLAVE: Alternar entre modo PHP y modo HTML
    //
    // ¿Por qué hacer esto?
    // Cuando tenemos bloques grandes de HTML con pocas variables PHP,
    // es mucho más cómodo y legible escribir el HTML directamente
    // en lugar de usar echo con comillas y concatenaciones.
    //
    // VENTAJAS de esta técnica:
    // 1. El HTML se ve como HTML real (con coloreado de sintaxis correcto)
    // 2. No hay que escapar comillas dentro del HTML
    // 3. No hay que concatenar con puntos (.)
    // 4. Es más fácil de mantener y editar
    // 5. Los editores de código pueden formatear el HTML automáticamente

    ?>
    <!-- Salimos del modo PHP para escribir HTML -->

    
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                
                <!-- Foto de perfil -->
                <!-- Aquí usamos <?= $foto ?> para insertar la URL -->
                
                <img src="<?= $foto ?>" 
                     alt="Foto de perfil" 
                     class="rounded-circle me-3" 
                     style="width:80px;height:80px;object-fit:cover;">
                
                <!-- Información textual -->
                <div>
                    <!-- Insertamos el nombre con <?= $nombre ?> -->
                    <h5 class="card-title mb-1"><?= $nombre ?></h5>
                    
                    <p class="card-text mb-0">
                        <!-- Insertamos el teléfono con <?= $telefono ?> -->
                        <strong>📞 Teléfono:</strong> <?= $telefono ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    // -----------------------------------------------------------------------
    // PASO 5: Volver al modo PHP
    // -----------------------------------------------------------------------
    // Con <?php volvemos al modo PHP.
    // Esto no es estrictamente necesario si no hay más código PHP después,
    // pero es una buena práctica cerrar el bloque PHP cuando trabajamos
    // en archivos que contienen múltiples funciones.
    
    // -----------------------------------------------------------------------
    // NOTA IMPORTANTE sobre echo vs return:
    // -----------------------------------------------------------------------
    // Esta función NO usa return, sino que muestra el contenido directamente.
    //
    // Diferencias entre las dos funciones de este archivo:
    //
    // generarTablaProductos():
    //   - Construye HTML en una variable con concatenación
    //   - Usa RETURN para devolver el HTML
    //   - Quien la llama decide cuándo mostrar el resultado con echo
    //   - Ejemplo: echo generarTablaProductos($productos);
    //
    // muestraInfoContacto():
    //   - Sale del modo PHP y escribe HTML directamente
    //   - El HTML se muestra inmediatamente (sin return)
    //   - Quien la llama solo hace: muestraInfoContacto($a, $b, $c);
    //
    // Ambos enfoques son válidos y se usan en diferentes situaciones:
    // - Usa RETURN cuando quieras más control sobre cuándo mostrar el resultado
    // - Usa ECHO directo (o salir de PHP) cuando quieras mostrar inmediatamente
    //
    // En esta práctica usamos ambos métodos para que aprendas los dos.
}

// ============================================================================
// FIN DEL ARCHIVO funciones.php
// ============================================================================
//
// RESUMEN DE CONCEPTOS APLICADOS:
// ✓ Funciones con parámetros
// ✓ Validación básica de datos con isset() y empty()
// ✓ Bucle foreach para recorrer arrays
// ✓ Operador ternario para condicionales simples
// ✓ Funciones de string: ucfirst(), trim()
// ✓ Formateo de números: number_format()
// ✓ Diferencia entre echo y return
// ✓ Concatenación de strings con punto (.)
//
// CONEXIÓN CON EL RESTO DE LA PRÁCTICA:
// - Este archivo será incluido en index.php con require_once
// - Las funciones aquí definidas se llamarán después de recibir los datos
//   del formulario y cargar el array de productos
// - Esto demuestra el principio de "separación de responsabilidades":
//   cada archivo tiene un propósito claro y específico
//
// TÉCNICAS DE GENERACIÓN DE HTML APRENDIDAS:
// 1. Concatenación con variables: $html .= '<tag>' . $variable . '</tag>';

// ============================================================================