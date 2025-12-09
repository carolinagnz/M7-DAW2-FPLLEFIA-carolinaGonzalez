<?php
/**
 * ========================================
 * ARCHIVO: admin/deleteNoticia.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Eliminar noticia
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

// Obtener ID de la noticia
$noticia_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($noticia_id <= 0) {
    header('Location: noticias.php');
    exit();
}

$mysqli = getDBConnection();

// Obtener datos de la noticia antes de eliminar
$stmt = $mysqli->prepare("SELECT * FROM noticias WHERE id = ?");
$stmt->bind_param("i", $noticia_id);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();
$stmt->close();

if (!$noticia) {
    header('Location: noticias.php');
    exit();
}

// Eliminar noticia
$stmt = $mysqli->prepare("DELETE FROM noticias WHERE id = ?");
$stmt->bind_param("i", $noticia_id);

if ($stmt->execute()) {
    // Eliminar imagen si existe
    if (!empty($noticia['imagen_destacada'])) {
        eliminar_imagen('../' . $noticia['imagen_destacada']);
    }
    
    $stmt->close();
    $mysqli->close();
    
    header('Location: noticias.php?success=deleted');
    exit();
} else {
    $stmt->close();
    $mysqli->close();
    
    header('Location: noticias.php?error=delete_failed');
    exit();
}
?>