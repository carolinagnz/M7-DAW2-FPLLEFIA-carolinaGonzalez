<?php
session_start();
require_once('../config.php');

// Control de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

// Obtener lista de estudiantes (solo users con role='student')
$students_result = $mysqli->query("SELECT id, nombre, apellidos FROM users WHERE role='student' ORDER BY nombre, apellidos");
$students = $students_result->fetch_all(MYSQLI_ASSOC);

// Obtener lista de módulos
$modulos_result = $mysqli->query("SELECT id, nombre FROM modulos ORDER BY nombre");
$modulos = $modulos_result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = (int) $_POST['id_usuario'];
    $id_modulo = (int) $_POST['id_modulo'];
    $nota = (float) $_POST['nota'];
    
    // Validaciones
    if (empty($id_usuario) || empty($id_modulo)) {
        $error = "Debes seleccionar un estudiante y un módulo.";
    } elseif ($nota < 0 || $nota > 10) {
        $error = "La nota debe estar entre 0 y 10.";
    } else {
        // Verificar que no exista ya una nota para este estudiante en este módulo
        $stmt = $mysqli->prepare("SELECT id FROM notas WHERE id_usuario = ? AND id_modulo = ?");
        $stmt->bind_param("ii", $id_usuario, $id_modulo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Este estudiante ya tiene una nota registrada en este módulo.";
        } else {
            // Insertar nota
            $stmt = $mysqli->prepare("INSERT INTO notas (nota, id_usuario, id_modulo) VALUES (?, ?, ?)");
            $stmt->bind_param("dii", $nota, $id_usuario, $id_modulo);
            
            if ($stmt->execute()) {
                $success = "Nota registrada correctamente.";
            } else {
                $error = "Error al registrar nota: " . $stmt->error;
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
    <title>Añadir Nota</title>
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
        
        select,
        input[type="number"] {
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
            margin-top: 5px;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #17a2b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>➕ Añadir Nota</h1>
        <a href="adminNotas.php" class="back-btn">← Volver</a>
    </div>
    
    <div class="container">
        <div class="form-container">
            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>
            
            <?php if (count($students) == 0 || count($modulos) == 0): ?>
                <div class="alert-info">
                    <strong>⚠️ Atención:</strong><br>
                    <?php if (count($students) == 0): ?>
                        No hay estudiantes registrados. <a href="adminUsers.php">Añade estudiantes primero</a>.<br>
                    <?php endif; ?>
                    <?php if (count($modulos) == 0): ?>
                        No hay módulos registrados. <a href="adminModulos.php">Añade módulos primero</a>.
                    <?php endif; ?>
                </div>
            <?php else: ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Estudiante:</label>
                    <select name="id_usuario" required>
                        <option value="">-- Selecciona un estudiante --</option>
                        <?php foreach($students as $student): ?>
                            <option value="<?= $student['id'] ?>">
                                <?= htmlspecialchars($student['nombre']) ?> <?= htmlspecialchars($student['apellidos']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Módulo:</label>
                    <select name="id_modulo" required>
                        <option value="">-- Selecciona un módulo --</option>
                        <?php foreach($modulos as $modulo): ?>
                            <option value="<?= $modulo['id'] ?>">
                                <?= htmlspecialchars($modulo['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Nota:</label>
                    <input type="number" name="nota" step="0.01" min="0" max="10" required>
                    <p class="note">Introduce una nota entre 0 y 10 (puedes usar decimales)</p>
                </div>
                
                <input type="submit" value="Registrar Nota">
            </form>
            
            <?php endif; ?>
        </div>
    </div>
</body>
</html>