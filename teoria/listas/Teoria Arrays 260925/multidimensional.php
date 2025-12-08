<?php

//array multidimensional

$alumnos = [
    [
        'nombre' => 'Juan',
        'apellido' => 'Pérez',
        'edad' => 21,
        'curso' => 'DAW2',
        'inteligente' => TRUE,
    ],
    [
        'nombre' => 'Juan',
        'apellido' => 'Pérez',
        'edad' => 21,
        'curso' => 'DAW2',
        'inteligente' => TRUE,
    ],
    [
        'nombre' => 'Juan',
        'apellido' => 'Pérez',
        'edad' => 21,
        'curso' => 'DAW2',
        'inteligente' => TRUE,
    ],
];

//echo $alumno;
//var_dump($alumno);

//print_r($alumno);

foreach($alumnos as $alumno){
    echo "<h1>{$alumno['nombre']} {$alumno['apellido']}</h1>";
}

?>