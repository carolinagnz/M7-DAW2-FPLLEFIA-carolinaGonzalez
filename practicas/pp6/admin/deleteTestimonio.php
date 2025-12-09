<?php
/**
 * ========================================
 * ARCHIVO: admin/deleteTestimonio.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Eliminar testimonio
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

// Obtener ID del testimonio
$testimonio_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($testimonio_id <= 0) {
    header('Location: testimonios.php');
    exit();
}

$mysqli = getDBConnection();

// Obtener datos del testimonio antes de eliminar
$stmt = $mysqli->prepare("SELECT * FROM testimonios WHERE id = ?");
$stmt->bind_param("i", $testimonio_id);
$stmt->execute();
$result = $stmt->get_result();
$testimonio = $result->fetch_assoc();
$stmt->close();

if (!$testimonio) {
    header('Location: testimonios.php');
    exit();
}

// Eliminar testimonio
$stmt = $mysqli->prepare("DELETE FROM testimonios WHERE id = ?");
$stmt->bind_param("i", $testimonio_id);

if ($stmt->execute()) {
    // Eliminar foto si existe
    if (!empty($testimonio['foto_cliente'])) {
        eliminar_imagen('../' . $testimonio['foto_cliente']);
    }
    
    $stmt->close();
    $mysqli->close();
    
    header('Location: testimonios.php?success=deleted');
    exit();
} else {
    $stmt->close();
    $mysqli->close();
    
    header('Location: testimonios.php?error=delete_failed');
    exit();
}
?>