<?php
/**
 * ========================================
 * ARCHIVO DE CONFIGURACIÓN
 * TechSolutions Pro - Sistema de Gestión
 * ========================================
 * 
 * Este archivo contiene:
 * - Credenciales de la base de datos
 * - Función de conexión MySQLi
 * - Configuraciones generales del sitio
 * - Configuraciones de seguridad
 */

// ====================================
// CONFIGURACIÓN DE BASE DE DATOS
// ====================================

/**
 * Para desarrollo LOCAL (tu computadora con XAMPP/WAMP):
 * - DB_HOST: 'localhost' (la base de datos está en tu PC)
 * - DB_USER: 'root' (usuario por defecto de XAMPP/WAMP)
 * - DB_PASS: '' (sin contraseña en local)
 * - DB_NAME: nombre de tu base de datos
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'techsolutions_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Para PRODUCCIÓN (Alwaysdata u otro servidor):
 * Cuando subas tu proyecto, descomenta estas líneas
 * y comenta las de arriba
 */
/*
define('DB_HOST', 'mysql-tunombre.alwaysdata.net');
define('DB_NAME', 'tunombre_techsolutions');
define('DB_USER', 'tunombre_user');
define('DB_PASS', 'tu_contraseña_segura');
define('DB_CHARSET', 'utf8mb4');
*/

/**
 * ====================================
 * FUNCIÓN PRINCIPAL DE CONEXIÓN
 * ====================================
 * 
 * Esta función crea y retorna una conexión MySQLi
 * 
 * ¿Qué hace?
 * 1. Crea un objeto mysqli con las credenciales
 * 2. Verifica si la conexión fue exitosa
 * 3. Establece el charset (para caracteres especiales como ñ, á, etc.)
 * 4. Retorna la conexión para usarla en otros archivos
 * 
 * Uso:
 * $mysqli = getDBConnection();
 * // ahora puedes hacer consultas con $mysqli
 */
function getDBConnection() {
    // Crear la conexión
    // new mysqli(servidor, usuario, contraseña, nombre_base_datos)
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verificar si hubo error en la conexión
    if ($mysqli->connect_errno) {
        // En desarrollo, mostramos el error
        if (DEBUG_MODE) {
            die("Error de conexión (" . $mysqli->connect_errno . "): " . $mysqli->connect_error);
        } else {
            // En producción, NO mostramos detalles del error por seguridad
            die("Error de conexión a la base de datos. Contacte al administrador.");
        }
    }
    
    // Establecer el charset UTF-8 para soportar todos los caracteres
    if (!$mysqli->set_charset(DB_CHARSET)) {
        die("Error al establecer charset: " . $mysqli->error);
    }
    
    return $mysqli;
}

/**
 * Función para cerrar la conexión
 * 
 * Uso:
 * closeDBConnection($mysqli);
 */
function closeDBConnection($mysqli) {
    if ($mysqli) {
        $mysqli->close();
    }
}

// ====================================
// CONFIGURACIONES GENERALES DEL SITIO
// ====================================

/**
 * Información básica de la empresa
 * Estas constantes se usan en todo el sitio
 */
define('SITE_NAME', 'TechSolutions Pro');
define('SITE_SLOGAN', 'Transformamos ideas en soluciones digitales');
define('SITE_EMAIL', 'info@techsolutions.com');
define('SITE_PHONE', '+34 123 456 789');
define('SITE_ADDRESS', 'Barcelona, España');

/**
 * Colores corporativos
 * Los usaremos en los estilos CSS
 */
define('COLOR_PRIMARY', '#2C3E50');   // Azul oscuro
define('COLOR_SECONDARY', '#E67E22'); // Naranja

// ====================================
// CONFIGURACIÓN DE ARCHIVOS SUBIDOS
// ====================================

/**
 * UPLOAD_DIR: Carpeta donde se guardan archivos subidos
 * __DIR__ = la carpeta donde está este archivo (includes/)
 * '/../uploads/' = subir un nivel y entrar a uploads/
 */
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

/**
 * Tamaño máximo de archivos: 5MB en bytes
 * 1 MB = 1,048,576 bytes
 * 5 MB = 5,242,880 bytes
 */
define('MAX_FILE_SIZE', 5242880);

/**
 * Tipos de imagen permitidos (para validar subidas)
 */
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// ====================================
// CONFIGURACIÓN DE SEGURIDAD
// ====================================

/**
 * Configuración de sesiones seguras
 * 
 * httponly = 1: Las cookies de sesión NO son accesibles desde JavaScript
 *                (previene ataques XSS)
 * 
 * use_only_cookies = 1: Solo usar cookies para las sesiones
 *                        (NO pasar session_id por URL)
 * 
 * cookie_secure = 0: En local (HTTP)
 *                 1: En producción con HTTPS
 * 
 * cookie_samesite = 'Lax': Protección contra CSRF
 */
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS
ini_set('session.cookie_samesite', 'Lax');

/**
 * Tiempo de expiración de sesión: 30 minutos
 * 30 minutos = 1800 segundos
 * 
 * Si el usuario está inactivo más de 30 minutos,
 * se cerrará su sesión automáticamente
 */
define('SESSION_TIMEOUT', 1800);

// ====================================
// CONFIGURACIÓN DE ENTORNO
// ====================================

/**
 * ENVIRONMENT: 'development' o 'production'
 * 
 * development = modo desarrollo (en tu PC)
 * production = modo producción (en servidor real)
 */
define('ENVIRONMENT', 'development');

/**
 * DEBUG_MODE: true o false
 * 
 * Si es true: Muestra errores detallados
 * Si es false: Oculta errores (por seguridad)
 */
define('DEBUG_MODE', ENVIRONMENT === 'development');

/**
 * Configurar visualización de errores según el entorno
 */
if (DEBUG_MODE) {
    // EN DESARROLLO: Mostrar TODOS los errores
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    // EN PRODUCCIÓN: NO mostrar errores
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ====================================
// FUNCIONES DE DEBUGGING
// ====================================

/**
 * dd() = "Dump and Die"
 * 
 * Función para debugging: muestra el contenido de una variable
 * y detiene la ejecución del script
 * 
 * SOLO funciona en modo DEBUG_MODE
 * 
 * Uso:
 * $usuario = ['nombre' => 'Juan', 'email' => 'juan@test.com'];
 * dd($usuario);
 * 
 * Mostrará:
 * array(2) {
 *   ["nombre"]=> string(4) "Juan"
 *   ["email"]=> string(13) "juan@test.com"
 * }
 * Y detendrá el script
 */
function dd($data) {
    if (DEBUG_MODE) {
        echo '<pre style="background: #f4f4f4; padding: 20px; border: 3px solid #e74c3c; border-radius: 8px; margin: 20px;">';
        echo '<strong style="color: #e74c3c; font-size: 18px;">DEBUG - Variable Dump:</strong><br><br>';
        var_dump($data);
        echo '</pre>';
        die();
    }
}

/**
 * debug_log() = Guardar mensajes en un archivo de log
 * 
 * En producción, no mostramos errores al usuario,
 * pero los guardamos en un archivo para revisarlos después
 * 
 * Uso:
 * debug_log("Error al conectar a la base de datos");
 */
function debug_log($mensaje, $archivo = 'error.log') {
    if (ENVIRONMENT === 'production') {
        $fecha_hora = date('Y-m-d H:i:s');
        $log_mensaje = "[$fecha_hora] $mensaje\n";
        
        // Crear carpeta logs si no existe
        $log_dir = __DIR__ . '/../logs/';
        if (!file_exists($log_dir)) {
            mkdir($log_dir, 0755, true);
        }
        
        // Escribir en el archivo de log
        error_log($log_mensaje, 3, $log_dir . $archivo);
    }
}

/**
 * ====================================
 * FUNCIÓN DE PRUEBA DE CONEXIÓN
 * ====================================
 * 
 * Esta función te permite probar si la conexión funciona
 * 
 * Uso (solo para testing):
 * test_connection();
 */
function test_connection() {
    $mysqli = getDBConnection();
    
    echo "<div style='background: #2ecc71; color: white; padding: 20px; margin: 20px; border-radius: 8px;'>";
    echo "<h3>✓ Conexión exitosa a la base de datos</h3>";
    echo "<p><strong>Servidor:</strong> " . DB_HOST . "</p>";
    echo "<p><strong>Base de datos:</strong> " . DB_NAME . "</p>";
    echo "<p><strong>Charset:</strong> " . $mysqli->character_set_name() . "</p>";
    echo "<p><strong>Versión MySQL:</strong> " . $mysqli->server_info . "</p>";
    echo "</div>";
    
    closeDBConnection($mysqli);
}

// ====================================
// ZONA DE SEGURIDAD
// ====================================

/**
 * Prevenir acceso directo a este archivo
 * 
 * Si alguien intenta abrir config.php directamente
 * en el navegador, no mostrará nada
 */
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Acceso directo no permitido');
}

/**
 * ====================================
 * FIN DEL ARCHIVO DE CONFIGURACIÓN
 * ====================================
 */
?>