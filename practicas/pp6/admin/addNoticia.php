<?php
/**
 * ========================================
 * ARCHIVO: admin/addNoticia.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Añadir nueva noticia
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Nueva Noticia - Admin - " . SITE_NAME;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = getDBConnection();
    
    $titulo = limpiar_input($_POST['titulo']);
    $subtitulo = limpiar_input($_POST['subtitulo']);
    $contenido = limpiar_input($_POST['contenido']);
    $categoria = limpiar_input($_POST['categoria']);
    $autor_id = $_SESSION['user_id']; // El autor es el usuario logueado
    $destacada = isset($_POST['destacada']) ? 1 : 0;
    $activa = isset($_POST['activa']) ? 1 : 0;
    
    // Validaciones
    if (empty($titulo)) {
        $error = "El título es obligatorio";
    } elseif (empty($contenido)) {
        $error = "El contenido es obligatorio";
    } else {
        // Gestionar imagen destacada
        $imagen_path = '';
        
        if (isset($_FILES['imagen_destacada']) && $_FILES['imagen_destacada']['error'] === UPLOAD_ERR_OK) {
            $resultado = subir_imagen($_FILES['imagen_destacada'], 'noticias');
            if ($resultado['success']) {
                $imagen_path = $resultado['path'];
            } else {
                $error = $resultado['message'];
            }
        }
        
        if (empty($error)) {
            $stmt = $mysqli->prepare(
                "INSERT INTO noticias (titulo, subtitulo, contenido, imagen_destacada, autor_id, categoria, destacada, activa, fecha_publicacion, fecha_actualizacion) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
            );
            
            if ($stmt) {
                $stmt->bind_param("ssssisii", $titulo, $subtitulo, $contenido, $imagen_path, $autor_id, $categoria, $destacada, $activa);
                
                if ($stmt->execute()) {
                    header("Location: noticias.php?success=added");
                    exit();
                } else {
                    $error = "Error al crear noticia: " . $stmt->error;
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
                    <i class="ti-write"></i> Nueva Noticia
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="noticias.php" class="text-white-50">Noticias</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Nueva</li>
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
                                        <label><i class="ti-text"></i> Título *</label>
                                        <input type="text" name="titulo" class="form-control" required
                                               value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="ti-bookmark"></i> Categoría</label>
                                        <select name="categoria" class="form-control">
                                            <option value="">Sin categoría</option>
                                            <option value="Tecnología" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Tecnología') ? 'selected' : ''; ?>>Tecnología</option>
                                            <option value="Diseño" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Diseño') ? 'selected' : ''; ?>>Diseño</option>
                                            <option value="Programación" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Programación') ? 'selected' : ''; ?>>Programación</option>
                                            <option value="Seguridad" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Seguridad') ? 'selected' : ''; ?>>Seguridad</option>
                                            <option value="Marketing" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                                            <option value="Negocios" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Negocios') ? 'selected' : ''; ?>>Negocios</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-quote-left"></i> Subtítulo</label>
                                        <input type="text" name="subtitulo" class="form-control"
                                               value="<?php echo isset($_POST['subtitulo']) ? htmlspecialchars($_POST['subtitulo']) : ''; ?>">
                                        <small class="form-text text-muted">Resumen breve de la noticia</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-align-left"></i> Contenido *</label>
                                        <textarea name="contenido" class="form-control" rows="12" required><?php echo isset($_POST['contenido']) ? htmlspecialchars($_POST['contenido']) : ''; ?></textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-image"></i> Imagen Destacada</label>
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
                                                   <?php echo (isset($_POST['destacada'])) ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="destacada">
                                                <i class="ti-star"></i> Marcar como destacada
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="activa" name="activa" checked>
                                            <label class="custom-control-label" for="activa">
                                                <i class="ti-check"></i> Publicar (activa)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="text-right">
                                <a href="noticias.php" class="btn btn-secondary">
                                    <i class="ti-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="ti-check"></i> Crear Noticia
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