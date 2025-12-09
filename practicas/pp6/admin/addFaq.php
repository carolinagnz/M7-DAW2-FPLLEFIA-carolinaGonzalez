<?php
/**
 * ========================================
 * ARCHIVO: admin/addFaq.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Añadir nueva FAQ
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Nueva FAQ - Admin - " . SITE_NAME;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = getDBConnection();
    
    $pregunta = limpiar_input($_POST['pregunta']);
    $respuesta = limpiar_input($_POST['respuesta']);
    $categoria = limpiar_input($_POST['categoria']);
    $orden = (int) $_POST['orden'];
    $activa = isset($_POST['activa']) ? 1 : 0;
    
    // Validaciones
    if (empty($pregunta)) {
        $error = "La pregunta es obligatoria";
    } elseif (empty($respuesta)) {
        $error = "La respuesta es obligatoria";
    } else {
        $stmt = $mysqli->prepare(
            "INSERT INTO faqs (pregunta, respuesta, categoria, orden, activa) 
             VALUES (?, ?, ?, ?, ?)"
        );
        
        if ($stmt) {
            $stmt->bind_param("sssil", $pregunta, $respuesta, $categoria, $orden, $activa);
            
            if ($stmt->execute()) {
                header("Location: faqs.php?success=added");
                exit();
            } else {
                $error = "Error al crear FAQ: " . $stmt->error;
            }
            
            $stmt->close();
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
                    <i class="ti-help"></i> Nueva FAQ
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="faqs.php" class="text-white-50">FAQs</a></li>
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
            <div class="col-lg-8">
                <div class="card border-0 shadow rounded">
                    <div class="card-body p-5">
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="ti-alert"></i> <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-help-alt"></i> Pregunta *</label>
                                        <input type="text" name="pregunta" class="form-control" required
                                               placeholder="¿Cuál es tu pregunta?"
                                               value="<?php echo isset($_POST['pregunta']) ? htmlspecialchars($_POST['pregunta']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="ti-comment-alt"></i> Respuesta *</label>
                                        <textarea name="respuesta" class="form-control" rows="6" required><?php echo isset($_POST['respuesta']) ? htmlspecialchars($_POST['respuesta']) : ''; ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><i class="ti-bookmark"></i> Categoría</label>
                                        <select name="categoria" class="form-control">
                                            <option value="">Sin categoría</option>
                                            <option value="General" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'General') ? 'selected' : ''; ?>>General</option>
                                            <option value="Servicios" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Servicios') ? 'selected' : ''; ?>>Servicios</option>
                                            <option value="Precios" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Precios') ? 'selected' : ''; ?>>Precios</option>
                                            <option value="Técnico" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Técnico') ? 'selected' : ''; ?>>Técnico</option>
                                            <option value="Soporte" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Soporte') ? 'selected' : ''; ?>>Soporte</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="ti-layers"></i> Orden</label>
                                        <input type="number" name="orden" class="form-control" min="0"
                                               value="<?php echo isset($_POST['orden']) ? htmlspecialchars($_POST['orden']) : '0'; ?>">
                                        <small class="form-text text-muted">Menor = mayor prioridad</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="activa" name="activa" checked>
                                            <label class="custom-control-label" for="activa">
                                                <i class="ti-check"></i> FAQ activa (visible)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="text-right">
                                <a href="faqs.php" class="btn btn-secondary">
                                    <i class="ti-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="ti-check"></i> Crear FAQ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>