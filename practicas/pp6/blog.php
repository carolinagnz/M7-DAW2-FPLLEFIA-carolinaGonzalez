<!DOCTYPE html>

<<?php
/**
 * ========================================
 * ARCHIVO: blog.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 */

require_once 'includes/config.php';
require_once 'includes/functions.php';

iniciar_sesion_segura();

$page_title = "Blog - " . SITE_NAME;

// Paginación
$noticias_por_pagina = 6;
$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($pagina_actual - 1) * $noticias_por_pagina;

// Obtener noticias de la BD
$mysqli = getDBConnection();

// Contar total de noticias
$result = $mysqli->query("SELECT COUNT(*) as total FROM noticias WHERE activa = TRUE");
$total_noticias = $result->fetch_assoc()['total'];
$total_paginas = ceil($total_noticias / $noticias_por_pagina);

// Obtener noticias de la página actual
$noticias = [];
$result = $mysqli->query("SELECT n.*, u.nombre as autor_nombre 
                          FROM noticias n 
                          LEFT JOIN users u ON n.autor_id = u.id 
                          WHERE n.activa = TRUE 
                          ORDER BY n.fecha_publicacion DESC 
                          LIMIT $noticias_por_pagina OFFSET $offset");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $noticias[] = $row;
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
                <h1 class="text-white font-weight-bold">Nuestro Blog</h1>
            </div>
        </div>
    </div>
</section>

<!-- BLOG -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h2 class="mb-4" style="color: #2C3E50;">Últimas Noticias y Artículos</h2>
                    <p class="text-muted para-desc mb-0 mx-auto">
                        Mantente informado sobre las últimas tendencias en tecnología
                    </p>
                </div>
            </div>
        </div>

        <?php if (count($noticias) > 0): ?>
        <div class="row">
            <?php foreach ($noticias as $noticia): ?>
            <div class="col-lg-4 col-md-6 mt-4 pt-2">
                <div class="card blog rounded border-0 shadow overflow-hidden">
                    <?php if (!empty($noticia['imagen_destacada'])): ?>
                        <img src="<?php echo htmlspecialchars($noticia['imagen_destacada']); ?>" 
                             class="img-fluid" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>"
                             style="height: 220px; object-fit: cover; width: 100%;">
                    <?php else: ?>
                        <img src="images/blog/post-1.jpg" class="img-fluid" 
                             alt="<?php echo htmlspecialchars($noticia['titulo']); ?>"
                             style="height: 220px; object-fit: cover; width: 100%;">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <?php if (!empty($noticia['categoria'])): ?>
                            <span class="badge badge-primary mb-2" style="background: #E67E22; border: none;">
                                <?php echo htmlspecialchars($noticia['categoria']); ?>
                            </span>
                        <?php endif; ?>
                        
                        <h5 class="card-title">
                            <a href="noticia.php?id=<?php echo $noticia['id']; ?>" 
                               style="color: #2C3E50;">
                                <?php echo htmlspecialchars($noticia['titulo']); ?>
                            </a>
                        </h5>
                        
                        <?php if (!empty($noticia['subtitulo'])): ?>
                            <p class="text-muted">
                                <em><?php echo htmlspecialchars($noticia['subtitulo']); ?></em>
                            </p>
                        <?php endif; ?>
                        
                        <p class="text-muted">
                            <?php echo recortar_texto($noticia['contenido'], 150); ?>
                        </p>
                        
                        <div class="post-meta d-flex justify-content-between mt-3">
                            <ul class="list-unstyled mb-0">
                                <li class="list-inline-item mr-2 mb-0">
                                    <i class="ti-user"></i> 
                                    <?php echo htmlspecialchars($noticia['autor_nombre'] ?? 'Admin'); ?>
                                </li>
                                <li class="list-inline-item mr-2 mb-0">
                                    <i class="ti-calendar"></i> 
                                    <?php echo formatear_fecha($noticia['fecha_publicacion'], 'corto'); ?>
                                </li>
                            </ul>
                            <a href="noticia.php?id=<?php echo $noticia['id']; ?>" 
                               style="color: #E67E22;">
                                Leer más <i class="ti-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- PAGINACIÓN -->
        <?php if ($total_paginas > 1): ?>
        <div class="row mt-5">
            <div class="col-12">
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        <?php if ($pagina_actual > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="blog.php?page=<?php echo $pagina_actual - 1; ?>">
                                Anterior
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                            <a class="page-link" href="blog.php?page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($pagina_actual < $total_paginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="blog.php?page=<?php echo $pagina_actual + 1; ?>">
                                Siguiente
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="row">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="ti-info"></i> No hay noticias disponibles en este momento.
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>