<?php
/**
 * ========================================
 * ARCHIVO: index.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Página principal del sitio web
 */

require_once 'includes/config.php';
require_once 'includes/functions.php';

iniciar_sesion_segura();

$page_title = SITE_NAME . " - " . SITE_SLOGAN;

$mysqli = getDBConnection();

// Obtener las 3 últimas noticias
$ultimas_noticias = [];
$result = $mysqli->query(
    "SELECT n.*, u.nombre as autor_nombre 
     FROM noticias n 
     LEFT JOIN users u ON n.autor_id = u.id 
     WHERE n.activa = TRUE 
     ORDER BY n.fecha_publicacion DESC 
     LIMIT 3"
);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $ultimas_noticias[] = $row;
    }
}

// Obtener proyectos del portfolio (máximo 6)
$proyectos = [];
$result = $mysqli->query(
    "SELECT * FROM proyectos 
     WHERE activo = TRUE 
     ORDER BY fecha_creacion DESC 
     LIMIT 6"
);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $proyectos[] = $row;
    }
}

// Obtener testimonios
$testimonios = [];
$result = $mysqli->query(
    "SELECT * FROM testimonios 
     WHERE activo = TRUE 
     ORDER BY fecha_creacion DESC 
     LIMIT 3"
);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $testimonios[] = $row;
    }
}

closeDBConnection($mysqli);

include 'includes/header.php';
?>

<!-- BANNER/HERO -->
<section class="banner" style="background-image: url('images/banner/banner.jpg'); background-size: cover; background-position: center; position: relative; padding: 150px 0;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-xl-7">
                <div class="block text-center text-lg-left">
                    <h1 class="mb-3 mt-3 text-white" style="font-size: 48px; font-weight: bold;">
                        <?php echo SITE_NAME; ?>
                    </h1>
                    <p class="mb-4 pr-5 text-white" style="font-size: 20px;">
                        <?php echo SITE_SLOGAN; ?>
                    </p>
                    <a href="portfolio.php" class="btn btn-main-md" style="background: #E67E22; border: none;">
                        Ver Portfolio <i class="ti-arrow-right"></i>
                    </a>
                    <a href="contact.php" class="btn btn-outline-light ml-2">
                        Contactar <i class="ti-email"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SOBRE NOSOTROS -->
<section class="about section" id="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-img">
                    <img src="images/about/about-us.png" alt="TechSolutions" class="img-fluid rounded shadow">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content pl-4 mt-4 mt-lg-0">
                    <h2 class="mb-4" style="color: #2C3E50;">
                        Sobre TechSolutions Pro
                    </h2>
                    <p class="mb-4">
                        Somos una empresa líder en desarrollo de soluciones tecnológicas innovadoras. 
                        Con más de 10 años de experiencia, transformamos las ideas de nuestros clientes 
                        en realidades digitales que impulsan el crecimiento de sus negocios.
                    </p>
                    <p class="mb-4">
                        Nuestro equipo está compuesto por profesionales altamente cualificados en 
                        desarrollo web, aplicaciones móviles, diseño UX/UI y consultoría tecnológica.
                    </p>
                    <a href="portfolio.php" class="btn btn-main-sm">Ver Nuestros Proyectos</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICIOS -->
<section class="service" style="background: #f8f9fa; padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h2 class="mb-4" style="color: #2C3E50;">Nuestros Servicios</h2>
                    <p class="text-muted para-desc mb-0 mx-auto">
                        Ofrecemos soluciones tecnológicas completas y personalizadas
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded text-center p-4 h-100">
                    <i class="ti-world" style="font-size: 48px; color: #E67E22;"></i>
                    <h4 class="mt-4 mb-3" style="color: #2C3E50;">Desarrollo Web</h4>
                    <p class="text-muted">Creamos sitios web modernos, responsivos y optimizados para SEO que convierten visitantes en clientes.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded text-center p-4 h-100">
                    <i class="ti-mobile" style="font-size: 48px; color: #E67E22;"></i>
                    <h4 class="mt-4 mb-3" style="color: #2C3E50;">Apps Móviles</h4>
                    <p class="text-muted">Desarrollamos aplicaciones nativas e híbridas para iOS y Android con experiencias de usuario excepcionales.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded text-center p-4 h-100">
                    <i class="ti-paint-bucket" style="font-size: 48px; color: #E67E22;"></i>
                    <h4 class="mt-4 mb-3" style="color: #2C3E50;">Diseño UX/UI</h4>
                    <p class="text-muted">Diseñamos interfaces intuitivas y atractivas que mejoran la experiencia del usuario y la conversión.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded text-center p-4 h-100">
                    <i class="ti-server" style="font-size: 48px; color: #E67E22;"></i>
                    <h4 class="mt-4 mb-3" style="color: #2C3E50;">Cloud & DevOps</h4>
                    <p class="text-muted">Implementamos soluciones en la nube y automatizamos procesos para optimizar la infraestructura IT.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded text-center p-4 h-100">
                    <i class="ti-lock" style="font-size: 48px; color: #E67E22;"></i>
                    <h4 class="mt-4 mb-3" style="color: #2C3E50;">Seguridad</h4>
                    <p class="text-muted">Protegemos tus sistemas con las mejores prácticas de ciberseguridad y auditorías especializadas.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded text-center p-4 h-100">
                    <i class="ti-headphone-alt" style="font-size: 48px; color: #E67E22;"></i>
                    <h4 class="mt-4 mb-3" style="color: #2C3E50;">Consultoría</h4>
                    <p class="text-muted">Asesoramos en la transformación digital de tu empresa con estrategias tecnológicas efectivas.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PORTFOLIO -->
<?php if (count($proyectos) > 0): ?>
<section class="portfolio section" id="portfolio" style="padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h2 class="mb-4" style="color: #2C3E50;">Nuestro Portfolio</h2>
                    <p class="text-muted para-desc mb-0 mx-auto">
                        Proyectos recientes que hemos desarrollado con éxito
                    </p>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <?php foreach ($proyectos as $proyecto): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded overflow-hidden">
                    <?php if (!empty($proyecto['imagen'])): ?>
                        <img src="<?php echo htmlspecialchars($proyecto['imagen']); ?>" 
                             class="img-fluid" 
                             alt="<?php echo htmlspecialchars($proyecto['titulo']); ?>"
                             style="height: 250px; object-fit: cover; width: 100%;">
                    <?php else: ?>
                        <img src="images/project/project-1.jpg" 
                             class="img-fluid" 
                             alt="Proyecto"
                             style="height: 250px; object-fit: cover; width: 100%;">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <?php if (!empty($proyecto['categoria'])): ?>
                            <span class="badge badge-primary mb-2" style="background: #E67E22; border: none;">
                                <?php echo htmlspecialchars($proyecto['categoria']); ?>
                            </span>
                        <?php endif; ?>
                        
                        <h5 class="card-title" style="color: #2C3E50;">
                            <?php echo htmlspecialchars($proyecto['titulo']); ?>
                        </h5>
                        
                        <p class="text-muted">
                            <?php echo recortar_texto($proyecto['descripcion'], 120); ?>
                        </p>
                        
                        <a href="proyecto.php?id=<?php echo $proyecto['id']; ?>" 
                           class="btn btn-sm btn-outline-primary">
                            Ver Detalles <i class="ti-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="portfolio.php" class="btn btn-main-sm">
                    Ver Todos los Proyectos <i class="ti-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- TESTIMONIOS -->
<?php if (count($testimonios) > 0): ?>
<section class="testimonial section" style="background: #2C3E50; padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h2 class="mb-4 text-white">Lo Que Dicen Nuestros Clientes</h2>
                    <p class="text-white-50 para-desc mb-0 mx-auto">
                        La satisfacción de nuestros clientes es nuestra mejor carta de presentación
                    </p>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <?php foreach ($testimonios as $testimonio): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded p-4 h-100">
                    <div class="d-flex align-items-center mb-3">
                        <?php if (!empty($testimonio['foto'])): ?>
                            <img src="<?php echo htmlspecialchars($testimonio['foto']); ?>" 
                                 class="rounded-circle mr-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;"
                                 alt="<?php echo htmlspecialchars($testimonio['nombre']); ?>">
                        <?php else: ?>
                            <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px; background: #E67E22; color: white; font-size: 24px;">
                                <?php echo strtoupper(substr($testimonio['nombre'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div>
                            <h6 class="mb-0" style="color: #2C3E50;">
                                <?php echo htmlspecialchars($testimonio['nombre']); ?>
                            </h6>
                            <?php if (!empty($testimonio['cargo'])): ?>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars($testimonio['cargo']); ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <p class="text-muted mb-0">
                        <i class="ti-quote-left" style="color: #E67E22; font-size: 20px;"></i>
                        <?php echo htmlspecialchars($testimonio['testimonio']); ?>
                        <i class="ti-quote-right" style="color: #E67E22; font-size: 20px;"></i>
                    </p>
                    
                    <?php if (!empty($testimonio['empresa'])): ?>
                        <hr>
                        <small class="text-muted">
                            <i class="ti-briefcase"></i> <?php echo htmlspecialchars($testimonio['empresa']); ?>
                        </small>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- BLOG / NOTICIAS -->
<?php if (count($ultimas_noticias) > 0): ?>
<section class="blog section" id="blog" style="padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h2 class="mb-4" style="color: #2C3E50;">Últimas Noticias</h2>
                    <p class="text-muted para-desc mb-0 mx-auto">
                        Mantente al día con las últimas tendencias y novedades tecnológicas
                    </p>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <?php foreach ($ultimas_noticias as $noticia): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card blog rounded border-0 shadow overflow-hidden">
                    <?php if (!empty($noticia['imagen_destacada'])): ?>
                        <img src="<?php echo htmlspecialchars($noticia['imagen_destacada']); ?>" 
                             class="img-fluid" 
                             alt="<?php echo htmlspecialchars($noticia['titulo']); ?>"
                             style="height: 220px; object-fit: cover; width: 100%;">
                    <?php else: ?>
                        <img src="images/blog/post-1.jpg" 
                             class="img-fluid" 
                             alt="Noticia"
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
                                <em><?php echo recortar_texto($noticia['subtitulo'], 80); ?></em>
                            </p>
                        <?php endif; ?>
                        
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
                <a href="blog.php" class="btn btn-main-sm">
                    Ver Todas las Noticias <i class="ti-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CALL TO ACTION -->
<section class="cta" style="background: linear-gradient(135deg, #2C3E50 0%, #34495e 100%); padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="text-white mb-4">¿Listo para Transformar tu Negocio?</h2>
                <p class="text-white-50 mb-4" style="font-size: 18px;">
                    Contacta con nosotros y descubre cómo podemos ayudarte a alcanzar tus objetivos digitales
                </p>
                <a href="contact.php" class="btn btn-main-md" style="background: #E67E22; border: none;">
                    <i class="ti-email"></i> Contáctanos Ahora
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>