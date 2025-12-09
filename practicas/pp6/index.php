<?php
/**
 * ========================================
 * ARCHIVO: admin/index.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Dashboard del Panel de Administración
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Panel de Administración - " . SITE_NAME;

// Obtener estadísticas
$mysqli = getDBConnection();

// Contar usuarios
$result = $mysqli->query("SELECT COUNT(*) as total FROM users");
$total_usuarios = $result->fetch_assoc()['total'];

// Contar noticias
$result = $mysqli->query("SELECT COUNT(*) as total FROM noticias");
$total_noticias = $result->fetch_assoc()['total'];

// Contar proyectos
$result = $mysqli->query("SELECT COUNT(*) as total FROM proyectos");
$total_proyectos = $result->fetch_assoc()['total'];

// Contar testimonios
$result = $mysqli->query("SELECT COUNT(*) as total FROM testimonios");
$total_testimonios = $result->fetch_assoc()['total'];

// Contar mensajes sin leer
$result = $mysqli->query("SELECT COUNT(*) as total FROM mensajes_contacto WHERE leido = FALSE");
$mensajes_sin_leer = $result->fetch_assoc()['total'];

// Últimos usuarios registrados
$ultimos_usuarios = [];
$result = $mysqli->query("SELECT * FROM users ORDER BY date_register DESC LIMIT 5");
while ($row = $result->fetch_assoc()) {
    $ultimos_usuarios[] = $row;
}

// Últimos mensajes de contacto
$ultimos_mensajes = [];
$result = $mysqli->query("SELECT * FROM mensajes_contacto ORDER BY fecha_envio DESC LIMIT 5");
while ($row = $result->fetch_assoc()) {
    $ultimos_mensajes[] = $row;
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
                    <i class="ti-dashboard"></i> Panel de Administración
                </h1>
                <p class="text-white-50">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- DASHBOARD -->
<section class="section">
    <div class="container">
        
        <!-- ESTADÍSTICAS -->
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="ti-user" style="font-size: 48px; color: #3498db;"></i>
                        </div>
                        <h3 class="mb-2" style="color: #2C3E50;"><?php echo $total_usuarios; ?></h3>
                        <p class="text-muted mb-0">Usuarios</p>
                        <a href="usuarios.php" class="btn btn-sm btn-outline-primary mt-3">Ver Todos</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="ti-write" style="font-size: 48px; color: #e74c3c;"></i>
                        </div>
                        <h3 class="mb-2" style="color: #2C3E50;"><?php echo $total_noticias; ?></h3>
                        <p class="text-muted mb-0">Noticias</p>
                        <a href="noticias.php" class="btn btn-sm btn-outline-primary mt-3">Ver Todas</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="ti-briefcase" style="font-size: 48px; color: #2ecc71;"></i>
                        </div>
                        <h3 class="mb-2" style="color: #2C3E50;"><?php echo $total_proyectos; ?></h3>
                        <p class="text-muted mb-0">Proyectos</p>
                        <a href="proyectos.php" class="btn btn-sm btn-outline-primary mt-3">Ver Todos</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="ti-email" style="font-size: 48px; color: #f39c12;"></i>
                        </div>
                        <h3 class="mb-2" style="color: #2C3E50;"><?php echo $mensajes_sin_leer; ?></h3>
                        <p class="text-muted mb-0">Mensajes Sin Leer</p>
                        <a href="mensajes.php" class="btn btn-sm btn-outline-primary mt-3">Ver Mensajes</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACCESOS RÁPIDOS -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="mb-4" style="color: #2C3E50;">
                    <i class="ti-menu"></i> Accesos Rápidos
                </h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body p-4">
                        <h5 style="color: #2C3E50;">
                            <i class="ti-user"></i> Usuarios
                        </h5>
                        <p class="text-muted mb-3">Gestionar usuarios del sistema</p>
                        <a href="usuarios.php" class="btn btn-sm btn-primary mr-2">Ver Todos</a>
                        <a href="addUser.php" class="btn btn-sm btn-success">
                            <i class="ti-plus"></i> Nuevo
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body p-4">
                        <h5 style="color: #2C3E50;">
                            <i class="ti-write"></i> Noticias
                        </h5>
                        <p class="text-muted mb-3">Gestionar noticias del blog</p>
                        <a href="noticias.php" class="btn btn-sm btn-primary mr-2">Ver Todas</a>
                        <a href="addNoticia.php" class="btn btn-sm btn-success">
                            <i class="ti-plus"></i> Nueva
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body p-4">
                        <h5 style="color: #2C3E50;">
                            <i class="ti-briefcase"></i> Proyectos
                        </h5>
                        <p class="text-muted mb-3">Gestionar portfolio de proyectos</p>
                        <a href="proyectos.php" class="btn btn-sm btn-primary mr-2">Ver Todos</a>
                        <a href="addProyecto.php" class="btn btn-sm btn-success">
                            <i class="ti-plus"></i> Nuevo
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body p-4">
                        <h5 style="color: #2C3E50;">
                            <i class="ti-comment"></i> Testimonios
                        </h5>
                        <p class="text-muted mb-3">Gestionar testimonios de clientes</p>
                        <a href="testimonios.php" class="btn btn-sm btn-primary mr-2">Ver Todos</a>
                        <a href="addTestimonio.php" class="btn btn-sm btn-success">
                            <i class="ti-plus"></i> Nuevo
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body p-4">
                        <h5 style="color: #2C3E50;">
                            <i class="ti-help"></i> FAQs
                        </h5>
                        <p class="text-muted mb-3">Gestionar preguntas frecuentes</p>
                        <a href="faqs.php" class="btn btn-sm btn-primary mr-2">Ver Todas</a>
                        <a href="addFaq.php" class="btn btn-sm btn-success">
                            <i class="ti-plus"></i> Nueva
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body p-4">
                        <h5 style="color: #2C3E50;">
                            <i class="ti-email"></i> Mensajes
                        </h5>
                        <p class="text-muted mb-3">Ver mensajes de contacto</p>
                        <a href="mensajes.php" class="btn btn-sm btn-primary">
                            Ver Mensajes
                            <?php if ($mensajes_sin_leer > 0): ?>
                                <span class="badge badge-danger ml-1"><?php echo $mensajes_sin_leer; ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ÚLTIMOS USUARIOS -->
        <div class="row mt-4">
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body p-4">
                        <h5 class="mb-4" style="color: #2C3E50;">
                            <i class="ti-user"></i> Últimos Usuarios Registrados
                        </h5>

                        <?php if (count($ultimos_usuarios) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimos_usuarios as $usuario): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($usuario['foto'])): ?>
                                                <img src="../<?php echo htmlspecialchars($usuario['foto']); ?>" 
                                                     class="rounded-circle mr-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover;">
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($usuario['nombre']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $usuario['role'] == 'admin' ? 'danger' : 'primary'; ?>">
                                                <?php echo htmlspecialchars($usuario['role']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo formatear_fecha($usuario['date_register'], 'corto'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <p class="text-muted">No hay usuarios registrados</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ÚLTIMOS MENSAJES -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body p-4">
                        <h5 class="mb-4" style="color: #2C3E50;">
                            <i class="ti-email"></i> Últimos Mensajes de Contacto
                        </h5>

                        <?php if (count($ultimos_mensajes) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Asunto</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimos_mensajes as $mensaje): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($mensaje['nombre']); ?></td>
                                        <td><?php echo recortar_texto($mensaje['asunto'], 30); ?></td>
                                        <td><?php echo formatear_fecha($mensaje['fecha_envio'], 'corto'); ?></td>
                                        <td>
                                            <?php if (!$mensaje['leido']): ?>
                                                <span class="badge badge-warning">Sin leer</span>
                                            <?php else: ?>
                                                <span class="badge badge-success">Leído</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <p class="text-muted">No hay mensajes</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<?php include '../includes/footer.php'; ?>