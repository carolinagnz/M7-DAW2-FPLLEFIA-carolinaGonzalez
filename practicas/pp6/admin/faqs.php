<?php
/**
 * ========================================
 * ARCHIVO: admin/faqs.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Listado de FAQs (Preguntas Frecuentes)
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Gestión de FAQs - Admin - " . SITE_NAME;

$mysqli = getDBConnection();

// Obtener todas las FAQs
$faqs = [];
$result = $mysqli->query("SELECT * FROM faqs ORDER BY orden ASC, id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
}

closeDBConnection($mysqli);

include '../includes/header.php';
?>

<!-- PAGE TITLE -->
<section class="page-title bg-cover" style="background: linear-gradient(135deg, #2C3E50 0%, #34495e 100%); padding: 60px 0;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-white font-weight-bold">
                    <i class="ti-help"></i> Gestión de FAQs
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">FAQs</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- FAQs -->
<section class="section">
    <div class="container">
        
        <!-- MENSAJES -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti-check"></i> 
                <?php
                if ($_GET['success'] == 'added') echo 'FAQ creada correctamente';
                elseif ($_GET['success'] == 'updated') echo 'FAQ actualizada correctamente';
                elseif ($_GET['success'] == 'deleted') echo 'FAQ eliminada correctamente';
                ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-6">
                <h4 style="color: #2C3E50;">
                    <i class="ti-help"></i> Total de FAQs: <?php echo count($faqs); ?>
                </h4>
            </div>
            <div class="col-md-6 text-right">
                <a href="addFaq.php" class="btn btn-success">
                    <i class="ti-plus"></i> Nueva FAQ
                </a>
            </div>
        </div>

        <div class="card border-0 shadow rounded">
            <div class="card-body p-0">
                <?php if (count($faqs) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #2C3E50; color: white;">
                            <tr>
                                <th>ID</th>
                                <th>Pregunta</th>
                                <th>Respuesta</th>
                                <th>Categoría</th>
                                <th>Orden</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($faqs as $faq): ?>
                            <tr>
                                <td><?php echo $faq['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($faq['pregunta']); ?></strong>
                                </td>
                                <td><?php echo recortar_texto($faq['respuesta'], 100); ?></td>
                                <td>
                                    <?php if (!empty($faq['categoria'])): ?>
                                        <span class="badge badge-info">
                                            <?php echo htmlspecialchars($faq['categoria']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?php echo $faq['orden']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($faq['activa']): ?>
                                        <span class="badge badge-success">Activa</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactiva</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="editFaq.php?id=<?php echo $faq['id']; ?>" 
                                       class="btn btn-sm btn-primary" 
                                       title="Editar">
                                        <i class="ti-pencil"></i>
                                    </a>
                                    <a href="deleteFaq.php?id=<?php echo $faq['id']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Estás seguro de eliminar esta FAQ?')"
                                       title="Eliminar">
                                        <i class="ti-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="p-4 text-center">
                    <p class="text-muted mb-0">No hay FAQs registradas</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<?php include '../includes/footer.php'; ?>