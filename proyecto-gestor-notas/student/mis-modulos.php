<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener módulos matriculados
$query = "
    SELECT DISTINCT
        m.id,
        m.nombre,
        m.foto
    FROM modulos m
    JOIN notas n ON m.id = n.id_modulo
    WHERE n.id_usuario = ?
    ORDER BY m.nombre
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$modulos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Módulos</title>
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
        }
        
        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .module-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .module-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .module-content {
            padding: 20px;
        }
        
        .module-content h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .module-id {
            color: #666;
            font-size: 14px;
        }
        
        .no-data {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .no-data-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .no-data h2 {
            color: #666;
            margin-bottom: 10px;
        }
        
        .no-data p {
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📚 Mis Módulos</h1>
        <a href="student-dashboard.php" class="back-btn">← Volver</a>
    </div>
    
    <div class="container">
        <?php if (count($modulos) > 0): ?>
        <div class="modules-grid">
            <?php foreach($modulos as $modulo): ?>
            <div class="module-card">
                <?php if ($modulo['foto']): ?>
                    <img src="../<?= $modulo['foto'] ?>" alt="<?= htmlspecialchars($modulo['nombre']) ?>" class="module-image">
                <?php else: ?>
                    <div class="module-image"></div>
                <?php endif; ?>
                <div class="module-content">
                    <h3><?= htmlspecialchars($modulo['nombre']) ?></h3>
                    <p class="module-id">ID: <?= $modulo['id'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="no-data">
            <div class="no-data-icon">📚</div>
            <h2>No estás matriculado en ningún módulo</h2>
            <p>Contacta con la administración para matricularte en los módulos disponibles.</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>