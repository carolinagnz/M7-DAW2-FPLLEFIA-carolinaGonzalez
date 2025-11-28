<?php
/**
 * Configuración - TechSolutions Pro
 */

// CREDENCIALES DE ALWAYSDATA
define('DB_HOST', 'mysql-carolinagnz.alwaysdata.net');
define('DB_NAME', 'carolinagnz_techsolutions_db');
define('DB_USER', 'carolinagnz');  // Tu usuario de Alwaysdata
define('DB_PASS', 'TU_CONTRASEÑA_AQUÍ');  // ← PON TU CONTRASEÑA REAL
define('DB_CHARSET', 'utf8mb4');

function getDBConnection() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($mysqli->connect_errno) {
        if (DEBUG_MODE) {
            die("Error de conexión (" . $mysqli->connect_errno . "): " . $mysqli->connect_error);
        } else {
            die("Error de conexión a la base de datos.");
        }
    }
    
    if (!$mysqli->set_charset(DB_CHARSET)) {
        die("Error al establecer charset: " . $mysqli->error);
    }
    
    return $mysqli;
}

function closeDBConnection($mysqli) {
    if ($mysqli) {
        $mysqli->close();
    }
}

define('SITE_NAME', 'TechSolutions Pro');
define('SITE_SLOGAN', 'Transformamos ideas en soluciones digitales');
define('SITE_EMAIL', 'info@techsolutions.com');
define('SITE_PHONE', '+34 123 456 789');
define('SITE_ADDRESS', 'Barcelona, España');

define('COLOR_PRIMARY', '#2C3E50');
define('COLOR_SECONDARY', '#E67E22');

define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5242880);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);  // HTTPS en Alwaysdata
ini_set('session.cookie_samesite', 'Lax');

define('SESSION_TIMEOUT', 1800);
define('ENVIRONMENT', 'production');
define('DEBUG_MODE', false);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

function dd($data) {
    if (DEBUG_MODE) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }
}

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Acceso directo no permitido');
}
?>