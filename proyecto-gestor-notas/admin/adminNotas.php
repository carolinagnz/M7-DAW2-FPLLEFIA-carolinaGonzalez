<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Obtener todas las notas con JOIN
$query = "
    SELECT 
        n.id,
        n.nota,
        u.nombre AS usuario_nombre,
        u.apellidos AS usuario_apellidos,
        u.foto AS usuario_foto,
        m.nombre AS modulo_nombre,
        m.foto AS modulo_foto
    FROM notas n
    JOIN users u ON n.id_usuario = u.id
    JOIN modulos m ON n.id_modulo = m.id
    ORDER BY n.id DESC
";

$result = $mysqli->query($query);
$notas = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Notas</title>
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
        
        .header h1 {
            font-size: 24px;
        }
        
        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .actions {
            margin-bottom: 20px;
        }
        
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #5568d3;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #667eea;
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
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .module-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .module-photo {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
        }
        
        .nota-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 16px;
        }
        
        .nota-aprobado {
            background: #d4edda;
            color: #155724;
        }
        
        .nota-suspendido {
            background: #f8d7da;
            color: #721c24;
        }
        
        .action-links a {
            margin-right: 10px;
            color: #667eea;
            text-decoration: none;
        }
        
        .action-links a:hover {
            text-decoration: underline;
        }
        
        .action-links .delete {
            color: #c33;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📝 Gestión de Notas</h1>
        <a href="admin-dashboard.php" class="back-btn">← Volver al Dashboard</a>
    </div>
    
    <div class="container">
        <div class="actions">
            <a href="addNota.php" class="btn">+ Añadir Nueva Nota</a>
        </div>
        
        <div class="table-container">
            <?php if (count($notas) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estudiante</th>
                        <th>Módulo</th>
                        <th>Nota</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($notas as $nota): ?>
                    <tr>
                        <td><?= $nota['id'] ?></td>
                        <td>
                            <div class="user-info">
                                <?php if ($nota['usuario_foto']): ?>
                                    <img src="../<?= $nota['usuario_foto'] ?>" alt="Foto" class="user-photo">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/40" alt="Sin foto" class="user-photo">
                                <?php endif; ?>
                                <div>
                                    <strong><?= htmlspecialchars($nota['usuario_nombre']) ?> <?= htmlspecialchars($nota['usuario_apellidos']) ?></strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="module-info">
                                <?php if ($nota['modulo_foto']): ?>
                                    <img src="../<?= $nota['modulo_foto'] ?>" alt="Foto" class="module-photo">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/50" alt="Sin foto" class="module-photo">
                                <?php endif; ?>
                                <div>
                                    <strong><?= htmlspecialchars($nota['modulo_nombre']) ?></strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="nota-badge <?= $nota['nota'] >= 5 ? 'nota-aprobado' : 'nota-suspendido' ?>">
                                <?= number_format($nota['nota'], 2) ?>
                            </span>
                        </td>
                        <td class="action-links">
                            <a href="editNota.php?id=<?= $nota['id'] ?>">✏️ Editar</a>
                            <a href="deleteNota.php?id=<?= $nota['id'] ?>" 
                               class="delete" 
                               onclick="return confirm('¿Estás seguro de eliminar esta nota?')">
                               🗑️ Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="no-data">
                <p>No hay notas registradas todavía.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>