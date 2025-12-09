<?php
/**
 * ========================================
 * ARCHIVO: admin/addTestimonio.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Añadir nuevo testimonio
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Nuevo Testimonio - Admin - " . SITE_NAME;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = getDBConnection();
    
    $nombre_cliente = limpiar_input($_POST['nombre_cliente']);
    $cargo = limpiar_input($_POST['cargo']);
    $empresa = limpiar_input($_POST['empresa']);
    $testimonio = limpiar_input($_POST['testimonio']);
    $puntuacion = (int) $_POST['puntuacion'];
    $destacado = isset($_POST['destacado']) ? 1 : 0;
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    // Validaciones
    if (empty($nombre_cliente)) {
        $error = "El nombre del cliente es obligatorio";
    } elseif (empty($testimonio)) {
        $error = "El testimonio es obligatorio";
    } elseif ($puntuacion < 1 || $puntuacion > 5) {
        $error = "La puntuación debe estar entre 1 y 5";
    } else {
        // Gestionar foto del cliente
        $foto_path = '';
        
        if (isset($_FILES['foto_cliente']) && $_FILES['foto_cliente']['error'] === UPLOAD_ERR_OK) {
            $resultado = subir_imagen($_FILES['foto_cliente'], 'testimonios');
            if ($resultado['success']) {
                $foto_path = $resultado['path'];
            } else {
                $error = $resultado['message'];
            }
        }
        
        if (empty($error)) {
            $stmt = $mysqli->prepare(
                "INSERT INTO testimonios (nombre_cliente, cargo, empresa, testimonio, foto_cliente, puntuacion, destacado, activo, fecha_testimonio) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())"
            );
            
            if ($stmt) {
                $stmt->bind_param("sssssiil", $nombre_cliente, $cargo, $empresa, $testimonio, $foto_path, $puntuacion, $destacado, $activo);
                
                if ($stmt->execute()) {
                    header("Location: testimonios.php?success=added");
                    exit();
                } else {
                    $error = "Error al crear testimonio: " . $stmt->error;
                }
                
                $stmt->close();
            }
        }
    }
    
    $mysqli->close();
}

include '../includes/header.php';
?>

<!-- PAGE TITLE -->
<section class="page-title bg-cover" style="background: linear-gradient(135deg, #2C3E50 0%, #34495e 100%); padding: 60px 0;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-white font-weight-bold">
                    <i class="ti-comment"></i> Nuevo Testimonio
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="testimonios.php" class="text-white-50">Testimonios</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Nuevo</li>
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

                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-user"></i> Nombre del Cliente *</label>
                                        <input type="text" name="nombre_cliente" class="form-control" required
                                               value="<?php echo isset($_POST['nombre_cliente']) ? htmlspecialchars($_POST['nombre_cliente']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-id-badge"></i> Cargo *</label>
                                        <input type="text" name="cargo" class="form-control" required
                                               placeholder="Ej: CEO, Director, Manager"
                                               value="<?php echo isset($_POST['cargo']) ? htmlspecialchars($_POST['cargo']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-briefcase"></i> Empresa *</label>
                                        <input type="text" name="empresa" class="form-control" required
                                               value="<?php echo isset($_POST['empresa']) ? htmlspecialchars($_POST['empresa']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-quote-left"></i> Testimonio *</label>
                                        <textarea name="testimonio" class="form-control" rows="6" required><?php echo isset($_POST['testimonio']) ? htmlspecialchars($_POST['testimonio']) : ''; ?></textarea>
                                        <small class="form-text text-muted">Opinión o comentario del cliente</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-star"></i> Puntuación *</label>
                                        <select name="puntuacion" class="form-control" required>
                                            <option value="">Seleccionar...</option>
                                            <option value="5" <?php echo (isset($_POST['puntuacion']) && $_POST['puntuacion'] == 5) ? 'selected' : ''; ?>>
                                                ⭐⭐⭐⭐⭐ (5 estrellas)
                                            </option>
                                            <option value="4" <?php echo (isset($_POST['puntuacion']) && $_POST['puntuacion'] == 4) ? 'selected' : ''; ?>>
                                                ⭐⭐⭐⭐ (4 estrellas)
                                            </option>
                                            <option value="3" <?php echo (isset($_POST['puntuacion']) && $_POST['puntuacion'] == 3) ? 'selected' : ''; ?>>
                                                ⭐⭐⭐ (3 estrellas)
                                            </option>
                                            <option value="2" <?php echo (isset($_POST['puntuacion']) && $_POST['puntuacion'] == 2) ? 'selected' : ''; ?>>
                                                ⭐⭐ (2 estrellas)
                                            </option>
                                            <option value="1" <?php echo (isset($_POST['puntuacion']) && $_POST['puntuacion'] == 1) ? 'selected' : ''; ?>>
                                                ⭐ (1 estrella)
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-image"></i> Foto del Cliente</label>
                                        <div class="custom-file">
                                            <input type="file" name="foto_cliente" class="custom-file-input" id="foto" accept="image/*">
                                            <label class="custom-file-label" for="foto">Elegir archivo...</label>
                                        </div>
                                        <small class="form-text text-muted">Opcional. JPG, PNG (máx 5MB)</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="destacado" name="destacado">
                                            <label class="custom-control-label" for="destacado">
                                                <i class="ti-star"></i> Marcar como destacado
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="activo" name="activo" checked>
                                            <label class="custom-control-label" for="activo">
                                                <i class="ti-check"></i> Testimonio activo (visible)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="text-right">
                                <a href="testimonios.php" class="btn btn-secondary">
                                    <i class="ti-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="ti-check"></i> Crear Testimonio
                                </button>
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