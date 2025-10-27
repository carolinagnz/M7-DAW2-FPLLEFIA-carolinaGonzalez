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
        [
            'nombre' => 'Mario',
            'descripcion' => 'Un fontanero italiano que vive en el Reino Champiñón.',
            'imagen' => 'mario.png'
        ],
        [
            'nombre' => 'Link',
            'descripcion' => 'El héroe del tiempo de la serie The Legend of Zelda.',
            'imagen' => 'link.png'
        ]
    ];

    //Ahora $_SESSION['personajes'] contiene 2 personajesPERSONAJES'] CONTIENE:
    // $_SESSION['personajes'][0] -> Primer personaje (Mario)
    // $_SESSION['personajes'][1] -> Segundo personaje (Link)   
}

//FIN INICIALIZACION DE DATOS

//FUNCION PARA AÑADIR PERSONAJE

function agregarPersonaje($nombre, $img, $poder, $descripcion){
    
}