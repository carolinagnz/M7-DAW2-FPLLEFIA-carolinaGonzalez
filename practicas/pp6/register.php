<?php
/**
 * ========================================
 * ARCHIVO: register.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 */

require_once 'includes/config.php';
require_once 'includes/functions.php';

iniciar_sesion_segura();

// Si ya está logueado, redirigir
if (esta_logueado()) {
    if (es_admin()) {
        header('Location: admin/index.php');
    } else {
        header('Location: index.php');
    }
    exit();
}

$error = '';
$success = '';
$page_title = "Registro - " . SITE_NAME;

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = getDBConnection();
    
    // Recoger datos
    $nombre = limpiar_input($_POST['nombre']);
    $apellidos = limpiar_input($_POST['apellidos']);
    $email = limpiar_input($_POST['email']);
    $password = $_POST['password'];
    $age = (int) $_POST['age'];
    $job = limpiar_input($_POST['job']);
    
    // Validaciones
    if (!validar_email($email)) {
        $error = "El email no es válido";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } else {
        // Verificar email único
        $email_escapado = $mysqli->real_escape_string($email);
        $result = $mysqli->query("SELECT id FROM users WHERE email = '$email_escapado' LIMIT 1");
        
        if ($result->num_rows > 0) {
            $error = "Este email ya está registrado";
        } else {
            // Gestionar avatar
            $avatar_path = 'uploads/avatars/default-avatar.jpg';
            
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $resultado = subir_avatar($_FILES['avatar']);
                if ($resultado['success']) {
                    $avatar_path = $resultado['path'];
                } else {
                    $error = $resultado['message'];
                }
            }
            
            if (empty($error)) {
                // Hash de contraseña
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insertar usuario
                $stmt = $mysqli->prepare(
                    "INSERT INTO users (nombre, apellidos, email, password, foto, role, age, job, date_register) 
                     VALUES (?, ?, ?, ?, ?, 'user', ?, ?, NOW())"
                );
                
                if (!$stmt) {
                    die("Error en prepare: " . $mysqli->error);
                }
                
                $stmt->bind_param("sssssis", $nombre, $apellidos, $email, $password_hash, $avatar_path, $age, $job);
                
                if ($stmt->execute()) {
                    $success = "¡Usuario registrado correctamente! Redirigiendo al login...";
                    header("refresh:2;url=login.php");
                } else {
                    $error = "Error al registrar usuario: " . $stmt->error;
                }
                
                $stmt->close();
            }
        }
    }
    
    $mysqli->close();
}

include 'includes/header.php';
?>

<section class="section" style="padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4" style="color: #2C3E50;">
                            <i class="ti-user"></i> Crear Cuenta
                        </h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="ti-alert"></i> <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="ti-check"></i> <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label><i class="ti-user"></i> Nombre *</label>
                                <input type="text" name="nombre" class="form-control" required 
                                       value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="ti-id-badge"></i> Apellidos *</label>
                                <input type="text" name="apellidos" class="form-control" required
                                       value="<?php echo isset($_POST['apellidos']) ? htmlspecialchars($_POST['apellidos']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="ti-email"></i> Email *</label>
                                <input type="email" name="email" class="form-control" required
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="ti-lock"></i> Contraseña * (mínimo 6 caracteres)</label>
                                <input type="password" name="password" class="form-control" minlength="6" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-calendar"></i> Edad *</label>
                                        <input type="number" name="age" class="form-control" min="15" max="100" required
                                               value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-briefcase"></i> Profesión</label>
                                        <input type="text" name="job" class="form-control"
                                               value="<?php echo isset($_POST['job']) ? htmlspecialchars($_POST['job']) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="ti-image"></i> Foto de perfil</label>
                                <div class="custom-file">
                                    <input type="file" name="avatar" class="custom-file-input" id="avatar" accept="image/*">
                                    <label class="custom-file-label" for="avatar">Elegir archivo...</label>
                                </div>
                                <small class="form-text text-muted">Opcional. JPG, PNG, GIF (máx 5MB)</small>
                            </div>
                            
                            <button type="submit" class="btn btn-block btn-lg mt-4" 
                                    style="background-color: #2C3E50; color: white;">
                                <i class="ti-check"></i> Registrarse
                            </button>
                            
                            <hr class="my-4">
                            <p class="text-center mb-0">
                                ¿Ya tienes cuenta? 
                                <a href="login.php" style="color: #E67E22;">Inicia sesión aquí</a>
                            </p>
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

<?php include 'includes/footer.php'; ?>