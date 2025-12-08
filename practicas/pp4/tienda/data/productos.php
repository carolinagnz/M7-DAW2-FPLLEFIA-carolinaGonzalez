<?php

// Este archivo define el conjunto de productos que mostrará la tienda.

// Cada producto será un elemento de ese array, con varios campos:
//   - nombre
//   - precio
//   - disponible (true/false)
//   - descripcion
//
// Este archivo se incluirá (require_once) en index.php,
// justo antes de llamar a la función generarTablaProductos().
// Así, cuando esa función recorra el array, tendrá acceso a los datos.
// -------------------------------------------------------------

// Definimos el array principal llamado $productos.
// En PHP, los arrays pueden ser de tipo “asociativo”: en lugar de usar índices numéricos,
// usamos claves con nombres descriptivos ('nombre', 'precio').
$productos = [

    // ---------------------------------------------------------
    // Producto 1
    // ---------------------------------------------------------
    [
        // 'nombre' guarda el nombre del producto.
        // Lo escribimos en minúscula porque luego, en la función generarTablaProductos(),
        // se usará ucfirst() para poner la primera letra en mayúscula.
        // Esto muestra cómo una función posterior aprovechará este formato inicial.
        'nombre' => 'manzana',

        // 'precio' se define como número (tipo float) y no como texto.
        // Esto es importante porque luego lo formatearemos con number_format()
        // dentro de la tabla de productos.
        'precio' => 0.75,

        // 'disponible' indica si el producto está en stock.
        // Usamos un valor booleano true/false, no texto.
        // Posteriormente, en generarTablaProductos(), usaremos un operador ternario
        // para mostrar “En stock” o “Agotado” en función de este valor.
        'disponible' => true,

        // 'descripcion' da información adicional del producto.
        // Este campo fue agregado como tarea adicional para ampliar la práctica.
        // Se mostrará en una columna extra de la tabla, ayudando a ver cómo se manejan cadenas.
        'descripcion' => 'Manzana roja, paquete 1kg'
    ],

    [
        'nombre' => 'pan',
        'precio' => 1.20,
        'disponible' => true,
        'descripcion' => 'Pan integral artesanal 500g'
    ],

    [
        'nombre' => 'leche',
        'precio' => 0.95,
        // En este caso marcamos “false” para simular un producto agotado.
        // Esto nos servirá para probar el formato condicional:
        // en la tabla, esa fila se mostrará en color rojo.
        'disponible' => false,
        'descripcion' => 'Leche semi-desnatada 1L'
    ],

    [
        'nombre' => 'cafe',
        'precio' => 3.50,
        'disponible' => true,
        'descripcion' => 'Café molido 250g'
    ],

    [
        'nombre' => 'huevos',
        'precio' => 2.30,
        'disponible' => false,
        'descripcion' => 'Docena de huevos tamaño M'
    ],
];

//
//  Cómo se conecta con lo que ya hicimos:
//    - En index.php, después de recibir los datos del formulario, incluimos este archivo con:
//         require_once __DIR__ . '/data/productos.php';
//      Eso carga este array en memoria y deja disponible la variable $productos.
//    - Luego, la función generarTablaProductos($productos) (definida en includes/funciones.php)
//      recorrerá este array con un bucle foreach para generar una tabla HTML.
//
//  Cómo se usará después:
//    - En la tabla, la función utilizará ucfirst() sobre 'nombre' para capitalizarlo,
//      y number_format() sobre 'precio' para mostrarlo con dos decimales.
//    - También usará un operador ternario sobre 'disponible' para decidir el texto “En stock” o “Agotado”,
//      aplicando un color rojo a la fila cuando esté agotado.
//    - La 'descripcion' se mostrará como una columna extra, cumpliendo la tarea adicional.
//
//  Orden lógico en la práctica completa:
//    inicio.php  → recoge datos del usuario
//    index.php   → recibe datos, carga productos y funciones
//    productos.php → proporciona los datos
//    funciones.php → transforma los datos en HTML (tabla y contacto)
//    header/footer → dan estructura visual consistente
//

