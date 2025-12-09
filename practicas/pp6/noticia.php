<?php
/**
 * ========================================
 * ARCHIVO: noticia.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 */

require_once 'includes/config.php';
require_once 'includes/functions.php';

iniciar_sesion_segura();

// Obtener ID de la noticia
$noticia_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($noticia_id <= 0) {
    header('Location: blog.php');
    exit();
}

$mysqli = getDBConnection();

// Obtener la noticia
$stmt = $mysqli->prepare(
    "SELECT n.*, u.nombre as autor_nombre, u.foto as autor_foto 
     FROM noticias n 
     LEFT JOIN users u ON n.autor_id = u.id 
     WHERE n.id = ? AND n.activa = TRUE"
);
$stmt->bind_param("i", $noticia_id);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();
$stmt->close();

if (!$noticia) {
    header('Location: blog.php');
    exit();
}

$page_title = $noticia['titulo'] . " - " . SITE_NAME;

// Obtener comentarios de la noticia
$comentarios = [];
$result = $mysqli->query(
    "SELECT c.*, u.nombre as usuario_nombre, u.foto as usuario_foto 
     FROM comentarios c 
     LEFT JOIN users u ON c.usuario_id = u.id 
     WHERE c.noticia_id = $noticia_id AND c.activo = TRUE 
     ORDER BY c.fecha_comentario DESC"
);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $comentarios[] = $row;
    }
}

closeDBConnection($mysqli);

include 'includes/header.php';
?>

<!-- PAGE TITLE -->
<section class="page-title bg-cover" style="background-image: url('images/backgrounds/page-title.jpg'); padding: 100px 0;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="text-white font-weight-bold">Blog</h1>
            </div>
        </div>
    </div>
</section>

<!-- NOTICIA -->
<section class="section">
    <div class="container">
        <div class="row">
            <!-- CONTENIDO PRINCIPAL -->
            <div class="col-lg-8">
                <div class="card border-0 shadow rounded overflow-hidden">
                    <?php if (!empty($noticia['imagen_destacada'])): ?>
                        <img src="<?php echo htmlspecialchars($noticia['imagen_destacada']); ?>" 
                             class="img-fluid" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>"
                             style="width: 100%; max-height: 400px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div class="card-body p-5">
                        <?php if (!empty($noticia['categoria'])): ?>
                            <span class="badge badge-primary mb-3" style="background: #E67E22; border: none; font-size: 14px;">
                                <?php echo htmlspecialchars($noticia['categoria']); ?>
                            </span>
                        <?php endif; ?>
                        
                        <h1 class="mb-3" style="color: #2C3E50;">
                            <?php echo htmlspecialchars($noticia['titulo']); ?>
                        </h1>
                        
                        <?php if (!empty($noticia['subtitulo'])): ?>
                            <h5 class="text-muted mb-4">
                                <em><?php echo htmlspecialchars($noticia['subtitulo']); ?></em>
                            </h5>
                        <?php endif; ?>
                        
                        <div class="post-meta mb-4 pb-4 border-bottom">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item mr-3">
                                    <?php if (!empty($noticia['autor_foto'])): ?>
                                        <img src="<?php echo htmlspecialchars($noticia['autor_foto']); ?>" 
                                             class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;">
                                    <?php endif; ?>
                                    <i class="ti-user"></i> 
                                    <?php echo htmlspecialchars($noticia['autor_nombre'] ?? 'Admin'); ?>
                                </li>
                                <li class="list-inline-item mr-3">
                                    <i class="ti-calendar"></i> 
                                    <?php echo formatear_fecha($noticia['fecha_publicacion']); ?>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="content" style="font-size: 16px; line-height: 1.8;">
                            <?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?>
                        </div>
                    </div>
                </div>

                <!-- COMENTARIOS -->
                <div class="card border-0 shadow rounded mt-4 p-4">
                    <h4 class="mb-4" style="color: #2C3E50;">
                        <i class="ti-comments"></i> Comentarios (<?php echo count($comentarios); ?>)
                    </h4>

                    <?php if (count($comentarios) > 0): ?>
                        <?php foreach ($comentarios as $comentario): ?>
                        <div class="media mb-4 pb-4 border-bottom">
                            <?php if (!empty($comentario['usuario_foto'])): ?>
                                <img src="<?php echo htmlspecialchars($comentario['usuario_foto']); ?>" 
                                     class="mr-3 rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                <img src="images/user-1.jpg" class="mr-3 rounded-circle" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            <?php endif; ?>
                            
                            <div class="media-body">
                                <h6 class="mt-0 mb-1" style="color: #2C3E50;">
                                    <?php echo htmlspecialchars($comentario['usuario_nombre']); ?>
                                </h6>
                                <small class="text-muted">
                                    <?php echo formatear_fecha($comentario['fecha_comentario']); ?>
                                </small>
                                <p class="mt-2 mb-0">
                                    <?php echo nl2br(htmlspecialchars($comentario['contenido'])); ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No hay comentarios aún. ¡Sé el primero en comentar!</p>
                    <?php endif; ?>

                    <?php if (esta_logueado()): ?>
                        <div class="alert alert-info">
                            <i class="ti-info"></i> El sistema de comentarios estará disponible próximamente.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="ti-lock"></i> 
                            <a href="login.php" style="color: #E67E22;">Inicia sesión</a> 
                            para dejar un comentario.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4">
                <div class="card border-0 shadow rounded p-4 mb-4">
                    <h5 class="mb-3" style="color: #2C3E50;">Sobre el Autor</h5>
                    <div class="text-center">
                        <?php if (!empty($noticia['autor_foto'])): ?>
                            <img src="<?php echo htmlspecialchars($noticia['autor_foto']); ?>" 
                                 class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php endif; ?>
                        <h6 style="color: #2C3E50;">
                            <?php echo htmlspecialchars($noticia['autor_nombre'] ?? 'Administrador'); ?>
                        </h6>
                    </div>
                </div>

                <div class="card border-0 shadow rounded p-4">
                    <h5 class="mb-3" style="color: #2C3E50;">Compartir</h5>
                    <div class="d-flex">
                        <a href="#" class="btn btn-sm btn-primary mr-2" style="background: #3b5998; border: none;">
                            <i class="ti-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-info mr-2" style="background: #1da1f2; border: none;">
                            <i class="ti-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-danger" style="background: #0077b5; border: none;">
                            <i class="ti-linkedin"></i>
                        </a>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="blog.php" class="btn btn-outline-primary btn-block">
                        <i class="ti-arrow-left"></i> Volver al Blog
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>