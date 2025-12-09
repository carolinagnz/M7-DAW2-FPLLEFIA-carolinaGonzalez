<<?php
/**
 * ========================================
 * ARCHIVO: index.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 */

require_once 'includes/config.php';
require_once 'includes/functions.php';

iniciar_sesion_segura();

$page_title = SITE_NAME . " - " . SITE_SLOGAN;

// Obtener datos de la BD
$mysqli = getDBConnection();

// Obtener proyectos destacados (máximo 6)
$proyectos = [];
$result = $mysqli->query("SELECT * FROM proyectos WHERE destacado = TRUE AND activo = TRUE ORDER BY orden_visualizacion ASC LIMIT 6");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $proyectos[] = $row;
    }
}

// Obtener noticias recientes (máximo 3)
$noticias = [];
$result = $mysqli->query("SELECT * FROM noticias WHERE activa = TRUE ORDER BY fecha_publicacion DESC LIMIT 3");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $noticias[] = $row;
    }
}

// Obtener testimonios
$testimonios = [];
$result = $mysqli->query("SELECT * FROM testimonios WHERE destacado = TRUE AND activo = TRUE ORDER BY puntuacion DESC LIMIT 3");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $testimonios[] = $row;
    }
}

closeDBConnection($mysqli);

include 'includes/header.php';
?>

<!-- BANNER PRINCIPAL -->
<section class="banner bg-cover position-relative d-flex justify-content-center align-items-center" 
         style="background-image: url('images/banner/banner.jpg'); min-height: 500px;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-3 text-white font-weight-bold mb-4">
                    <?php echo SITE_NAME; ?>
                </h1>
                <p class="text-white mb-5 lead">
                    <?php echo SITE_SLOGAN; ?>
                </p>
                <?php if (!esta_logueado()): ?>
                    <a href="register.php" class="btn btn-main-sm">Comienza Ahora</a>
                    <a href="contact.php" class="btn btn-main ml-2">Contacto</a>
                <?php else: ?>
                    <a href="portfolio.php" class="btn btn-main-sm">Ver Portfolio</a>
                    <a href="blog.php" class="btn btn-main ml-2">Leer Blog</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- MENSAJE DE LOGOUT -->
<?php if (isset($_GET['logout'])): ?>
<section class="section pt-5 pb-0">
    <div class="container">
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ti-check"></i> Has cerrado sesión correctamente.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- SERVICIOS -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h2 class="mb-4" style="color: #2C3E50;">Nuestros Servicios</h2>
                    <p class="text-muted para-desc mb-0 mx-auto">
                        Ofrecemos soluciones tecnológicas completas para impulsar tu negocio
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 mt-4 pt-2">
                <div class="card border-0 text-center rounded shadow p-4">
                    <div class="icon-lg text-primary mx-auto">
                        <i class="ti-world" style="font-size: 48px; color: #2C3E50;"></i>
                    </div>
                    <div class="card-body p-0 content">
                        <h5 class="mt-4" style="color: #2C3E50;">Desarrollo Web</h5>
                        <p class="text-muted mt-3 mb-0">
                            Sitios web modernos, responsivos y optimizados para tu negocio
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mt-4 pt-2">
                <div class="card border-0 text-center rounded shadow p-4">
                    <div class="icon-lg text-primary mx-auto">
                        <i class="ti-mobile" style="font-size: 48px; color: #2C3E50;"></i>
                    </div>
                    <div class="card-body p-0 content">
                        <h5 class="mt-4" style="color: #2C3E50;">Apps Móviles</h5>
                        <p class="text-muted mt-3 mb-0">
                            Aplicaciones nativas y multiplataforma para iOS y Android
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mt-4 pt-2">
                <div class="card border-0 text-center rounded shadow p-4">
                    <div class="icon-lg text-primary mx-auto">
                        <i class="ti-settings" style="font-size: 48px; color: #2C3E50;"></i>
                    </div>
                    <div class="card-body p-0 content">
                        <h5 class="mt-4" style="color: #2C3E50;">Consultoría IT</h5>
                        <p class="text-muted mt-3 mb-0">
                            Asesoramiento tecnológico para optimizar tus procesos
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mt-4 pt-2">
                <div class="card border-0 text-center rounded shadow p-4">
                    <div class="icon-lg text-primary mx-auto">
                        <i class="ti-headphone-alt" style="font-size: 48px; color: #2C3E50;"></i>
                    </div>
                    <div class="card-body p-0 content">
                        <h5 class="mt-4" style="color: #2C3E50;">Soporte 24/7</h5>
                        <p class="text-muted mt-3 mb-0">
                            Mantenimiento y soporte técnico continuo
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PROYECTOS DESTACADOS -->
<?php if (count($proyectos) > 0): ?>
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h2 class="mb-4" style="color: #2C3E50;">Portfolio Destacado</h2>
                    <p class="text-muted para-desc mb-0 mx-auto">
                        Algunos de nuestros proyectos más recientes
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <?php foreach ($proyectos as $proyecto): ?>
            <div class="col-lg-4 col-md-6 mt-4 pt-2">
                <div class="card border-0 work-container work-classic shadow rounded overflow-hidden">
                    <?php if (!empty($proyecto['imagen_principal'])): ?>
                        <img src="<?php echo htmlspecialchars($proyecto['imagen_principal']); ?>" 
                             class="img-fluid" alt="<?php echo htmlspecialchars($proyecto['titulo']); ?>"
                             style="height: 250px; object-fit: cover; width: 100%;">
                    <?php else: ?>
                        <img src="images/project/project-1.jpg" class="img-fluid" 
                             alt="<?php echo htmlspecialchars($proyecto['titulo']); ?>"
                             style="height: 250px; object-fit: cover; width: 100%;">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="mb-0">
                            <a href="proyecto.php?id=<?php echo $proyecto['id']; ?>" 
                               style="color: #2C3E50;">
                                <?php echo htmlspecialchars($proyecto['titulo']); ?>
                            </a>
                        </h5>
                        <p class="text-muted mt-2">
                            <?php echo recortar_texto($proyecto['descripcion'], 100); ?>
                        </p>
                        <?php if (!empty($proyecto['categoria'])): ?>
                            <span class="badge badge-primary" style="background: #E67E22; border: none;">
                                <?php echo htmlspecialchars($proyecto['categoria']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="portfolio.php" class="btn btn-main-sm">Ver Todos los Proyectos</a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- NOTICIAS RECIENTES -->
<?php if (count($noticias) > 0): ?>
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h2 class="mb-4" style="color: #2C3E50;">Últimas Noticias</h2>
                    <p class="text-muted para-desc mb-0 mx-auto">
                        Mantente al día con nuestro blog
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <?php foreach ($noticias as $noticia): ?>
            <div class="col-lg-4 col-md-6 mt-4 pt-2">
                <div class="card blog rounded border-0 shadow overflow-hidden">
                    <?php if (!empty($noticia['imagen_destacada'])): ?>
                        <img src="<?php echo htmlspecialchars($noticia['imagen_destacada']); ?>" 
                             class="img-fluid" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>"
                             style="height: 200px; object-fit: cover; width: 100%;">
                    <?php else: ?>
                        <img src="images/blog/post-1.jpg" class="img-fluid" 
                             alt="<?php echo htmlspecialchars($noticia['titulo']); ?>"
                             style="height: 200px; object-fit: cover; width: 100%;">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="noticia.php?id=<?php echo $noticia['id']; ?>" 
                               style="color: #2C3E50;">
                                <?php echo htmlspecialchars($noticia['titulo']); ?>
                            </a>
                        </h5>
                        <?php if (!empty($noticia['subtitulo'])): ?>
                            <p class="text-muted"><em><?php echo htmlspecialchars($noticia['subtitulo']); ?></em></p>
                        <?php endif; ?>
                        <p class="text-muted">
                            <?php echo recortar_texto($noticia['contenido'], 120); ?>
                        </p>
                        <div class="post-meta d-flex justify-content-between mt-3">
                            <ul class="list-unstyled mb-0">
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

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="blog.php" class="btn btn-main-sm">Ver Todas las Noticias</a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- TESTIMONIOS -->
<?php if (count($testimonios) > 0): ?>
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h2 class="mb-4" style="color: #2C3E50;">Lo Que Dicen Nuestros Clientes</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <?php foreach ($testimonios as $testimonio): ?>
            <div class="col-lg-4 col-md-6 mt-4 pt-2">
                <div class="card border-0 text-center rounded shadow p-4">
                    <?php if (!empty($testimonio['foto_cliente'])): ?>
                        <img src="<?php echo htmlspecialchars($testimonio['foto_cliente']); ?>" 
                             class="rounded-circle mx-auto d-block" 
                             style="width: 80px; height: 80px; object-fit: cover;"
                             alt="<?php echo htmlspecialchars($testimonio['nombre_cliente']); ?>">
                    <?php else: ?>
                        <img src="images/testimonial/user-1.jpg" 
                             class="rounded-circle mx-auto d-block" 
                             style="width: 80px; height: 80px; object-fit: cover;"
                             alt="<?php echo htmlspecialchars($testimonio['nombre_cliente']); ?>">
                    <?php endif; ?>
                    
                    <div class="card-body p-0 content mt-3">
                        <p class="text-muted mt-3">
                            "<?php echo htmlspecialchars($testimonio['testimonio']); ?>"
                        </p>
                        <h6 class="mb-0" style="color: #2C3E50;">
                            <?php echo htmlspecialchars($testimonio['nombre_cliente']); ?>
                        </h6>
                        <small class="text-muted">
                            <?php echo htmlspecialchars($testimonio['cargo']); ?> 
                            en <?php echo htmlspecialchars($testimonio['empresa']); ?>
                        </small>
                        <div class="mt-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="ti-star" style="color: <?php echo $i <= $testimonio['puntuacion'] ? '#E67E22' : '#dee2e6'; ?>;"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>