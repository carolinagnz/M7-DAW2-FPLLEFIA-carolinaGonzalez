<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int) $_GET['id'];

// Eliminar nota
$stmt = $mysqli->prepare("DELETE FROM notas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: adminNotas.php");
exit;
?>