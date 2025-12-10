<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int) $_GET['id'];

// Obtener datos del módulo para eliminar la foto
$stmt = $mysqli->prepare("SELECT foto FROM modulos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$modulo = $result->fetch_assoc();

if ($modulo) {
    // Eliminar foto si existe
    if ($modulo['foto'] && file_exists('../' . $modulo['foto'])) {
        unlink('../' . $modulo['foto']);
    }
    
    // Eliminar módulo (las notas se eliminarán automáticamente por CASCADE)
    $stmt = $mysqli->prepare("DELETE FROM modulos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: adminModulos.php");
exit;
?>