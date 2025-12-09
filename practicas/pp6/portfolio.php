<?php
/**
 * ========================================
 * ARCHIVO: portfolio.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 */

require_once 'includes/config.php';
require_once 'includes/functions.php';

iniciar_sesion_segura();

$page_title = "Portfolio - " . SITE_NAME;

// Obtener proyectos de la BD
$mysqli = getDBConnection();

$proyectos = [];
$result = $mysqli->query("SELECT * FROM proyectos WHERE activo = TRUE ORDER BY orden_visualizacion ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $proyectos[] = $row;
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
                <h1 class="text-white font-weight-bold">Nuestro Portfolio</h1>
            </div>
        </div>
    </div>
</section>

<!-- PORTFOLIO -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h2 class="mb-4" style="color: #2C3E50;">Proyectos Realizados</h2>
                    <p class="text-muted para-desc mb-0 mx-auto">
                        Descubre algunos de los proyectos que hemos desarrollado para nuestros clientes
                    </p>
                </div>
            </div>
        </div>

        <?php if (count($proyectos) > 0): ?>
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
                        
                        <?php if (!empty($proyecto['cliente'])): ?>
                            <p class="text-muted mt-2 mb-2">
                                <i class="ti-briefcase"></i> 
                                <strong>Cliente:</strong> <?php echo htmlspecialchars($proyecto['cliente']); ?>
                            </p>
                        <?php endif; ?>
                        
                        <p class="text-muted">
                            <?php echo recortar_texto($proyecto['descripcion'], 120); ?>
                        </p>
                        
                        <div class="mt-3">
                            <?php if (!empty($proyecto['categoria'])): ?>
                                <span class="badge badge-primary mr-1" style="background: #E67E22; border: none;">
                                    <?php echo htmlspecialchars($proyecto['categoria']); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($proyecto['tecnologias'])): ?>
                                <span class="badge badge-secondary" style="background: #95a5a6; border: none;">
                                    <?php echo htmlspecialchars($proyecto['tecnologias']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-3">
                            <a href="proyecto.php?id=<?php echo $proyecto['id']; ?>" 
                               class="btn btn-sm btn-main-sm">
                                Ver Detalles <i class="ti-arrow-right"></i>
                            </a>
                            
                            <?php if (!empty($proyecto['url_proyecto'])): ?>
                                <a href="<?php echo htmlspecialchars($proyecto['url_proyecto']); ?>" 
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary ml-2">
                                    <i class="ti-link"></i> Visitar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="row">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="ti-info"></i> No hay proyectos disponibles en este momento.
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA -->
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mb-4" style="color: #2C3E50;">¿Tienes un proyecto en mente?</h2>
                <p class="text-muted mb-4">
                    Contáctanos y cuéntanos tu idea. Estaremos encantados de ayudarte a hacerla realidad.
                </p>
                <a href="contact.php" class="btn btn-main-sm">Contactar Ahora</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>