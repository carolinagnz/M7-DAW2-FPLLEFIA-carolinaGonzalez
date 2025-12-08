<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo 7 - Práctica 1</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #f2f2f2;
            border-bottom: 2px solid #ccc;
        }

        header img {
            height: 80px;
            margin-right: 20px;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
            gap: 40px;
        }

        .columna {
            flex: 1;
            max-width: 400px;
        }

        .foto-rodonda {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto 10px auto;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #f2f2f2;
            border-top: 2px solid #ccc;
            margin-top: 50px;
        }

        h1, h3 {
            margin: 0;
        }

        p {
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <img src="https://www.alianzafpdual.es/wp-content/uploads/2022/01/LLefia_logo.jpg" alt="Logo FP Llefià">
        <h1>Módulo 7 - Práctica 1. Mi primera aplicación en PHP</h1>
    </header>

    <!-- Contenido principal con dos columnas -->
    <main>
        <div class="columna" style="text-align:center;">
            <img src="https://lh3.googleusercontent.com/a/ACg8ocLHeG-55DG3qMXfAngf_cMoBCVgcWY-MzNZhdIpwWiCzNaUYmU=s288-c-no" alt="Mi foto" class="foto-rodonda">
            <h3>Carolina Gonzalez</h3>
        </div>
        <div class="columna">
            <p>
                Este párrafo explica el código de las áreas señaladas del archivo <code>index.php</code> que tenemos por defecto. 
                Por ejemplo, la sección del header define la navegación principal, 
                las columnas se organizan mediante CSS puro usando flexbox y estilos personalizados, 
                y el footer muestra la fecha actual utilizando PHP para generarla automáticamente.
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>Carolina Gonzalez</p>
        <p>
            <?php
                // Muestra la fecha actual en formato 2024-12-09
                echo date('Y-m-d');
            ?>
        </p>
    </footer>
</body>
</html>
