<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int) $_GET['id'];

// No permitir eliminar al propio usuario
if ($id === $_SESSION['user_id']) {
    header("Location: adminUsers.php?error=No puedes eliminarte a ti mismo");
    exit;
}

// Obtener datos del usuario para eliminar la foto
$stmt = $mysqli->prepare("SELECT foto FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    // Eliminar foto si existe
    if ($user['foto'] && file_exists('../' . $user['foto'])) {
        unlink('../' . $user['foto']);
    }
    
    // Eliminar usuario
    $stmt = $mysqli->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: adminUsers.php");
exit;
?>