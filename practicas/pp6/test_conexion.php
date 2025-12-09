<?php
/**
 * ========================================
 * ARCHIVO DE PRUEBA - ELIMINAR DESPUÉS
 * ========================================
 */

require_once 'includes/config.php';
require_once 'includes/functions.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Conexión - TechSolutions Pro</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .header {
            background: linear-gradient(135deg, #2C3E50 0%, #34495e 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 { font-size: 36px; margin-bottom: 10px; }
        .content { padding: 40px; }
        .test-box {
            background: #f8f9fa;
            border-left: 5px solid #28a745;
            padding: 25px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .test-box.error { border-left-color: #dc3545; background: #fff5f5; }
        .test-box h3 { color: #2C3E50; margin-bottom: 15px; }
        code {
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #e83e8c;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        table td:first-child { font-weight: 600; color: #495057; width: 200px; }
        .success-banner {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            margin-top: 30px;
        }
        .success-banner h2 { font-size: 32px; margin-bottom: 10px; }
        .warning-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🧪 Test de Conexión</h1>
        <p>TechSolutions Pro - Verificación del Sistema</p>
    </div>

    <div class="content">
        <?php
        $all_passed = true;

        // TEST 1: Configuración
        echo '<div class="test-box">';
        echo '<h3>✅ Test 1: Configuración</h3>';
        echo '<table>';
        echo '<tr><td>Host:</td><td><code>' . DB_HOST . '</code></td></tr>';
        echo '<tr><td>Base de datos:</td><td><code>' . DB_NAME . '</code></td></tr>';
        echo '<tr><td>Usuario:</td><td><code>' . DB_USER . '</code></td></tr>';
        echo '<tr><td>Charset:</td><td><code>' . DB_CHARSET . '</code></td></tr>';
        echo '</table>';
        echo '</div>';

        // TEST 2: Conexión MySQL
        echo '<div class="test-box">';
        echo '<h3>🔌 Test 2: Conexión MySQL</h3>';
        try {
            $mysqli = getDBConnection();
            echo '<table>';
            echo '<tr><td>Estado:</td><td><code>✓ Conectado</code></td></tr>';
            echo '<tr><td>Versión MySQL:</td><td><code>' . $mysqli->server_info . '</code></td></tr>';
            echo '<tr><td>Charset:</td><td><code>' . $mysqli->character_set_name() . '</code></td></tr>';
            echo '</table>';
        } catch (Exception $e) {
            echo '<p style="color: #dc3545;"><strong>❌ Error:</strong> ' . $e->getMessage() . '</p>';
            $all_passed = false;
            echo '</div></div></body></html>';
            exit;
        }
        echo '</div>';

        // TEST 3: Verificar Tablas
        echo '<div class="test-box">';
        echo '<h3>🗄️ Test 3: Verificar Tablas</h3>';
        $tablas_requeridas = ['users', 'noticias', 'proyectos', 'testimonios', 'comentarios', 'faqs', 'mensajes_contacto'];
        $tablas_encontradas = [];
        
        $result = $mysqli->query("SHOW TABLES");
        while ($row = $result->fetch_array()) {
            $tablas_encontradas[] = $row[0];
        }
        
        echo '<table>';
        foreach ($tablas_requeridas as $tabla) {
            $existe = in_array($tabla, $tablas_encontradas);
            $icono = $existe ? '✓' : '✗';
            $color = $existe ? '#28a745' : '#dc3545';
            echo '<tr><td>' . $tabla . '</td><td style="color: ' . $color . ';"><code>' . $icono . ' ' . ($existe ? 'Existe' : 'NO existe') . '</code></td></tr>';
            if (!$existe) $all_passed = false;
        }
        echo '</table>';
        echo '</div>';

        // TEST 4: Verificar Datos
        echo '<div class="test-box">';
        echo '<h3>📊 Test 4: Verificar Datos</h3>';
        echo '<table>';
        
        $result = $mysqli->query("SELECT COUNT(*) as total FROM users");
        $row = $result->fetch_assoc();
        echo '<tr><td>Usuarios:</td><td><code>' . $row['total'] . ' registros</code></td></tr>';
        
        $result = $mysqli->query("SELECT COUNT(*) as total FROM noticias");
        $row = $result->fetch_assoc();
        echo '<tr><td>Noticias:</td><td><code>' . $row['total'] . ' registros</code></td></tr>';
        
        $result = $mysqli->query("SELECT COUNT(*) as total FROM proyectos");
        $row = $result->fetch_assoc();
        echo '<tr><td>Proyectos:</td><td><code>' . $row['total'] . ' registros</code></td></tr>';
        
        echo '</table>';
        echo '</div>';

        // TEST 5: Información del Sistema
        echo '<div class="test-box">';
        echo '<h3>⚙️ Test 5: Información del Sistema</h3>';
        echo '<table>';
        echo '<tr><td>Versión PHP:</td><td><code>' . phpversion() . '</code></td></tr>';
        echo '<tr><td>MySQLi:</td><td><code>' . (extension_loaded('mysqli') ? '✓ Instalada' : '✗ NO instalada') . '</code></td></tr>';
        echo '<tr><td>Entorno:</td><td><code>' . ENVIRONMENT . '</code></td></tr>';
        echo '<tr><td>Debug Mode:</td><td><code>' . (DEBUG_MODE ? 'Activo' : 'Desactivado') . '</code></td></tr>';
        echo '</table>';
        echo '</div>';

        closeDBConnection($mysqli);

        // Resultado final
        if ($all_passed) {
            echo '<div class="success-banner">';
            echo '<h2>🎉 ¡TODOS LOS TESTS PASARON!</h2>';
            echo '<p>Tu aplicación está lista p