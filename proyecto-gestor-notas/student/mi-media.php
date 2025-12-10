<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener promedio general
$promedio_result = $mysqli->query("
    SELECT ROUND(AVG(nota), 2) as promedio 
    FROM notas 
    WHERE id_usuario = $user_id
");
$promedio_general = $promedio_result->fetch_assoc()['promedio'] ?? 0;

// Obtener notas por módulo con promedio
$query = "
    SELECT 
        m.nombre AS modulo,
        m.foto AS modulo_foto,
        n.nota
    FROM notas n
    JOIN modulos m ON n.id_modulo = m.id
    WHERE n.id_usuario = ?
    ORDER BY n.nota DESC
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notas = $result->fetch_all(MYSQLI_ASSOC);

// Calcular estadísticas
$total_notas = count($notas);
$aprobados = 0;
$suspensos = 0;
$nota_mas_alta = 0;
$nota_mas_baja = 10;

foreach ($notas as $nota) {
    if ($nota['nota'] >= 5) {
        $aprobados++;
    } else {
        $suspensos++;
    }
    
    if ($nota['nota'] > $nota_mas_alta) {
        $nota_mas_alta = $nota['nota'];
    }
    
    if ($nota['nota'] < $nota_mas_baja) {
        $nota_mas_baja = $nota['nota'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Media</title>
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
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .promedio-principal {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 30px;
        }
        
        .promedio-principal h2 {
            color: #666;
            font-size: 18px;
            margin-bottom: 20px;
        }
        
        .promedio-numero {
            font-size: 72px;
            font-weight: bold;
            color: #11998e;
            margin-bottom: 10px;
        }
        
        .promedio-label {
            color: #999;
            font-size: 16px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-box h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .stat-box .numero {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-box.aprobados .numero {
            color: #28a745;
        }
        
        .stat-box.suspensos .numero {
            color: #dc3545;
        }
        
        .stat-box.alta .numero {
            color: #38ef7d;
        }
        
        .stat-box.baja .numero {
            color: #ff6b6b;
        }
        
        .detalles-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .detalles-card h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #11998e;
        }
        
        .nota-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .nota-item:last-child {
            border-bottom: none;
        }
        
        .nota-item:hover {
            background: #f9f9f9;
        }
        
        .modulo-nombre {
            font-weight: bold;
            color: #333;
        }
        
        .nota-valor {
            font-size: 20px;
            font-weight: bold;
            padding: 5px 15px;
            border-radius: 20px;
        }
        
        .nota-excelente {
            background: #d4edda;
            color: #155724;
        }
        
        .nota-buena {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .nota-aprobado {
            background: #fff3cd;
            color: #856404;
        }
        
        .nota-suspendido {
            background: #f8d7da;
            color: #721c24;
        }
        
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .print-btn {
            display: inline-block;
            background: #11998e;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        
        .print-btn:hover {
            background: #0d7a6f;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Mi Promedio Académico</h1>
        <a href="student-dashboard.php" class="back-btn">← Volver</a>
    </div>
    
    <div class="container">
        <?php if ($total_notas > 0): ?>
        
        <div class="promedio-principal">
            <h2>Tu Promedio General</h2>
            <div class="promedio-numero"><?= number_format($promedio_general, 2) ?></div>
            <div class="promedio-label">sobre 10.00</div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-box">
                <h3>Total de Notas</h3>
                <div class="numero" style="color: #667eea;"><?= $total_notas ?></div>
            </div>
            
            <div class="stat-box aprobados">
                <h3>Aprobados</h3>
                <div class="numero"><?= $aprobados ?></div>
            </div>
            
            <div class="stat-box suspensos">
                <h3>Suspensos</h3>
                <div class="numero"><?= $suspensos ?></div>
            </div>
            
            <div class="stat-box alta">
                <h3>Nota Más Alta</h3>
                <div class="numero"><?= number_format($nota_mas_alta, 2) ?></div>
            </div>
            
            <div class="stat-box baja">
                <h3>Nota Más Baja</h3>
                <div class="numero"><?= number_format($nota_mas_baja, 2) ?></div>
            </div>
        </div>
        
        <div class="detalles-card">
            <h2>Detalle de Notas por Módulo</h2>
            <?php foreach($notas as $nota): ?>
            <div class="nota-item">
                <div class="modulo-nombre"><?= htmlspecialchars($nota['modulo']) ?></div>
                <?php
                $nota_valor = $nota['nota'];
                if ($nota_valor >= 9) {
                    $clase = 'nota-excelente';
                } elseif ($nota_valor >= 7) {
                    $clase = 'nota-buena';
                } elseif ($nota_valor >= 5) {
                    $clase = 'nota-aprobado';
                } else {
                    $clase = 'nota-suspendido';
                }
                ?>
                <div class="nota-valor <?= $clase ?>">
                    <?= number_format($nota_valor, 2) ?>
                </div>
            </div>
            <?php endforeach; ?>
            
            <div style="text-align: center;">
                <a href="#" onclick="window.print(); return false;" class="print-btn">
                    🖨️ Imprimir Expediente
                </a>
            </div>
        </div>
        
        <?php else: ?>
        <div class="promedio-principal">
            <div class="no-data">
                <div style="font-size: 64px; margin-bottom: 20px;">📊</div>
                <h2>No tienes notas registradas</h2>
                <p>Tu promedio aparecerá aquí cuando tengas notas registradas.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
