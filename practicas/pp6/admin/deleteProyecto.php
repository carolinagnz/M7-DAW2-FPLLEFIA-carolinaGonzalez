<?php
/**
 * ========================================
 * ARCHIVO: admin/deleteProyecto.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Eliminar proyecto
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

// Obtener ID del proyecto
$proyecto_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($proyecto_id <= 0) {
    header('Location: proyectos.php');
    exit();
}

$mysqli = getDBConnection();

// Obtener datos del proyecto antes de eliminar
$stmt = $mysqli->prepare("SELECT * FROM proyectos WHERE id = ?");
$stmt->bind_param("i", $proyecto_id);
$stmt->execute();
$result = $stmt->get_result();
$proyecto = $result->fetch_assoc();
$stmt->close();

if (!$proyecto) {
    header('Location: proyectos.php');
    exit();
}

// Eliminar proyecto
$stmt = $mysqli->prepare("DELETE FROM proyectos WHERE id = ?");
$stmt->bind_param("i", $proyecto_id);

if ($stmt->execute()) {
    // Eliminar imagen si existe
    if (!empty($proyecto['imagen_principal'])) {
        eliminar_imagen('../' . $proyecto['imagen_principal']);
    }
    
    $stmt->close();
    $mysqli->close();
    
    header('Location: proyectos.php?success=deleted');
    exit();
} else {
    $stmt->close();
    $mysqli->close();
    
    header('Location: proyectos.php?error=delete_failed');
    exit();
}
?>