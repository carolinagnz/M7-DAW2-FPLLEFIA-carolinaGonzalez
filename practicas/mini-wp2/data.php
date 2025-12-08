<?php
// Iniciamos la sesión para poder guardar datos entre páginas
// Las sesiones permiten almacenar variables que persisten mientras el navegador esté abierto
session_start();

// Comprobamos si la variable de sesión 'noticias' ya existe
if (!isset($_SESSION['noticias'])) {
    // Si no existe, creamos un array multidimensional con noticias de ejemplo
    $_SESSION['noticias'] = [
        [
            'titulo' => 'Noticia 1',
            'contenido' => 'Contenido de la noticia 1',
            'fecha' => '2024-10-01',
            'imagen' => 'https://via.placeholder.com/150',
            'categoria' => 'Tecnología'
        ],
        [
            'titulo' => 'Noticia 2',
            'contenido' => 'Contenido de la noticia 2',
            'fecha' => '2024-10-02',
            'imagen' => 'https://via.placeholder.com/150',
            'categoria' => 'Salud'
        ],
        [
            'titulo' => 'Noticia 3',
            'contenido' => 'Contenido de la noticia 3',
            'fecha' => '2024-10-03',
            'imagen' => 'https://via.placeholder.com/150',
            'categoria' => 'Deportes'
        ],
        [
            'titulo' => 'Noticia 4',
            'contenido' => 'Contenido de la noticia 4',
            'fecha' => '2024-10-04',
            'imagen' => 'https://via.placeholder.com/150',
            'categoria' => 'Cultura'
        ],
        [
            'titulo' => 'Noticia 5',
            'contenido' => 'Contenido de la noticia 5',
            'fecha' => '2024-10-05',
            'imagen' => 'https://via.placeholder.com/150',
            'categoria' => 'Economía'
        ]
    ];
}

// Fin de data.php
?>
