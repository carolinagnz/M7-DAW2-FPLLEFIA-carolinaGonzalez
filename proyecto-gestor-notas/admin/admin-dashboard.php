<?php
session_start();
require_once('../config.php');

// Control de acceso - Solo admins
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Obtener estadísticas
$totalUsers = $mysqli->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$totalModulos = $mysqli->query("SELECT COUNT(*) as total FROM modulos")->fetch_assoc()['total'];
$totalNotas = $mysqli->query("SELECT COUNT(*) as total FROM notas")->fetch_assoc()['total'];
$totalStudents = $mysqli->query("SELECT COUNT(*) as total FROM users WHERE role='student'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 24px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid white;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
        }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .menu-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .menu-card h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 22px;
        }
        
        .menu-card p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .menu-card a {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 10px 30px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .menu-card a:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>👨‍💼 Panel de Administrador</h1>
        <div class="user-info">
            <?php if ($_SESSION['user_foto']): ?>
                <img src="../<?= $_SESSION['user_foto'] ?>" alt="Foto de perfil">
            <?php else: ?>
                <img src="https://via.placeholder.com/50" alt="Sin foto">
            <?php endif; ?>
            <div>
                <strong><?= $_SESSION['user_nombre'] ?> <?= $_SESSION['user_apellidos'] ?></strong><br>
                <small><?= $_SESSION['user_email'] ?></small>
            </div>
            <a href="../logout.php" class="logout-btn">Cerrar Sesión</a>
        </div>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <h3>Total Usuarios</h3>
                <div class="number"><?= $totalUsers ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Estudiantes</h3>
                <div class="number"><?= $totalStudents ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Módulos</h3>
                <div class="number"><?= $totalModulos ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Notas Registradas</h3>
                <div class="number"><?= $totalNotas ?></div>
            </div>
        </div>
        
        <div class="menu-grid">
            <div class="menu-card">
                <h2>👥 Gestión de Usuarios</h2>
                <p>Crear, editar y eliminar usuarios del sistema</p>
                <a href="adminUsers.php">Gestionar Usuarios</a>
            </div>
            
            <div class="menu-card">
                <h2>📚 Gestión de Módulos</h2>
                <p>Administrar los módulos académicos</p>
                <a href="adminModulos.php">Gestionar Módulos</a>
            </div>
            
            <div class="menu-card">
                <h2>📝 Gestión de Notas</h2>
                <p>Registrar y actualizar calificaciones</p>
                <a href="adminNotas.php">Gestionar Notas</a>
            </div>

            <div class="menu-card">
                <h2>📊 Consultas Avanzadas</h2>
                <p>Estadísticas y reportes académicos</p>
                <a href="consultas-avanzadas.php">Ver Estadísticas</a>
            </div>
        </div>
    </div>
</body>
</html>