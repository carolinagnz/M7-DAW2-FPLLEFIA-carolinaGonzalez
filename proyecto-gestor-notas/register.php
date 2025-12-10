<?php
require_once('config.php');
session_start();

// Directorio para guardar fotos
$uploadDir = 'uploads/users/';

// Crear directorio si no existe
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. RECOGER DATOS DEL FORMULARIO
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    
    // 2. VALIDACIONES
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } 
    elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    }
    elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    }
    else {
        // 3. VERIFICAR SI EL EMAIL YA EXISTE
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Este email ya está registrado.";
        } else {
            
            // 4. PROCESAR LA IMAGEN
            $fotoPath = NULL;
            
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto']['tmp_name'];
                $fileName = $_FILES['foto']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = uniqid() . '_' . time() . '.' . $fileExtension;
                    $fotoPath = $uploadDir . $newFileName;
                    
                    if (!move_uploaded_file($fileTmpPath, $fotoPath)) {
                        $error = "Error al subir la imagen.";
                    }
                } else {
                    $error = "Solo se permiten imágenes (jpg, jpeg, png, gif).";
                }
            }
            
            // 5. SI NO HAY ERRORES, INSERTAR USUARIO
            if (empty($error)) {
                // ENCRIPTAR CONTRASEÑA
                $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
                
                // INSERTAR EN LA BASE DE DATOS
                $stmt = $mysqli->prepare(
                    "INSERT INTO users (nombre, apellidos, email, password, foto, role) 
                     VALUES (?, ?, ?, ?, ?, ?)"
                );
                $stmt->bind_param("ssssss", $nombre, $apellidos, $email, $passwordHashed, $fotoPath, $role);
                
                if ($stmt->execute()) {
                    $success = "Usuario registrado correctamente. <a href='login.php'>Ir al login</a>";
                } else {
                    $error = "Error al registrar usuario: " . $stmt->error;
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
    <title>Registro - Gestor Académico</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
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
        input[type="file"],
        select {
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
            transition: background 0.3s;
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
            border: 1px solid #fcc;
        }
        
        .success {
            background: #efe;
            color: #3c3;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #cfc;
        }
        
        .link {
            text-align: center;
            margin-top: 20px;
        }
        
        .link a {
            color: #667eea;
            text-decoration: none;
        }
        
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Registro</h1>
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label>Apellidos:</label>
                <input type="text" name="apellidos" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>Confirmar Contraseña:</label>
                <input type="password" name="confirm_password" required>
            </div>
            
            <div class="form-group">
                <label>Foto de perfil:</label>
                <input type="file" name="foto" accept="image/*">
            </div>
            
            <div class="form-group">
                <label>Rol:</label>
                <select name="role" required>
                    <option value="student">Estudiante</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            
            <input type="submit" value="Registrar">
        </form>
        
        <div class="link">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>