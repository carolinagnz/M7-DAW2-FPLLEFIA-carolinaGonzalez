<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener notas del estudiante
$query = "
    SELECT 
        m.nombre AS modulo,
        m.foto AS modulo_foto,
        n.nota,
        n.fecha_registro
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Notas</title>
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
        
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #11998e;
            color: white;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            font-weight: bold;
        }
        
        tbody tr:hover {
            background: #f9f9f9;
        }
        
        .module-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .module-photo {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
        }
        
        .nota-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 18px;
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
        
        .no-data-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📝 Mis Notas</h1>
        <a href="student-dashboard.php" class="back-btn">← Volver</a>
    </div>
    
    <div class="container">
        <div class="table-container">
            <?php if (count($notas) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Módulo</th>
                        <th>Nota</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($notas as $nota): ?>
                    <tr>
                        <td>
                            <div class="module-info">
                                <?php if ($nota['modulo_foto']): ?>
                                    <img src="../<?= $nota['modulo_foto'] ?>" alt="Módulo" class="module-photo">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/50" alt="Módulo" class="module-photo">
                                <?php endif; ?>
                                <strong><?= htmlspecialchars($nota['modulo']) ?></strong>
                            </div>
                        </td>
                        <td>
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
                            <span class="nota-badge <?= $clase ?>">
                                <?= number_format($nota_valor, 2) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($nota_valor >= 5): ?>
                                <strong style="color: #28a745;">✓ Aprobado</strong>
                            <?php else: ?>
                                <strong style="color: #dc3545;">✗ Suspenso</strong>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($nota['fecha_registro'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="no-data">
                <div class="no-data-icon">📝</div>
                <h2>No tienes notas registradas</h2>
                <p>Las notas aparecerán aquí cuando los profesores las registren.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>