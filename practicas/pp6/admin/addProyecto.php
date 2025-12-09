<?php
/**
 * ========================================
 * ARCHIVO: admin/addProyecto.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Añadir nuevo proyecto
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Nuevo Proyecto - Admin - " . SITE_NAME;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = getDBConnection();
    
    $titulo = limpiar_input($_POST['titulo']);
    $descripcion = limpiar_input($_POST['descripcion']);
    $cliente = limpiar_input($_POST['cliente']);
    $categoria = limpiar_input($_POST['categoria']);
    $tecnologias = limpiar_input($_POST['tecnologias']);
    $url_proyecto = limpiar_input($_POST['url_proyecto']);
    $fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : NULL;
    $fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : NULL;
    $orden_visualizacion = (int) $_POST['orden_visualizacion'];
    $destacado = isset($_POST['destacado']) ? 1 : 0;
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    // Validaciones
    if (empty($titulo)) {
        $error = "El título es obligatorio";
    } elseif (empty($descripcion)) {
        $error = "La descripción es obligatoria";
    } else {
        // Gestionar imagen principal
        $imagen_path = '';
        
        if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === UPLOAD_ERR_OK) {
            $resultado = subir_imagen($_FILES['imagen_principal'], 'proyectos');
            if ($resultado['success']) {
                $imagen_path = $resultado['path'];
            } else {
                $error = $resultado['message'];
            }
        }
        
        if (empty($error)) {
            $stmt = $mysqli->prepare(
                "INSERT INTO proyectos (titulo, descripcion, imagen_principal, cliente, categoria, tecnologias, url_proyecto, fecha_inicio, fecha_fin, orden_visualizacion, destacado, activo) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            
            if ($stmt) {
                $stmt->bind_param("sssssssssiil", $titulo, $descripcion, $imagen_path, $cliente, $categoria, $tecnologias, $url_proyecto, $fecha_inicio, $fecha_fin, $orden_visualizacion, $destacado, $activo);
                
                if ($stmt->execute()) {
                    header("Location: proyectos.php?success=added");
                    exit();
                } else {
                    $error = "Error al crear proyecto: " . $stmt->error;
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
                    <i class="ti-briefcase"></i> Nuevo Proyecto
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="proyectos.php" class="text-white-50">Proyectos</a></li>
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
            <div class="col-lg-10">
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
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><i class="ti-text"></i> Título del Proyecto *</label>
                                        <input type="text" name="titulo" class="form-control" required
                                               value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="ti-bookmark"></i> Categoría</label>
                                        <select name="categoria" class="form-control">
                                            <option value="">Sin categoría</option>
                                            <option value="Web Development" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Web Development') ? 'selected' : ''; ?>>Web Development</option>
                                            <option value="Mobile App" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Mobile App') ? 'selected' : ''; ?>>Mobile App</option>
                                            <option value="E-commerce" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'E-commerce') ? 'selected' : ''; ?>>E-commerce</option>
                                            <option value="Software" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Software') ? 'selected' : ''; ?>>Software</option>
                                            <option value="Design" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Design') ? 'selected' : ''; ?>>Design</option>
                                            <option value="Branding" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Branding') ? 'selected' : ''; ?>>Branding</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-align-left"></i> Descripción *</label>
                                        <textarea name="descripcion" class="form-control" rows="8" required><?php echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-user"></i> Cliente</label>
                                        <input type="text" name="cliente" class="form-control"
                                               value="<?php echo isset($_POST['cliente']) ? htmlspecialchars($_POST['cliente']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="ti-settings"></i> Tecnologías Utilizadas</label>
                                        <input type="text" name="tecnologias" class="form-control"
                                               placeholder="Ej: PHP, MySQL, JavaScript"
                                               value="<?php echo isset($_POST['tecnologias']) ? htmlspecialchars($_POST['tecnologias']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-link"></i> URL del Proyecto</label>
                                        <input type="url" name="url_proyecto" class="form-control"
                                               placeholder="https://ejemplo.com"
                                               value="<?php echo isset($_POST['url_proyecto']) ? htmlspecialchars($_POST['url_proyecto']) : ''; ?>">
                                        <small class="form-text text-muted">URL completa del proyecto en producción</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="ti-calendar"></i> Fecha de Inicio</label>
                                        <input type="date" name="fecha_inicio" class="form-control"
                                               value="<?php echo isset($_POST['fecha_inicio']) ? htmlspecialchars($_POST['fecha_inicio']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="ti-check-box"></i> Fecha de Finalización</label>
                                        <input type="date" name="fecha_fin" class="form-control"
                                               value="<?php echo isset($_POST['fecha_fin']) ? htmlspecialchars($_POST['fecha_fin']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="ti-layers"></i> Orden de Visualización</label>
                                        <input type="number" name="orden_visualizacion" class="form-control" min="0"
                                               value="<?php echo isset($_POST['orden_visualizacion']) ? htmlspecialchars($_POST['orden_visualizacion']) : '0'; ?>">
                                        <small class="form-text text-muted">Menor número = mayor prioridad</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-image"></i> Imagen Principal</label>
                                        <div class="custom-file">
                                            <input type="file" name="imagen_principal" class="custom-file-input" id="imagen" accept="image/*">
                                            <label class="custom-file-label" for="imagen">Elegir archivo...</label>
                                        </div>
                                        <small class="form-text text-muted">Opcional. JPG, PNG, GIF (máx 5MB)</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="destacado" name="destacado"
                                                   <?php echo (isset($_POST['destacado'])) ? 'checked' : ''; ?>>
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
                                                <i class="ti-check"></i> Proyecto activo (visible)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="text-right">
                                <a href="proyectos.php" class="btn btn-secondary">
                                    <i class="ti-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="ti-check"></i> Crear Proyecto
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