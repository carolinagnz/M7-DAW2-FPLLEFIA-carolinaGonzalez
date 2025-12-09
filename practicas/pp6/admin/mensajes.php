<?php
/**
 * ========================================
 * ARCHIVO: admin/mensajes.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Visualización de mensajes de contacto
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Mensajes de Contacto - Admin - " . SITE_NAME;

$mysqli = getDBConnection();

// Marcar mensaje como leído si se recibe ID
if (isset($_GET['marcar_leido'])) {
    $mensaje_id = (int)$_GET['marcar_leido'];
    $stmt = $mysqli->prepare("UPDATE mensajes_contacto SET leido = TRUE WHERE id = ?");
    $stmt->bind_param("i", $mensaje_id);
    $stmt->execute();
    $stmt->close();
    header('Location: mensajes.php?success=marked');
    exit();
}

// Eliminar mensaje si se recibe ID
if (isset($_GET['eliminar'])) {
    $mensaje_id = (int)$_GET['eliminar'];
    $stmt = $mysqli->prepare("DELETE FROM mensajes_contacto WHERE id = ?");
    $stmt->bind_param("i", $mensaje_id);
    $stmt->execute();
    $stmt->close();
    header('Location: mensajes.php?success=deleted');
    exit();
}

// Obtener todos los mensajes
$mensajes = [];
$result = $mysqli->query("SELECT * FROM mensajes_contacto ORDER BY fecha_envio DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $mensajes[] = $row;
    }
}

// Contar mensajes sin leer
$result = $mysqli->query("SELECT COUNT(*) as total FROM mensajes_contacto WHERE leido = FALSE");
$mensajes_sin_leer = $result->fetch_assoc()['total'];

closeDBConnection($mysqli);

include '../includes/header.php';
?>

<!-- PAGE TITLE -->
<section class="page-title bg-cover" style="background: linear-gradient(135deg, #2C3E50 0%, #34495e 100%); padding: 60px 0;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-white font-weight-bold">
                    <i class="ti-email"></i> Mensajes de Contacto
                    <?php if ($mensajes_sin_leer > 0): ?>
                        <span class="badge badge-danger ml-2"><?php echo $mensajes_sin_leer; ?> sin leer</span>
                    <?php endif; ?>
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Mensajes</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- MENSAJES -->
<section class="section">
    <div class="container">
        
        <!-- MENSAJES DE ÉXITO -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti-check"></i> 
                <?php
                if ($_GET['success'] == 'marked') echo 'Mensaje marcado como leído';
                elseif ($_GET['success'] == 'deleted') echo 'Mensaje eliminado correctamente';
                ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-6">
                <h4 style="color: #2C3E50;">
                    <i class="ti-email"></i> Total de Mensajes: <?php echo count($mensajes); ?>
                </h4>
            </div>
            <div class="col-md-6 text-right">
                <span class="badge badge-warning p-2" style="font-size: 14px;">
                    <i class="ti-alert"></i> <?php echo $mensajes_sin_leer; ?> sin leer
                </span>
            </div>
        </div>

        <?php if (count($mensajes) > 0): ?>
            
            <!-- VISTA EN CARDS (Más visual) -->
            <div class="row">
                <?php foreach ($mensajes as $mensaje): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm rounded <?php echo !$mensaje['leido'] ? 'border-left border-warning' : ''; ?>" 
                         style="<?php echo !$mensaje['leido'] ? 'border-left: 4px solid #f39c12 !important;' : ''; ?>">
                        <div class="card-body p-4">
                            
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-1" style="color: #2C3E50;">
                                        <i class="ti-user"></i> <?php echo htmlspecialchars($mensaje['nombre']); ?>
                                        <?php if (!$mensaje['leido']): ?>
                                            <span class="badge badge-warning ml-2">Nuevo</span>
                                        <?php endif; ?>
                                    </h5>
                                    <small class="text-muted">
                                        <i class="ti-email"></i> <?php echo htmlspecialchars($mensaje['email']); ?>
                                    </small>
                                </div>
                                <small class="text-muted">
                                    <i class="ti-calendar"></i> <?php echo formatear_fecha($mensaje['fecha_envio']); ?>
                                </small>
                            </div>

                            <!-- Asunto -->
                            <div class="mb-3">
                                <strong style="color: #2C3E50;">
                                    <i class="ti-bookmark"></i> Asunto:
                                </strong>
                                <p class="mb-0"><?php echo htmlspecialchars($mensaje['asunto']); ?></p>
                            </div>

                            <!-- Mensaje -->
                            <div class="mb-3">
                                <strong style="color: #2C3E50;">
                                    <i class="ti-comment"></i> Mensaje:
                                </strong>
                                <div class="p-3 bg-light rounded mt-2">
                                    <?php echo nl2br(htmlspecialchars($mensaje['mensaje'])); ?>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <a href="mailto:<?php echo htmlspecialchars($mensaje['email']); ?>?subject=Re: <?php echo urlencode($mensaje['asunto']); ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="ti-email"></i> Responder
                                    </a>
                                </div>
                                <div>
                                    <?php if (!$mensaje['leido']): ?>
                                    <a href="mensajes.php?marcar_leido=<?php echo $mensaje['id']; ?>" 
                                       class="btn btn-sm btn-success"
                                       title="Marcar como leído">
                                        <i class="ti-check"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="mensajes.php?eliminar=<?php echo $mensaje['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Estás seguro de eliminar este mensaje?')"
                                       title="Eliminar">
                                        <i class="ti-trash"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            
            <!-- Sin mensajes -->
            <div class="card border-0 shadow rounded">
                <div class="card-body p-5 text-center">
                    <i class="ti-email" style="font-size: 64px; color: #95a5a6;"></i>
                    <h4 class="mt-4" style="color: #2C3E50;">No hay mensajes</h4>
                    <p class="text-muted">Cuando los usuarios envíen mensajes desde el formulario de contacto, aparecerán aquí.</p>
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php include '../includes/footer.php'; ?>