<?php
/**
 * ========================================
 * ARCHIVO: admin/deleteUser.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Eliminar usuario
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

// Obtener ID del usuario
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id <= 0) {
    header('Location: usuarios.php');
    exit();
}

// No permitir que el admin se elimine a sí mismo
if ($user_id == $_SESSION['user_id']) {
    header('Location: usuarios.php?error=self_delete');
    exit();
}

$mysqli = getDBConnection();

// Obtener datos del usuario antes de eliminar
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

if (!$usuario) {
    header('Location: usuarios.php');
    exit();
}

// Eliminar usuario
$stmt = $mysqli->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // Eliminar avatar si no es el default
    if (!empty($usuario['foto']) && $usuario['foto'] != 'uploads/avatars/default-avatar.jpg') {
        eliminar_imagen('../' . $usuario['foto']);
    }
    
    $stmt->close();
    $mysqli->close();
    
    header('Location: usuarios.php?success=deleted');
    exit();
} else {
    $stmt->close();
    $mysqli->close();
    
    header('Location: usuarios.php?error=delete_failed');
    exit();
}
?>