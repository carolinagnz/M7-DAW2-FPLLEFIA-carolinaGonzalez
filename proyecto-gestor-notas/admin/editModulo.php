<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int) $_GET['id'];
$uploadDir = '../uploads/modulos/';
$error = '';
$success = '';

// Obtener datos del módulo
$stmt = $mysqli->prepare("SELECT * FROM modulos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$modulo = $result->fetch_assoc();

if (!$modulo) {
    header("Location: adminModulos.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    
    if (empty($nombre)) {
        $error = "El nombre del módulo es obligatorio.";
    } else {
        // Procesar imagen si se sube una nueva
        $fotoPath = $modulo['foto'];
        
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['foto']['tmp_name'];
            $fileName = $_FILES['foto']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                // Eliminar foto anterior
                if ($modulo['foto'] && file_exists('../' . $modulo['foto'])) {
                    unlink('../' . $modulo['foto']);
                }
                
                $newFileName = uniqid() . '_' . time() . '.' . $fileExtension;
                $fotoPath = 'uploads/modulos/' . $newFileName;
                
                if (!move_uploaded_file($fileTmpPath, '../' . $fotoPath)) {
                    $error = "Error al subir la imagen.";
                }
            }
        }
        
        // Actualizar módulo
        if (empty($error)) {
            $stmt = $mysqli->prepare("UPDATE modulos SET nombre=?, foto=? WHERE id=?");
            $stmt->bind_param("ssi", $nombre, $fotoPath, $id);
            
            if ($stmt->execute()) {
                $success = "Módulo actualizado correctamente.";
                $modulo['nombre'] = $nombre;
                $modulo['foto'] = $fotoPath;
            } else {
                $error = "Error al actualizar módulo: " . $stmt->error;
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
    <title>Editar Módulo</title>
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
        
        .current-photo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .current-photo img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
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
        
        .note {
            font-size: 12px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✏️ Editar Módulo</h1>
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
            
            <?php if ($modulo['foto']): ?>
            <div class="current-photo">
                <img src="../<?= $modulo['foto'] ?>" alt="Foto actual">
                <p>Foto actual</p>
            </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nombre del Módulo:</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($modulo['nombre']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Nueva Foto del Módulo:</label>
                    <input type="file" name="foto" accept="image/*">
                    <p class="note">Déjalo en blanco si no quieres cambiar la foto</p>
                </div>
                
                <input type="submit" value="Guardar Cambios">
            </form>
        </div>
    </div>
</body>
</html>