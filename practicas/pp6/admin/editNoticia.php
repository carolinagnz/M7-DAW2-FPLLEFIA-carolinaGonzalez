<?php
/**
 * ========================================
 * ARCHIVO: admin/editNoticia.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Editar noticia existente
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Editar Noticia - Admin - " . SITE_NAME;

// Obtener ID de la noticia
$noticia_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($noticia_id <= 0) {
    header('Location: noticias.php');
    exit();
}

$mysqli = getDBConnection();

// Obtener datos de la noticia
$stmt = $mysqli->prepare("SELECT * FROM noticias WHERE id = ?");
$stmt->bind_param("i", $noticia_id);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();
$stmt->close();

if (!$noticia) {
    header('Location: noticias.php');
    exit();
}

$error = '';
$success = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = limpiar_input($_POST['titulo']);
    $subtitulo = limpiar_input($_POST['subtitulo']);
    $contenido = limpiar_input($_POST['contenido']);
    $categoria = limpiar_input($_POST['categoria']);
    $destacada = isset($_POST['destacada']) ? 1 : 0;
    $activa = isset($_POST['activa']) ? 1 : 0;
    
    // Validaciones
    if (empty($titulo)) {
        $error = "El título es obligatorio";
    } elseif (empty($contenido)) {
        $error = "El contenido es obligatorio";
    } else {
        // Gestionar imagen destacada
        $imagen_path = $noticia['imagen_destacada'];
        
        if (isset($_FILES['imagen_destacada']) && $_FILES['imagen_destacada']['error'] === UPLOAD_ERR_OK) {
            $resultado = subir_imagen($_FILES['imagen_destacada'], 'noticias');
            if ($resultado['success']) {
                // Eliminar imagen anterior si existe
                if (!empty($noticia['imagen_destacada'])) {
                    eliminar_imagen('../' . $noticia['imagen_destacada']);
                }
                $imagen_path = $resultado['path'];
            } else {
                $error = $resultado['message'];
            }
        }
        
        if (empty($error)) {
            $stmt = $mysqli->prepare(
                "UPDATE noticias SET titulo = ?, subtitulo = ?, contenido = ?, imagen_destacada = ?, categoria = ?, destacada = ?, activa = ?, fecha_actualizacion = NOW() WHERE id = ?"
            );
            
            if ($stmt) {
                $stmt->bind_param("sssssiii", $titulo, $subtitulo, $contenido, $imagen_path, $categoria, $destacada, $activa, $noticia_id);
                
                if ($stmt->execute()) {
                    header("Location: noticias.php?success=updated");
                    exit();
                } else {
                    $error = "Error al actualizar noticia: " . $stmt->error;
                }
                
                $stmt->close();
            }
        }
    }
    
    // Recargar datos si hubo error
    $stmt = $mysqli->prepare("SELECT * FROM noticias WHERE id = ?");
    $stmt->bind_param("i", $noticia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $noticia = $result->fetch_assoc();
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
                    <i class="ti-pencil"></i> Editar Noticia
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="noticias.php" class="text-white-50">Noticias</a></li>
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
            <div class="col-lg-10">
                <div class="card border-0 shadow rounded">
                    <div class="card-body p-5">
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="ti-alert"></i> <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php endif; ?>

                        <!-- Vista previa de imagen actual -->
                        <?php if (!empty($noticia['imagen_destacada'])): ?>
                        <div class="text-center mb-4">
                            <img src="../<?php echo htmlspecialchars($noticia['imagen_destacada']); ?>" 
                                 class="img-fluid rounded" 
                                 style="max-height: 300px; object-fit: cover;"
                                 alt="Imagen actual">
                            <p class="text-muted mt-2">Imagen actual</p>
                        </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><i class="ti-text"></i> Título *</label>
                                        <input type="text" name="titulo" class="form-control" required
                                               value="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="ti-bookmark"></i> Categoría</label>
                                        <select name="categoria" class="form-control">
                                            <option value="">Sin categoría</option>
                                            <option value="Tecnología" <?php echo $noticia['categoria'] == 'Tecnología' ? 'selected' : ''; ?>>Tecnología</option>
                                            <option value="Diseño" <?php echo $noticia['categoria'] == 'Diseño' ? 'selected' : ''; ?>>Diseño</option>
                                            <option value="Programación" <?php echo $noticia['categoria'] == 'Programación' ? 'selected' : ''; ?>>Programación</option>
                                            <option value="Seguridad" <?php echo $noticia['categoria'] == 'Seguridad' ? 'selected' : ''; ?>>Seguridad</option>
                                            <option value="Marketing" <?php echo $noticia['categoria'] == 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                                            <option value="Negocios" <?php echo $noticia['categoria'] == 'Negocios' ? 'selected' : ''; ?>>Negocios</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-quote-left"></i> Subtítulo</label>
                                        <input type="text" name="subtitulo" class="form-control"
                                               value="<?php echo htmlspecialchars($noticia['subtitulo']); ?>">
                                        <small class="form-text text-muted">Resumen breve de la noticia</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-align-left"></i> Contenido *</label>
                                        <textarea name="contenido" class="form-control" rows="12" required><?php echo htmlspecialchars($noticia['contenido']); ?></textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-image"></i> Cambiar Imagen Destacada</label>
                                        <div class="custom-file">
                                            <input type="file" name="imagen_destacada" class="custom-file-input" id="imagen" accept="image/*">
                                            <label class="custom-file-label" for="imagen">Elegir archivo...</label>
                                        </div>
                                        <small class="form-text text-muted">Opcional. JPG, PNG, GIF (máx 5MB)</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="destacada" name="destacada"
                                                   <?php echo $noticia['destacada'] ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="destacada">
                                                <i class="ti-star"></i> Marcar como destacada
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="activa" name="activa"
                                                   <?php echo $noticia['activa'] ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="activa">
                                                <i class="ti-check"></i> Publicar (activa)
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
                                            Publicado el <?php echo formatear_fecha($noticia['fecha_publicacion']); ?>
                                        </small>
                                        <?php if ($noticia['fecha_actualizacion'] && $noticia['fecha_actualizacion'] != $noticia['fecha_publicacion']): ?>
                                        <br>
                                        <small>
                                            <i class="ti-reload"></i> 
                                            Actualizado el <?php echo formatear_fecha($noticia['fecha_actualizacion']); ?>
                                        </small>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="noticias.php" class="btn btn-secondary">
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