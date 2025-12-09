<?php
/**
 * ========================================
 * ARCHIVO: admin/editTestimonio.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Editar testimonio existente
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Editar Testimonio - Admin - " . SITE_NAME;

// Obtener ID del testimonio
$testimonio_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($testimonio_id <= 0) {
    header('Location: testimonios.php');
    exit();
}

$mysqli = getDBConnection();

// Obtener datos del testimonio
$stmt = $mysqli->prepare("SELECT * FROM testimonios WHERE id = ?");
$stmt->bind_param("i", $testimonio_id);
$stmt->execute();
$result = $stmt->get_result();
$testimonio = $result->fetch_assoc();
$stmt->close();

if (!$testimonio) {
    header('Location: testimonios.php');
    exit();
}

$error = '';
$success = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_cliente = limpiar_input($_POST['nombre_cliente']);
    $cargo = limpiar_input($_POST['cargo']);
    $empresa = limpiar_input($_POST['empresa']);
    $testimonio_texto = limpiar_input($_POST['testimonio']);
    $puntuacion = (int) $_POST['puntuacion'];
    $destacado = isset($_POST['destacado']) ? 1 : 0;
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    // Validaciones
    if (empty($nombre_cliente)) {
        $error = "El nombre del cliente es obligatorio";
    } elseif (empty($testimonio_texto)) {
        $error = "El testimonio es obligatorio";
    } elseif ($puntuacion < 1 || $puntuacion > 5) {
        $error = "La puntuación debe estar entre 1 y 5";
    } else {
        // Gestionar foto del cliente
        $foto_path = $testimonio['foto_cliente'];
        
        if (isset($_FILES['foto_cliente']) && $_FILES['foto_cliente']['error'] === UPLOAD_ERR_OK) {
            $resultado = subir_imagen($_FILES['foto_cliente'], 'testimonios');
            if ($resultado['success']) {
                // Eliminar foto anterior si existe
                if (!empty($testimonio['foto_cliente'])) {
                    eliminar_imagen('../' . $testimonio['foto_cliente']);
                }
                $foto_path = $resultado['path'];
            } else {
                $error = $resultado['message'];
            }
        }
        
        if (empty($error)) {
            $stmt = $mysqli->prepare(
                "UPDATE testimonios SET nombre_cliente = ?, cargo = ?, empresa = ?, testimonio = ?, foto_cliente = ?, puntuacion = ?, destacado = ?, activo = ? WHERE id = ?"
            );
            
            if ($stmt) {
                $stmt->bind_param("sssssilli", $nombre_cliente, $cargo, $empresa, $testimonio_texto, $foto_path, $puntuacion, $destacado, $activo, $testimonio_id);
                
                if ($stmt->execute()) {
                    header("Location: testimonios.php?success=updated");
                    exit();
                } else {
                    $error = "Error al actualizar testimonio: " . $stmt->error;
                }
                
                $stmt->close();
            }
        }
    }
    
    // Recargar datos si hubo error
    $stmt = $mysqli->prepare("SELECT * FROM testimonios WHERE id = ?");
    $stmt->bind_param("i", $testimonio_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $testimonio = $result->fetch_assoc();
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
                    <i class="ti-pencil"></i> Editar Testimonio
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="testimonios.php" class="text-white-50">Testimonios</a></li>
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

                        <!-- Vista previa de foto actual -->
                        <?php if (!empty($testimonio['foto_cliente'])): ?>
                        <div class="text-center mb-4">
                            <img src="../<?php echo htmlspecialchars($testimonio['foto_cliente']); ?>" 
                                 class="rounded-circle" 
                                 style="width: 100px; height: 100px; object-fit: cover;"
                                 alt="Foto actual">
                            <p class="text-muted mt-2">Foto actual</p>
                        </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-user"></i> Nombre del Cliente *</label>
                                        <input type="text" name="nombre_cliente" class="form-control" required
                                               value="<?php echo htmlspecialchars($testimonio['nombre_cliente']); ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-id-badge"></i> Cargo *</label>
                                        <input type="text" name="cargo" class="form-control" required
                                               placeholder="Ej: CEO, Director, Manager"
                                               value="<?php echo htmlspecialchars($testimonio['cargo']); ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-briefcase"></i> Empresa *</label>
                                        <input type="text" name="empresa" class="form-control" required
                                               value="<?php echo htmlspecialchars($testimonio['empresa']); ?>">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-quote-left"></i> Testimonio *</label>
                                        <textarea name="testimonio" class="form-control" rows="6" required><?php echo htmlspecialchars($testimonio['testimonio']); ?></textarea>
                                        <small class="form-text text-muted">Opinión o comentario del cliente</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-star"></i> Puntuación *</label>
                                        <select name="puntuacion" class="form-control" required>
                                            <option value="">Seleccionar...</option>
                                            <option value="5" <?php echo $testimonio['puntuacion'] == 5 ? 'selected' : ''; ?>>
                                                ⭐⭐⭐⭐⭐ (5 estrellas)
                                            </option>
                                            <option value="4" <?php echo $testimonio['puntuacion'] == 4 ? 'selected' : ''; ?>>
                                                ⭐⭐⭐⭐ (4 estrellas)
                                            </option>
                                            <option value="3" <?php echo $testimonio['puntuacion'] == 3 ? 'selected' : ''; ?>>
                                                ⭐⭐⭐ (3 estrellas)
                                            </option>
                                            <option value="2" <?php echo $testimonio['puntuacion'] == 2 ? 'selected' : ''; ?>>
                                                ⭐⭐ (2 estrellas)
                                            </option>
                                            <option value="1" <?php echo $testimonio['puntuacion'] == 1 ? 'selected' : ''; ?>>
                                                ⭐ (1 estrella)
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-image"></i> Cambiar Foto del Cliente</label>
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
                                            <input type="checkbox" class="custom-control-input" id="destacado" name="destacado"
                                                   <?php echo $testimonio['destacado'] ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="destacado">
                                                <i class="ti-star"></i> Marcar como destacado
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="activo" name="activo"
                                                   <?php echo $testimonio['activo'] ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="activo">
                                                <i class="ti-check"></i> Testimonio activo (visible)
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
                                            <i class="ti-calendar"></i> 
                                            Registrado el <?php echo formatear_fecha($testimonio['fecha_testimonio']); ?>
                                        </small>
                                    </p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="testimonios.php" class="btn btn-secondary">
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