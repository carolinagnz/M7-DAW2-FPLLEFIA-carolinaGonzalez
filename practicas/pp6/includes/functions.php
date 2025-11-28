<?php
/**
 * Funciones Auxiliares
 * TechSolutions Pro - PP6
 */

// ====================================
// FUNCIONES DE SEGURIDAD Y VALIDACIÓN
// ====================================

/**
 * Sanitizar y limpiar datos de entrada
 * Elimina espacios, barras y convierte caracteres especiales
 */
function limpiar_input($data) {
    if (is_array($data)) {
        return array_map('limpiar_input', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validar formato de email
 */
function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validar longitud de string
 */
function validar_longitud($string, $min, $max) {
    $length = strlen($string);
    return $length >= $min && $length <= $max;
}

/**
 * Generar token CSRF para formularios
 */
function generar_csrf_token() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verificar token CSRF
 */
function verificar_csrf_token($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ====================================
// FUNCIONES DE SESIÓN Y AUTENTICACIÓN
// ====================================

/**
 * Iniciar sesión de manera segura
 */
function iniciar_sesion_segura() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Regenerar ID de sesión periódicamente
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 300) { // Cada 5 minutos
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
    
    // Verificar timeout de sesión
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        header('Location: login.php?timeout=1');
        exit();
    }
    $_SESSION['last_activity'] = time();
}

/**
 * Verificar si el usuario está logueado
 */
function esta_logueado() {
    iniciar_sesion_segura();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Verificar si el usuario es administrador
 */
function es_admin() {
    return esta_logueado() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Redirigir si NO está logueado
 */
function requerir_login($redirect = '../login.php') {
    if (!esta_logueado()) {
        header('Location: ' . $redirect);
        exit();
    }
}

/**
 * Redirigir si NO es administrador
 */
function requerir_admin($redirect = '../index.php') {
    requerir_login();
    if (!es_admin()) {
        header('Location: ' . $redirect);
        exit();
    }
}

/**
 * Cerrar sesión de manera segura
 */
function cerrar_sesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

// ====================================
// FUNCIONES DE FORMATO Y PRESENTACIÓN
// ====================================

/**
 * Formatear fecha en español
 */
function formatear_fecha($fecha, $formato = 'completo') {
    $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
    
    $meses = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
    ];
    
    switch ($formato) {
        case 'corto':
            // Ejemplo: 15/11/2024
            return date('d/m/Y', $timestamp);
            
        case 'medio':
            // Ejemplo: 15 Nov 2024
            return date('d', $timestamp) . ' ' . substr($meses[date('n', $timestamp)], 0, 3) . ' ' . date('Y', $timestamp);
            
        case 'completo':
        default:
            // Ejemplo: 15 de noviembre de 2024
            return date('d', $timestamp) . ' de ' . $meses[date('n', $timestamp)] . ' de ' . date('Y', $timestamp);
    }
}

/**
 * Formatear fecha y hora
 */
function formatear_fecha_hora($fecha) {
    $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
    return formatear_fecha($timestamp, 'completo') . ' a las ' . date('H:i', $timestamp);
}

/**
 * Calcular tiempo transcurrido (Ej: hace 2 horas)
 */
function tiempo_transcurrido($fecha) {
    $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
    $diferencia = time() - $timestamp;
    
    if ($diferencia < 60) {
        return 'hace unos segundos';
    } elseif ($diferencia < 3600) {
        $minutos = floor($diferencia / 60);
        return 'hace ' . $minutos . ($minutos == 1 ? ' minuto' : ' minutos');
    } elseif ($diferencia < 86400) {
        $horas = floor($diferencia / 3600);
        return 'hace ' . $horas . ($horas == 1 ? ' hora' : ' horas');
    } elseif ($diferencia < 604800) {
        $dias = floor($diferencia / 86400);
        return 'hace ' . $dias . ($dias == 1 ? ' día' : ' días');
    } else {
        return formatear_fecha($timestamp, 'medio');
    }
}

/**
 * Recortar texto a un número determinado de caracteres
 */
function recortar_texto($texto, $limite = 150, $sufijo = '...') {
    if (strlen($texto) <= $limite) {
        return $texto;
    }
    
    $texto_recortado = substr($texto, 0, $limite);
    $ultimo_espacio = strrpos($texto_recortado, ' ');
    
    if ($ultimo_espacio !== false) {
        $texto_recortado = substr($texto_recortado, 0, $ultimo_espacio);
    }
    
    return $texto_recortado . $sufijo;
}

/**
 * Formatear número con separadores de miles
 */
function formatear_numero($numero, $decimales = 0) {
    return number_format($numero, $decimales, ',', '.');
}

// ====================================
// FUNCIONES DE MANEJO DE ARCHIVOS
// ====================================

/**
 * Subir imagen con validaciones
 */
function subir_imagen($file, $carpeta_destino) {
    // Verificar si hay error en la subida
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [
            'success' => false, 
            'message' => 'Error al subir el archivo. Código de error: ' . $file['error']
        ];
    }
    
    // Verificar tamaño del archivo
    if ($file['size'] > MAX_FILE_SIZE) {
        return [
            'success' => false, 
            'message' => 'El archivo es demasiado grande. Tamaño máximo: ' . (MAX_FILE_SIZE / 1048576) . 'MB'
        ];
    }
    
    // Verificar tipo de archivo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, ALLOWED_IMAGE_TYPES)) {
        return [
            'success' => false, 
            'message' => 'Tipo de archivo no permitido. Solo se aceptan imágenes JPG, PNG, GIF y WEBP'
        ];
    }
    
    // Generar nombre único para el archivo
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nuevo_nombre = uniqid('img_', true) . '.' . strtolower($extension);
    
    // Crear carpeta de destino si no existe
    $ruta_completa = UPLOAD_DIR . $carpeta_destino;
    if (!file_exists($ruta_completa)) {
        mkdir($ruta_completa, 0755, true);
    }
    
    $ruta_archivo = $ruta_completa . '/' . $nuevo_nombre;
    
    // Mover el archivo
    if (move_uploaded_file($file['tmp_name'], $ruta_archivo)) {
        return [
            'success' => true, 
            'filename' => $nuevo_nombre,
            'path' => 'uploads/' . $carpeta_destino . '/' . $nuevo_nombre
        ];
    } else {
        return [
            'success' => false, 
            'message' => 'Error al guardar el archivo en el servidor'
        ];
    }
}

/**
 * Eliminar archivo de imagen
 */
function eliminar_imagen($ruta_archivo) {
    $ruta_completa = __DIR__ . '/../' . $ruta_archivo;
    if (file_exists($ruta_completa) && is_file($ruta_completa)) {
        return unlink($ruta_completa);
    }
    return false;
}

// ====================================
// FUNCIONES DE INTERFAZ DE USUARIO
// ====================================

/**
 * Mostrar alerta Bootstrap
 */
function mostrar_alerta($tipo, $mensaje, $dismissible = true) {
    $tipos_validos = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
    if (!in_array($tipo, $tipos_validos)) {
        $tipo = 'info';
    }
    
    $html = '<div class="alert alert-' . $tipo;
    if ($dismissible) {
        $html .= ' alert-dismissible fade show';
    }
    $html .= '" role="alert">';
    $html .= htmlspecialchars($mensaje);
    
    if ($dismissible) {
        $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        $html .= '<span aria-hidden="true">&times;</span>';
        $html .= '</button>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Generar opciones de select desde array
 */
function generar_opciones_select($opciones, $seleccionado = '') {
    $html = '';
    foreach ($opciones as $valor => $texto) {
        $selected = ($valor == $seleccionado) ? ' selected' : '';
        $html .= '<option value="' . htmlspecialchars($valor) . '"' . $selected . '>';
        $html .= htmlspecialchars($texto);
        $html .= '</option>';
    }
    return $html;
}

/**
 * Generar paginación
 */
function generar_paginacion($pagina_actual, $total_paginas, $url_base) {
    if ($total_paginas <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Paginación"><ul class="pagination justify-content-center">';
    
    // Botón anterior
    if ($pagina_actual > 1) {
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $url_base . '?pagina=' . ($pagina_actual - 1) . '">Anterior</a>';
        $html .= '</li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Anterior</span></li>';
    }
    
    // Números de página
    for ($i = 1; $i <= $total_paginas; $i++) {
        if ($i == $pagina_actual) {
            $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $url_base . '?pagina=' . $i . '">' . $i . '</a>';
            $html .= '</li>';
        }
    }
    
    // Botón siguiente
    if ($pagina_actual < $total_paginas) {
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $url_base . '?pagina=' . ($pagina_actual + 1) . '">Siguiente</a>';
        $html .= '</li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Siguiente</span></li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}

// ====================================
// FUNCIONES DE BASE DE DATOS
// ====================================

/**
 * Ejecutar consulta preparada de manera segura
 */
function ejecutar_consulta($sql, $params = []) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            die("Error en la consulta: " . $e->getMessage());
        } else {
            log_error("Error SQL: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Obtener un solo registro
 */
function obtener_uno($sql, $params = []) {
    $stmt = ejecutar_consulta($sql, $params);
    return $stmt ? $stmt->fetch() : false;
}

/**
 * Obtener múltiples registros
 */
function obtener_todos($sql, $params = []) {
    $stmt = ejecutar_consulta($sql, $params);
    return $stmt ? $stmt->fetchAll() : false;
}

// ====================================
// FUNCIONES DE URL
// ====================================

/**
 * Redireccionar a una URL
 */
function redireccionar($url, $codigo = 302) {
    header("Location: $url", true, $codigo);
    exit();
}

/**
 * Obtener URL actual
 */
function url_actual() {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $protocolo . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Generar slug desde un texto (para URLs amigables)
 */
function generar_slug($texto) {
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9\s-]/', '', $texto);
    $texto = preg_replace('/[\s-]+/', '-', $texto);
    $texto = trim($texto, '-');
    return $texto;
}
?>