<?php
/**
 * ========================================
 * ARCHIVO: admin/proyectos.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Listado de proyectos
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Gestión de Proyectos - Admin - " . SITE_NAME;

$mysqli = getDBConnection();

// Obtener todos los proyectos
$proyectos = [];
$result = $mysqli->query("SELECT * FROM proyectos ORDER BY orden_visualizacion ASC, fecha_inicio DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $proyectos[] = $row;
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
                    <i class="ti-briefcase"></i> Gestión de Proyectos
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Proyectos</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- PROYECTOS -->
<section class="section">
    <div class="container">
        
        <!-- MENSAJES -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti-check"></i> 
                <?php
                if ($_GET['success'] == 'added') echo 'Proyecto creado correctamente';
                elseif ($_GET['success'] == 'updated') echo 'Proyecto actualizado correctamente';
                elseif ($_GET['success'] == 'deleted') echo 'Proyecto eliminado correctamente';
                ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-6">
                <h4 style="color: #2C3E50;">
                    <i class="ti-briefcase"></i> Total de Proyectos: <?php echo count($proyectos); ?>
                </h4>
            </div>
            <div class="col-md-6 text-right">
                <a href="addProyecto.php" class="btn btn-success">
                    <i class="ti-plus"></i> Nuevo Proyecto
                </a>
            </div>
        </div>

        <div class="card border-0 shadow rounded">
            <div class="card-body p-0">
                <?php if (count($proyectos) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #2C3E50; color: white;">
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Título</th>
                                <th>Cliente</th>
                                <th>Categoría</th>
                                <th>Tecnologías</th>
                                <th>Fecha Inicio</th>
                                <th>Destacado</th>
                                <th>Orden</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proyectos as $proyecto): ?>
                            <tr>
                                <td><?php echo $proyecto['id']; ?></td>
                                <td>
                                    <?php if (!empty($proyecto['imagen_principal'])): ?>
                                        <img src="../<?php echo htmlspecialchars($proyecto['imagen_principal']); ?>" 
                                             class="rounded" 
                                             style="width: 60px; height: 40px; object-fit: cover;"
                                             alt="<?php echo htmlspecialchars($proyecto['titulo']); ?>">
                                    <?php else: ?>
                                        <i class="ti-image" style="font-size: 24px; color: #95a5a6;"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo recortar_texto($proyecto['titulo'], 40); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($proyecto['cliente'] ?: '-'); ?></td>
                                <td>
                                    <?php if (!empty($proyecto['categoria'])): ?>
                                        <span class="badge badge-primary" style="background: #E67E22; border: none;">
                                            <?php echo htmlspecialchars($proyecto['categoria']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo recortar_texto($proyecto['tecnologias'], 30) ?: '-'; ?></td>
                                <td>
                                    <?php echo $proyecto['fecha_inicio'] ? formatear_fecha($proyecto['fecha_inicio'], 'corto') : '-'; ?>
                                </td>
                                <td>
                                    <?php if ($proyecto['destacado']): ?>
                                        <span class="badge badge-warning">
                                            <i class="ti-star"></i> Destacado
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?php echo $proyecto['orden_visualizacion']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($proyecto['activo']): ?>
                                        <span class="badge badge-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="../proyecto.php?id=<?php echo $proyecto['id']; ?>" 
                                       class="btn btn-sm btn-info" 
                                       target="_blank"
                                       title="Ver">
                                        <i class="ti-eye"></i>
                                    </a>
                                    <a href="editProyecto.php?id=<?php echo $proyecto['id']; ?>" 
                                       class="btn btn-sm btn-primary" 
                                       title="Editar">
                                        <i class="ti-pencil"></i>
                                    </a>
                                    <a href="deleteProyecto.php?id=<?php echo $proyecto['id']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Estás seguro de eliminar este proyecto?')"
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
                    <p class="text-muted mb-0">No hay proyectos creados</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<?php include '../includes/footer.php'; ?>