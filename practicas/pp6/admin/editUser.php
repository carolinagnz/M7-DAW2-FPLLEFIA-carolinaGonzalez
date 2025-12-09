<?php
/**
 * ========================================
 * ARCHIVO: admin/editUser.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Editar usuario existente
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Editar Usuario - Admin - " . SITE_NAME;

// Obtener ID del usuario
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id <= 0) {
    header('Location: usuarios.php');
    exit();
}

$mysqli = getDBConnection();

// Obtener datos del usuario
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

if (!$usuario) {
    header('Location: usuarios.php');
    exit();
}

$error = '';
$success = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = limpiar_input($_POST['nombre']);
    $apellidos = limpiar_input($_POST['apellidos']);
    $email = limpiar_input($_POST['email']);
    $role = limpiar_input($_POST['role']);
    $age = (int) $_POST['age'];
    $job = limpiar_input($_POST['job']);
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    // Validaciones
    if (!validar_email($email)) {
        $error = "El email no es válido";
    } else {
        // Verificar email único (excepto el actual)
        $email_escapado = $mysqli->real_escape_string($email);
        $result = $mysqli->query("SELECT id FROM users WHERE email = '$email_escapado' AND id != $user_id LIMIT 1");
        
        if ($result->num_rows > 0) {
            $error = "Este email ya está registrado por otro usuario";
        } else {
            // Gestionar avatar
            $avatar_path = $usuario['foto'];
            
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $resultado = subir_avatar($_FILES['avatar']);
                if ($resultado['success']) {
                    // Eliminar avatar anterior si no es el default
                    if (!empty($usuario['foto']) && $usuario['foto'] != 'uploads/avatars/default-avatar.jpg') {
                        eliminar_imagen('../' . $usuario['foto']);
                    }
                    $avatar_path = $resultado['path'];
                } else {
                    $error = $resultado['message'];
                }
            }
            
            if (empty($error)) {
                // Si se proporcionó nueva contraseña
                if (!empty($_POST['password'])) {
                    $password = $_POST['password'];
                    if (strlen($password) < 6) {
                        $error = "La contraseña debe tener al menos 6 caracteres";
                    } else {
                        $password_hash = password_hash($password, PASSWORD_DEFAULT);
                        
                        $stmt = $mysqli->prepare(
                            "UPDATE users SET nombre = ?, apellidos = ?, email = ?, password = ?, foto = ?, role = ?, age = ?, job = ?, activo = ? WHERE id = ?"
                        );
                        $stmt->bind_param("ssssssisii", $nombre, $apellidos, $email, $password_hash, $avatar_path, $role, $age, $job, $activo, $user_id);
                    }
                } else {
                    // Sin cambio de contraseña
                    $stmt = $mysqli->prepare(
                        "UPDATE users SET nombre = ?, apellidos = ?, email = ?, foto = ?, role = ?, age = ?, job = ?, activo = ? WHERE id = ?"
                    );
                    $stmt->bind_param("sssssisii", $nombre, $apellidos, $email, $avatar_path, $role, $age, $job, $activo, $user_id);
                }
                
                if (empty($error) && $stmt) {
                    if ($stmt->execute()) {
                        // Si el admin se editó a sí mismo, actualizar sesión
                        if ($user_id == $_SESSION['user_id']) {
                            $_SESSION['user_name'] = $nombre;
                            $_SESSION['user_email'] = $email;
                            $_SESSION['user_role'] = $role;
                            $_SESSION['user_foto'] = $avatar_path;
                        }
                        
                        header("Location: usuarios.php?success=updated");
                        exit();
                    } else {
                        $error = "Error al actualizar usuario: " . $stmt->error;
                    }
                    
                    $stmt->close();
                }
            }
        }
    }
    
    // Recargar datos del usuario si hubo error
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();
}

$mysqli->close();

include '../includes/header.php';
?>

<!-- PAGE TITLE -->
<section class="page-title bg-cover" style="background: linear-gradient(135deg, #2C3E50 0%, #34495e 100%); padding: 60px 0;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-white font-weight-bold">
                    <i class="ti-pencil"></i> Editar Usuario
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="usuarios.php" class="text-white-50">Usuarios</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Editar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- FORMULARIO -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow rounded">
                    <div class="card-body p-5">
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="ti-alert"></i> <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="ti-check"></i> <?php echo htmlspecialchars($success); ?>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php endif; ?>

                        <!-- Vista previa de avatar actual -->
                        <?php if (!empty($usuario['foto'])): ?>
                        <div class="text-center mb-4">
                            <img src="../<?php echo htmlspecialchars($usuario['foto']); ?>" 
                                 class="rounded-circle" 
                                 style="width: 100px; height: 100px; object-fit: cover;"
                                 alt="Avatar actual">
                            <p class="text-muted mt-2">Avatar actual</p>
                        </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-user"></i> Nombre *</label>
                                        <input type="text" name="nombre" class="form-control" required
                                               value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-id-badge"></i> Apellidos *</label>
                                        <input type="text" name="apellidos" class="form-control" required
                                               value="<?php echo htmlspecialchars($usuario['apellidos']); ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-email"></i> Email *</label>
                                        <input type="email" name="email" class="form-control" required
                                               value="<?php echo htmlspecialchars($usuario['email']); ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-lock"></i> Nueva Contraseña (opcional)</label>
                                        <input type="password" name="password" class="form-control" minlength="6">
                                        <small class="form-text text-muted">Déjalo vacío si no quieres cambiarla</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="ti-shield"></i> Rol *</label>
                                        <select name="role" class="form-control" required>
                                            <option value="user" <?php echo $usuario['role'] == 'user' ? 'selected' : ''; ?>>
                                                Usuario
                                            </option>
                                            <option value="admin" <?php echo $usuario['role'] == 'admin' ? 'selected' : ''; ?>>
                                                Administrador
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="ti-calendar"></i> Edad *</label>
                                        <input type="number" name="age" class="form-control" min="15" max="100" required
                                               value="<?php echo htmlspecialchars($usuario['age']); ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="ti-briefcase"></i> Profesión</label>
                                        <input type="text" name="job" class="form-control"
                                               value="<?php echo htmlspecialchars($usuario['job']); ?>">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-image"></i> Cambiar Foto de perfil</label>
                                        <div class="custom-file">
                                            <input type="file" name="avatar" class="custom-file-input" id="avatar" accept="image/*">
                                            <label class="custom-file-label" for="avatar">Elegir archivo...</label>
                                        </div>
                                        <small class="form-text text-muted">Opcional. JPG, PNG, GIF (máx 5MB)</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="activo" name="activo" 
                                                   <?php echo $usuario['activo'] ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="activo">
                                                Usuario activo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted mb-0">
                                        <small>
                                            <i class="ti-info"></i> 
                                            Registrado el <?php echo formatear_fecha($usuario['date_register']); ?>
                                        </small>
                                    </p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="usuarios.php" class="btn btn-secondary">
                                        <i class="ti-arrow-left"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti-check"></i> Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = e.target.files[0].name;
    var label = e.target.nextElementSibling;
    label.textContent = fileName;
});
</script>

<?php include '../includes/footer.php'; ?>