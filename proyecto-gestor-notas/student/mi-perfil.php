<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$uploadDir = '../uploads/users/';
$error = '';
$success = '';

// Obtener datos del usuario
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    
    if (empty($nombre) || empty($apellidos) || empty($email)) {
        $error = "Nombre, apellidos y email son obligatorios.";
    } else {
        // Verificar si el email ya existe (excepto el propio)
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Este email ya está registrado por otro usuario.";
        } else {
            // Procesar imagen si se sube una nueva
            $fotoPath = $user['foto'];
            
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto']['tmp_name'];
                $fileName = $_FILES['foto']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    // Eliminar foto anterior
                    if ($user['foto'] && file_exists('../' . $user['foto'])) {
                        unlink('../' . $user['foto']);
                    }
                    
                    $newFileName = uniqid() . '_' . time() . '.' . $fileExtension;
                    $fotoPath = 'uploads/users/' . $newFileName;
                    
                    if (!move_uploaded_file($fileTmpPath, '../' . $fotoPath)) {
                        $error = "Error al subir la imagen.";
                    }
                }
            }
            
            // Actualizar usuario
            if (empty($error)) {
                // Si se proporciona una nueva contraseña
                if (!empty($_POST['password'])) {
                    $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt = $mysqli->prepare(
                        "UPDATE users SET nombre=?, apellidos=?, email=?, password=?, foto=? WHERE id=?"
                    );
                    $stmt->bind_param("sssssi", $nombre, $apellidos, $email, $passwordHashed, $fotoPath, $user_id);
                } else {
                    $stmt = $mysqli->prepare(
                        "UPDATE users SET nombre=?, apellidos=?, email=?, foto=? WHERE id=?"
                    );
                    $stmt->bind_param("ssssi", $nombre, $apellidos, $email, $fotoPath, $user_id);
                }
                
                if ($stmt->execute()) {
                    $success = "Perfil actualizado correctamente.";
                    // Actualizar sesión
                    $_SESSION['user_nombre'] = $nombre;
                    $_SESSION['user_apellidos'] = $apellidos;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_foto'] = $fotoPath;
                    // Recargar datos
                    $user['nombre'] = $nombre;
                    $user['apellidos'] = $apellidos;
                    $user['email'] = $email;
                    $user['foto'] = $fotoPath;
                } else {
                    $error = "Error al actualizar perfil: " . $stmt->error;
                }
                
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
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
            max-width: 600px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .current-photo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .current-photo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #11998e;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #11998e;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        
        input[type="submit"]:hover {
            background: #0d7a6f;
        }
        
        .error {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .success {
            background: #efe;
            color: #3c3;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .note {
            font-size: 12px;
            color: #666;
            font-style: italic;
        }
        
        .info-box {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #11998e;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>👤 Mi Perfil</h1>
        <a href="student-dashboard.php" class="back-btn">← Volver</a>
    </div>
    
    <div class="container">
        <div class="form-container">
            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>
            
            <div class="info-box">
                <strong>ℹ️ Información:</strong> Puedes editar tu información personal desde aquí. 
                Los campos de contraseña y foto son opcionales.
            </div>
            
            <?php if ($user['foto']): ?>
            <div class="current-photo">
                <img src="../<?= $user['foto'] ?>" alt="Mi foto">
                <p>Foto actual</p>
            </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nombre:</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Apellidos:</label>
                    <input type="text" name="apellidos" value="<?= htmlspecialchars($user['apellidos']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Nueva Contraseña:</label>
                    <input type="password" name="password">
                    <p class="note">Déjalo en blanco si no quieres cambiar tu contraseña</p>
                </div>
                
                <div class="form-group">
                    <label>Nueva Foto de perfil:</label>
                    <input type="file" name="foto" accept="image/*">
                    <p class="note">Déjalo en blanco si no quieres cambiar tu foto</p>
                </div>
                
                <input type="submit" value="Guardar Cambios">
            </form>
        </div>
    </div>
</body>
</html>