<?php 

$alumno = [
    'nombre' => 'Juan',
    'apellido' => 'Pérez',
    'edad' => 21,
    'curso' => 'DAW2',
    'inteligente' => TRUE,
];

//echo $alumno;
//var_dump($alumno);

//print_r($alumno);

echo $alumno['nombre'];
echo $alumno['edad'];
echo $alumno['inteligente']; 


//añadir un elemento
$alumno['email'] = 'juan.perez@example.com';
echo '<br><br>';
print_r($alumno);

//recorrer con foreach

foreach($alumno as $clave => $valor){
    echo '<h1>$clave: $valor</h1>';
}

?>
