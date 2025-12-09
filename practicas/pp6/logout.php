<?php
/**
 * ========================================
 * ARCHIVO: logout.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * 
 * Cerrar sesión del usuario
 */

require_once 'includes/config.php';
require_once 'includes/functions.php';

// Iniciar sesión para poder destruirla
iniciar_sesion_segura();

// Cerrar la sesión
cerrar_sesion();

// Redirigir al inicio con mensaje
header('Location: index.php?logout=1');
exit();
?>