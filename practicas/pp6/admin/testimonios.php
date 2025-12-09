<?php
/**
 * ========================================
 * ARCHIVO: admin/testimonios.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Listado de testimonios
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Gestión de Testimonios - Admin - " . SITE_NAME;

$mysqli = getDBConnection();

// Obtener todos los testimonios
$testimonios = [];
$result = $mysqli->query("SELECT * FROM testimonios ORDER BY puntuacion DESC, fecha_testimonio DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $testimonios[] = $row;
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
                    <i class="ti-comment"></i> Gestión de Testimonios
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Testimonios</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIOS -->
<section class="section">
    <div class="container">
        
        <!-- MENSAJES -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti-check"></i> 
                <?php
                if ($_GET['success'] == 'added') echo 'Testimonio creado correctamente';
                elseif ($_GET['success'] == 'updated') echo 'Testimonio actualizado correctamente';
                elseif ($_GET['success'] == 'deleted') echo 'Testimonio eliminado correctamente';
                ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-6">
                <h4 style="color: #2C3E50;">
                    <i class="ti-comment"></i> Total de Testimonios: <?php echo count($testimonios); ?>
                </h4>
            </div>
            <div class="col-md-6 text-right">
                <a href="addTestimonio.php" class="btn btn-success">
                    <i class="ti-plus"></i> Nuevo Testimonio
                </a>
            </div>
        </div>

        <div class="card border-0 shadow rounded">
            <div class="card-body p-0">
                <?php if (count($testimonios) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #2C3E50; color: white;">
                            <tr>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Cliente</th>
                                <th>Cargo/Empresa</th>
                                <th>Testimonio</th>
                                <th>Puntuación</th>
                                <th>Fecha</th>
                                <th>Destacado</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($testimonios as $testimonio): ?>
                            <tr>
                                <td><?php echo $testimonio['id']; ?></td>
                                <td>
                                    <?php if (!empty($testimonio['foto_cliente'])): ?>
                                        <img src="../<?php echo htmlspecialchars($testimonio['foto_cliente']); ?>" 
                                             class="rounded-circle" 
                                             style="width: 40px; height: 40px; object-fit: cover;"
                                             alt="<?php echo htmlspecialchars($testimonio['nombre_cliente']); ?>">
                                    <?php else: ?>
                                        <i class="ti-user" style="font-size: 24px; color: #95a5a6;"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($testimonio['nombre_cliente']); ?></strong>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($testimonio['cargo']); ?>
                                    <br>
                                    <small class="text-muted"><?php echo htmlspecialchars($testimonio['empresa']); ?></small>
                                </td>
                                <td><?php echo recortar_texto($testimonio['testimonio'], 80); ?></td>
                                <td>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="ti-star" style="color: <?php echo $i <= $testimonio['puntuacion'] ? '#E67E22' : '#dee2e6'; ?>;"></i>
                                    <?php endfor; ?>
                                    <br>
                                    <small class="text-muted">(<?php echo $testimonio['puntuacion']; ?>/5)</small>
                                </td>
                                <td><?php echo formatear_fecha($testimonio['fecha_testimonio'], 'corto'); ?></td>
                                <td>
                                    <?php if ($testimonio['destacado']): ?>
                                        <span class="badge badge-warning">
                                            <i class="ti-star"></i> Destacado
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($testimonio['activo']): ?>
                                        <span class="badge badge-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="editTestimonio.php?id=<?php echo $testimonio['id']; ?>" 
                                       class="btn btn-sm btn-primary" 
                                       title="Editar">
                                        <i class="ti-pencil"></i>
                                    </a>
                                    <a href="deleteTestimonio.php?id=<?php echo $testimonio['id']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Estás seguro de eliminar este testimonio?')"
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
                    <p class="text-muted mb-0">No hay testimonios registrados</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<?php include '../includes/footer.php'; ?>