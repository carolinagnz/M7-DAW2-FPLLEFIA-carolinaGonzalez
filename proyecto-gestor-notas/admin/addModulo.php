<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$uploadDir = '../uploads/modulos/';

// Crear directorio si no existe
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    
    if (empty($nombre)) {
        $error = "El nombre del módulo es obligatorio.";
    } else {
        // Procesar imagen
        $fotoPath = NULL;
        
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['foto']['tmp_name'];
            $fileName = $_FILES['foto']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid() . '_' . time() . '.' . $fileExtension;
                $fotoPath = 'uploads/modulos/' . $newFileName;
                
                if (!move_uploaded_file($fileTmpPath, '../' . $fotoPath)) {
                    $error = "Error al subir la imagen.";
                }
            } else {
                $error = "Solo se permiten imágenes (jpg, jpeg, png, gif).";
            }
        }
        
        // Insertar módulo
        if (empty($error)) {
            $stmt = $mysqli->prepare("INSERT INTO modulos (nombre, foto) VALUES (?, ?)");
            $stmt->bind_param("ss", $nombre, $fotoPath);
            
            if ($stmt->execute()) {
                $success = "Módulo creado correctamente.";
                $nombre = ''; // Limpiar campo
            } else {
                $error = "Error al crear módulo: " . $stmt->error;
            }
            
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Módulo</title>
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
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        
        input[type="submit"]:hover {
            background: #5568d3;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>➕ Añadir Módulo</h1>
        <a href="adminModulos.php" class="back-btn">← Volver</a>
    </div>
    
    <div class="container">
        <div class="form-container">
            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nombre del Módulo:</label>
                    <input type="text" name="nombre" value="<?= isset($nombre) ? htmlspecialchars($nombre) : '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Foto del Módulo:</label>
                    <input type="file" name="foto" accept="image/*">
                </div>
                
                <input type="submit" value="Crear Módulo">
            </form>
        </div>
    </div>
</body>
</html>