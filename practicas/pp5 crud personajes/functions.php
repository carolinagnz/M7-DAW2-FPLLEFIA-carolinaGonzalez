<?php
session_start();

//INICIALIZACION DE DATOS
//Preguntamos si ya existe la lista de persoanajes en la sesión
//!isset() significa "NO existe"

if(!isset($_SESSION['personajes'])){
    //Si no existe, la creamos con 2 personajes de ejemplo
    //$_SESSION['personajes'] será un array de personajes
    //Cada personaje es también un array con sus propiedades

    $_SESSION['personajes'] = [
        $_SESSION['personajes'] = [
    [
        'nombre' => 'Mario',
        'descripcion' => 'Un fontanero italiano que vive en el Reino Champiñón.',
        'imagen' => 'mario.png'
    ],
    [
        'nombre' => 'Link',
        'descripcion' => 'El héroe del tiempo de la serie The Legend of Zelda.',
        'imagen' => 'link.png'
    ],
    [
        'nombre' => 'Pikachu',
        'descripcion' => 'Un Pokémon eléctrico conocido por su energía y su poderoso ataque "impactrueno".',
        'imagen' => 'pikachu.png'
    ],
    [
        'nombre' => 'Samus',
        'descripcion' => 'Cazadora espacial equipada con un traje de poder avanzado de la saga Metroid.',
        'imagen' => 'samus.png'
    ],
    [
        'nombre' => 'Donkey Kong',
        'descripcion' => 'Un gorila fuerte y valiente, protector de su isla y amante de los plátanos.',
        'imagen' => 'donkeykong.png'
    ],
    [
        'nombre' => 'Kirby',
        'descripcion' => 'Una pequeña criatura rosada con la habilidad de absorber enemigos y copiar sus poderes.',
        'imagen' => 'kirby.png'
    ],
    [
        'nombre' => 'Fox McCloud',
        'descripcion' => 'Líder del escuadrón Star Fox, experto piloto de naves espaciales.',
        'imagen' => 'fox.png'
    ],
    [
        'nombre' => 'Yoshi',
        'descripcion' => 'El dinosaurio verde amigo de Mario, conocido por su lengua larga y sus saltos.',
        'imagen' => 'yoshi.png'
    ],
    [
        'nombre' => 'Peach',
        'descripcion' => 'La princesa del Reino Champiñón, amable y valiente, a menudo en el centro de las aventuras.',
        'imagen' => 'peach.png'
    ],
    [
        'nombre' => 'Bowser',
        'descripcion' => 'El rey de los Koopas y principal rival de Mario, poderoso y temido.',
        'imagen' => 'bowser.png'
    ]
];

    ];

    //Ahora $_SESSION['personajes'] contiene 2 personajesPERSONAJES'] CONTIENE:
    // $_SESSION['personajes'][0] -> Primer personaje (Mario)
    // $_SESSION['personajes'][1] -> Segundo personaje (Link)   
}

//FIN INICIALIZACION DE DATOS

//FUNCION PARA AÑADIR PERSONAJE

function agregarPersonaje($nombre, $img, $poder, $descripcion){
    function agregarPersonaje($nombre, $descripcion, $imagen) {
    // Verificar si la sesión de personajes existe; si no, crearla
    if (!isset($_SESSION['personajes'])) {
        $_SESSION['personajes'] = [];
    }

    // Crear el nuevo personaje como un array asociativo
    $nuevoPersonaje = [
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'imagen' => $imagen
    ];

    // Agregar el nuevo personaje al final del array
    $_SESSION['personajes'][] = $nuevoPersonaje;
}

    
}