<?php
/**
 * ========================================
 * ARCHIVO: admin/noticias.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Listado de noticias
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Gestión de Noticias - Admin - " . SITE_NAME;

$mysqli = getDBConnection();

// Obtener todas las noticias con autor
$noticias = [];
$result = $mysqli->query(
    "SELECT n.*, u.nombre as autor_nombre 
     FROM noticias n 
     LEFT JOIN users u ON n.autor_id = u.id 
     ORDER BY n.fecha_publicacion DESC"
);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $noticias[] = $row;
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
                    <i class="ti-write"></i> Gestión de Noticias
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Noticias</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- NOTICIAS -->
<section class="section">
    <div class="container">
        
        <!-- MENSAJES -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti-check"></i> 
                <?php
                if ($_GET['success'] == 'added') echo 'Noticia creada correctamente';
                elseif ($_GET['success'] == 'updated') echo 'Noticia actualizada correctamente';
                elseif ($_GET['success'] == 'deleted') echo 'Noticia eliminada correctamente';
                ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-6">
                <h4 style="color: #2C3E50;">
                    <i class="ti-write"></i> Total de Noticias: <?php echo count($noticias); ?>
                </h4>
            </div>
            <div class="col-md-6 text-right">
                <a href="addNoticia.php" class="btn btn-success">
                    <i class="ti-plus"></i> Nueva Noticia
                </a>
            </div>
        </div>

        <div class="card border-0 shadow rounded">
            <div class="card-body p-0">
                <?php if (count($noticias) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #2C3E50; color: white;">
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Título</th>
                                <th>Categoría</th>
                                <th>Autor</th>
                                <th>Fecha Publicación</th>
                                <th>Destacada</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($noticias as $noticia): ?>
                            <tr>
                                <td><?php echo $noticia['id']; ?></td>
                                <td>
                                    <?php if (!empty($noticia['imagen_destacada'])): ?>
                                        <img src="../<?php echo htmlspecialchars($noticia['imagen_destacada']); ?>" 
                                             class="rounded" 
                                             style="width: 60px; height: 40px; object-fit: cover;"
                                             alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                                    <?php else: ?>
                                        <i class="ti-image" style="font-size: 24px; color: #95a5a6;"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo recortar_texto($noticia['titulo'], 50); ?></strong>
                                    <?php if (!empty($noticia['subtitulo'])): ?>
                                        <br><small class="text-muted"><?php echo recortar_texto($noticia['subtitulo'], 40); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($noticia['categoria'])): ?>
                                        <span class="badge badge-primary" style="background: #E67E22; border: none;">
                                            <?php echo htmlspecialchars($noticia['categoria']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($noticia['autor_nombre'] ?? 'Desconocido'); ?></td>
                                <td><?php echo formatear_fecha($noticia['fecha_publicacion'], 'corto'); ?></td>
                                <td>
                                    <?php if ($noticia['destacada']): ?>
                                        <span class="badge badge-warning">
                                            <i class="ti-star"></i> Destacada
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($noticia['activa']): ?>
                                        <span class="badge badge-success">Activa</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactiva</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="../noticia.php?id=<?php echo $noticia['id']; ?>" 
                                       class="btn btn-sm btn-info" 
                                       target="_blank"
                                       title="Ver">
                                        <i class="ti-eye"></i>
                                    </a>
                                    <a href="editNoticia.php?id=<?php echo $noticia['id']; ?>" 
                                       class="btn btn-sm btn-primary" 
                                       title="Editar">
                                        <i class="ti-pencil"></i>
                                    </a>
                                    <a href="deleteNoticia.php?id=<?php echo $noticia['id']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Estás seguro de eliminar esta noticia?')"
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
                    <p class="text-muted mb-0">No hay noticias creadas</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<?php include '../includes/footer.php'; ?>