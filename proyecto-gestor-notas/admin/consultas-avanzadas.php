<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// 1. Promedio de notas por módulo
$query_promedio_modulos = "
    SELECT 
        m.nombre AS modulo,
        ROUND(AVG(n.nota), 2) AS promedio,
        COUNT(n.id) AS total_notas
    FROM modulos m
    LEFT JOIN notas n ON m.id = n.id_modulo
    GROUP BY m.id, m.nombre
    ORDER BY promedio DESC
";
$promedios_modulos = $mysqli->query($query_promedio_modulos)->fetch_all(MYSQLI_ASSOC);

// 2. Alumnos con promedio >= 8
$query_alumnos_destacados = "
    SELECT 
        u.nombre,
        u.apellidos,
        ROUND(AVG(n.nota), 2) AS media
    FROM users u
    JOIN notas n ON u.id = n.id_usuario
    WHERE u.role = 'student'
    GROUP BY u.id, u.nombre, u.apellidos
    HAVING media >= 8
    ORDER BY media DESC
";
$alumnos_destacados = $mysqli->query($query_alumnos_destacados)->fetch_all(MYSQLI_ASSOC);

// 3. Alumnos con promedio < 5
$query_alumnos_bajo = "
    SELECT 
        u.nombre,
        u.apellidos,
        ROUND(AVG(n.nota), 2) AS media
    FROM users u
    JOIN notas n ON u.id = n.id_usuario
    WHERE u.role = 'student'
    GROUP BY u.id, u.nombre, u.apellidos
    HAVING media < 5
    ORDER BY media ASC
";
$alumnos_bajo = $mysqli->query($query_alumnos_bajo)->fetch_all(MYSQLI_ASSOC);

// 4. Módulo con mejor promedio
$mejor_modulo = count($promedios_modulos) > 0 ? $promedios_modulos[0] : null;

// 5. Módulo con peor promedio
$peor_modulo = count($promedios_modulos) > 0 ? end($promedios_modulos) : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas Avanzadas</title>
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
        
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #555;
        }
        
        .promedio-alto {
            color: #28a745;
            font-weight: bold;
        }
        
        .promedio-bajo {
            color: #dc3545;
            font-weight: bold;
        }
        
        .promedio-medio {
            color: #ffc107;
            font-weight: bold;
        }
        
        .highlight-box {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
            margin-bottom: 15px;
        }
        
        .no-data {
            text-align: center;
            color: #666;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Consultas Avanzadas y Estadísticas</h1>
        <a href="admin-dashboard.php" class="back-btn">← Volver al Dashboard</a>
    </div>
    
    <div class="container">
        <!-- Estadísticas destacadas -->
        <div class="card">
            <h2>📈 Estadísticas Destacadas</h2>
            
            <?php if ($mejor_modulo): ?>
            <div class="highlight-box">
                <strong>🏆 Módulo con mejor promedio:</strong> 
                <?= htmlspecialchars($mejor_modulo['modulo']) ?> 
                (Promedio: <?= $mejor_modulo['promedio'] ?>)
            </div>
            <?php endif; ?>
            
            <?php if ($peor_modulo && $peor_modulo !== $mejor_modulo): ?>
            <div class="highlight-box" style="background: #ffebee; border-color: #dc3545;">
                <strong>⚠️ Módulo con promedio más bajo:</strong> 
                <?= htmlspecialchars($peor_modulo['modulo']) ?> 
                (Promedio: <?= $peor_modulo['promedio'] ?>)
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Promedio por módulo -->
        <div class="card">
            <h2>📚 Promedio de Notas por Módulo</h2>
            <?php if (count($promedios_modulos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Módulo</th>
                        <th>Promedio</th>
                        <th>Total de Notas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($promedios_modulos as $pm): ?>
                    <tr>
                        <td><?= htmlspecialchars($pm['modulo']) ?></td>
                        <td>
                            <span class="<?= $pm['promedio'] >= 7 ? 'promedio-alto' : ($pm['promedio'] >= 5 ? 'promedio-medio' : 'promedio-bajo') ?>">
                                <?= $pm['promedio'] ?? 'N/A' ?>
                            </span>
                        </td>
                        <td><?= $pm['total_notas'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="no-data">No hay datos disponibles</p>
            <?php endif; ?>
        </div>
        
        <!-- Alumnos destacados -->
        <div class="card">
            <h2>⭐ Alumnos Destacados (Promedio ≥ 8)</h2>
            <?php if (count($alumnos_destacados) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($alumnos_destacados as $alumno): ?>
                    <tr>
                        <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                        <td><?= htmlspecialchars($alumno['apellidos']) ?></td>
                        <td><span class="promedio-alto"><?= $alumno['media'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="no-data">No hay alumnos con promedio mayor o igual a 8</p>
            <?php endif; ?>
        </div>
        
        <!-- Alumnos en riesgo -->
        <div class="card">
            <h2>⚠️ Alumnos en Riesgo (Promedio < 5)</h2>
            <?php if (count($alumnos_bajo) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($alumnos_bajo as $alumno): ?>
                    <tr>
                        <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                        <td><?= htmlspecialchars($alumno['apellidos']) ?></td>
                        <td><span class="promedio-bajo"><?= $alumno['media'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="no-data">No hay alumnos con promedio menor a 5 😊</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>