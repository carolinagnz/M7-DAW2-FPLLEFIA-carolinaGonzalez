<?php
session_start();
require_once('../config.php');

// Control de acceso - Solo estudiantes
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener información del estudiante
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Obtener estadísticas del estudiante
$total_modulos = $mysqli->query("
    SELECT COUNT(DISTINCT id_modulo) as total 
    FROM notas 
    WHERE id_usuario = $user_id
")->fetch_assoc()['total'];

$total_notas = $mysqli->query("
    SELECT COUNT(*) as total 
    FROM notas 
    WHERE id_usuario = $user_id
")->fetch_assoc()['total'];

$promedio_result = $mysqli->query("
    SELECT ROUND(AVG(nota), 2) as promedio 
    FROM notas 
    WHERE id_usuario = $user_id
");
$promedio = $promedio_result->fetch_assoc()['promedio'] ?? 0;

// Contar aprobados y suspensos
$aprobados = $mysqli->query("
    SELECT COUNT(*) as total 
    FROM notas 
    WHERE id_usuario = $user_id AND nota >= 5
")->fetch_assoc()['total'];

$suspensos = $mysqli->query("
    SELECT COUNT(*) as total 
    FROM notas 
    WHERE id_usuario = $user_id AND nota < 5
")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel - Estudiante</title>
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
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid white;
            object-fit: cover;
        }
        
        .user-details {
            text-align: right;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            margin-left: 15px;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .welcome-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .welcome-card h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .welcome-card p {
            color: #666;
            font-size: 16px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
            color: #11998e;
        }
        
        .stat-card.promedio .number {
            color: #38ef7d;
        }
        
        .stat-card.aprobados .number {
            color: #28a745;
        }
        
        .stat-card.suspensos .number {
            color: #dc3545;
        }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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
        
        .menu-card .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .menu-card h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .menu-card p {
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .menu-card a {
            display: inline-block;
            background: #11998e;
            color: white;
            padding: 10px 30px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .menu-card a:hover {
            background: #0d7a6f;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎓 Mi Panel de Estudiante</h1>
        <div class="user-info">
            <?php if ($user['foto']): ?>
                <img src="../<?= $user['foto'] ?>" alt="Mi foto">
            <?php else: ?>
                <img src="https://via.placeholder.com/60" alt="Sin foto">
            <?php endif; ?>
            <div class="user-details">
                <strong><?= htmlspecialchars($user['nombre']) ?> <?= htmlspecialchars($user['apellidos']) ?></strong><br>
                <small><?= htmlspecialchars($user['email']) ?></small>
            </div>
            <a href="../logout.php" class="logout-btn">Cerrar Sesión</a>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome-card">
            <h2>¡Bienvenido/a, <?= htmlspecialchars($user['nombre']) ?>! 👋</h2>
            <p>Este es tu panel personal donde puedes consultar tus módulos, notas y promedio académico.</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Módulos Matriculados</h3>
                <div class="number"><?= $total_modulos ?></div>
            </div>
            <div class="stat-card">
                <h3>Total de Notas</h3>
                <div class="number"><?= $total_notas ?></div>
            </div>
            <div class="stat-card promedio">
                <h3>Promedio General</h3>
                <div class="number"><?= $promedio ?></div>
            </div>
            <div class="stat-card aprobados">
                <h3>Aprobados</h3>
                <div class="number"><?= $aprobados ?></div>
            </div>
            <div class="stat-card suspensos">
                <h3>Suspensos</h3>
                <div class="number"><?= $suspensos ?></div>
            </div>
        </div>
        
        <div class="menu-grid">
            <div class="menu-card">
                <div class="icon">👤</div>
                <h2>Mi Perfil</h2>
                <p>Edita tu información personal</p>
                <a href="mi-perfil.php">Ver Perfil</a>
            </div>
            
            <div class="menu-card">
                <div class="icon">📚</div>
                <h2>Mis Módulos</h2>
                <p>Consulta los módulos en los que estás matriculado</p>
                <a href="mis-modulos.php">Ver Módulos</a>
            </div>
            
            <div class="menu-card">
                <div class="icon">📝</div>
                <h2>Mis Notas</h2>
                <p>Revisa todas tus calificaciones</p>
                <a href="mis-notas.php">Ver Notas</a>
            </div>
            
            <div class="menu-card">
                <div class="icon">📊</div>
                <h2>Mi Media</h2>
                <p>Consulta tu promedio académico detallado</p>
                <a href="mi-media.php">Ver Media</a>
            </div>
        </div>
    </div>
</body>
</html>