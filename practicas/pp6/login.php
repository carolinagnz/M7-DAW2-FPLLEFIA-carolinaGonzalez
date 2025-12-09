<?php
/**
 * ========================================
 * ARCHIVO: login.php
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
$page_title = "Iniciar Sesión - " . SITE_NAME;

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = getDBConnection();
    
    $email = limpiar_input($_POST['email']);
    $password = $_POST['password'];
    
    // Buscar usuario
    $email_escapado = $mysqli->real_escape_string($email);
    $result = $mysqli->query(
        "SELECT * FROM users WHERE email = '$email_escapado' AND activo = TRUE LIMIT 1"
    );
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verificar contraseña
        if (password_verify($password, $user['password'])) {
            // Login exitoso - crear sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_foto'] = $user['foto'];
            $_SESSION['last_activity'] = time();
            
            // Redirigir según rol
            if ($user['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
            
        } else {
            $error = "Email o contraseña incorrectos";
        }
    } else {
        $error = "Email o contraseña incorrectos";
    }
    
    $mysqli->close();
}

include 'includes/header.php';
?>

<section class="section" style="padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        
                        <div class="text-center mb-4">
                            <i class="ti-lock" style="font-size: 48px; color: #2C3E50;"></i>
                            <h2 class="mt-3" style="color: #2C3E50;">Iniciar Sesión</h2>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="ti-alert"></i> <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['timeout'])): ?>
                            <div class="alert alert-warning">
                                <i class="ti-time"></i> Tu sesión ha expirado por inactividad. 
                                Inicia sesión de nuevo.
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label><i class="ti-email"></i> Email</label>
                                <input type="email" name="email" class="form-control" required autofocus
                                       placeholder="tu@email.com"
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="ti-lock"></i> Contraseña</label>
                                <input type="password" name="password" class="form-control" required
                                       placeholder="••••••••">
                            </div>
                            
                            <button type="submit" class="btn btn-block btn-lg mt-4" 
                                    style="background-color: #2C3E50; color: white;">
                                <i class="ti-arrow-right"></i> Entrar
                            </button>
                            
                            <hr class="my-4">
                            
                            <p class="text-center mb-0">
                                ¿No tienes cuenta? 
                                <a href="register.php" style="color: #E67E22;">Regístrate aquí</a>
                            </p>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>