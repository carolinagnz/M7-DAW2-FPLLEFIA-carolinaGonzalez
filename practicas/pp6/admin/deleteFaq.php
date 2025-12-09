<?php
/**
 * ========================================
 * ARCHIVO: admin/deleteFaq.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Eliminar FAQ
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

// Obtener ID de la FAQ
$faq_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($faq_id <= 0) {
    header('Location: faqs.php');
    exit();
}

$mysqli = getDBConnection();

// Eliminar FAQ
$stmt = $mysqli->prepare("DELETE FROM faqs WHERE id = ?");
$stmt->bind_param("i", $faq_id);

if ($stmt->execute()) {
    $stmt->close();
    $mysqli->close();
    
    header('Location: faqs.php?success=deleted');
    exit();
} else {
    $stmt->close();
    $mysqli->close();
    
    header('Location: faqs.php?error=delete_failed');
    exit();
}
?>