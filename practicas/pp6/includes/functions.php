<?php
/**
 * ========================================
 * FUNCIONES ÚTILES - TechSolutions Pro
 * ========================================
 */

// ====================================
// FUNCIONES DE SEGURIDAD
// ====================================

function limpiar_input($data) {
    global $mysqli;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    if (isset($mysqli)) {
        $data = $mysqli->real_escape_string($data);
    }
    return $data;
}

function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validar_longitud($texto, $min, $max) {
    return (strlen($texto) >= $min && strlen($texto) <= $max);
}

// ====================================
// FUNCIONES DE SESIÓN
// ====================================

function iniciar_sesion_segura() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 300) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
    
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        cerrar_sesion();
        header('Location: login.php?timeout=1');
        exit();
    }
    $_SESSION['last_activity'] = time();
}

function esta_logueado() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function es_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function cerrar_sesion() {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();
}

// ====================================
// FUNCIONES DE FORMATO
// ====================================

function formatear_fecha($fecha, $formato = 'medio') {
    $timestamp = strtotime($fecha);
    $meses = array(1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    
    if ($formato == 'corto') {
        return date('d/m/Y', $timestamp);
    }
    
    $dia = date('d', $timestamp);
    $mes = $meses[date('n', $timestamp)];
    $anio = date('Y', $timestamp);
    return $dia . ' de ' . $mes . ' de ' . $anio;
}

function recortar_texto($texto, $longitud = 100) {
    if (strlen($texto) <= $longitud) return $texto;
    return substr($texto, 0, $longitud) . '...';
}

// ====================================
// FUNCIONES DE ARCHIVOS
// ====================================

function subir_imagen($file, $carpeta = 'general') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error al subir el archivo'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Archivo demasiado grande (máx 5MB)'];
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime, ALLOWED_IMAGE_TYPES)) {
        return ['success' => false, 'message' => 'Tipo de archivo no permitido'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nombre_archivo = uniqid() . '_' . time() . '.' . $extension;
    
    $ruta_carpeta = UPLOAD_DIR . $carpeta . '/';
    if (!file_exists($ruta_carpeta)) {
        mkdir($ruta_carpeta, 0755, true);
    }
    
    $ruta_completa = $ruta_carpeta . $nombre_archivo;
    if (move_uploaded_file($file['tmp_name'], $ruta_completa)) {
        return ['success' => true, 'path' => 'uploads/' . $carpeta . '/' . $nombre_archivo];
    }
    
    return ['success' => false, 'message' => 'Error al mover el archivo'];
}

function subir_avatar($file) {
    return subir_imagen($file, 'avatars');
}
?>