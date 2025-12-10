<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Obtener todos los usuarios
$result = $mysqli->query("SELECT id, nombre, apellidos, email, foto, role FROM users ORDER BY id DESC");
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
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
            max-width: 1200px;
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
            overflow: hidden;
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
        
        .user-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .role-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .role-admin {
            background: #fee;
            color: #c33;
        }
        
        .role-student {
            background: #efe;
            color: #3c3;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>👥 Gestión de Usuarios</h1>
        <a href="admin-dashboard.php" class="back-btn">← Volver al Dashboard</a>
    </div>
    
    <div class="container">
        <div class="actions">
            <a href="addUser.php" class="btn">+ Añadir Nuevo Usuario</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td>
                            <?php if ($user['foto']): ?>
                                <img src="../<?= $user['foto'] ?>" alt="Foto" class="user-photo">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/50" alt="Sin foto" class="user-photo">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['nombre']) ?></td>
                        <td><?= htmlspecialchars($user['apellidos']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="role-badge role-<?= $user['role'] ?>">
                                <?= $user['role'] === 'admin' ? 'Administrador' : 'Estudiante' ?>
                            </span>
                        </td>
                        <td class="action-links">
                            <a href="editUser.php?id=<?= $user['id'] ?>">✏️ Editar</a>
                            <a href="deleteUser.php?id=<?= $user['id'] ?>" 
                               class="delete" 
                               onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                               🗑️ Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>