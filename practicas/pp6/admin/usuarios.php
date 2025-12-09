<?php
/**
 * ========================================
 * ARCHIVO: admin/usuarios.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 * Listado de usuarios
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

iniciar_sesion_segura();
requerir_login();
requerir_admin();

$page_title = "Gestión de Usuarios - Admin - " . SITE_NAME;

$mysqli = getDBConnection();

// Obtener todos los usuarios
$usuarios = [];
$result = $mysqli->query("SELECT * FROM users ORDER BY date_register DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
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
                    <i class="ti-user"></i> Gestión de Usuarios
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Usuarios</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- USUARIOS -->
<section class="section">
    <div class="container">
        
        <!-- MENSAJES -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti-check"></i> 
                <?php
                if ($_GET['success'] == 'added') echo 'Usuario creado correctamente';
                elseif ($_GET['success'] == 'updated') echo 'Usuario actualizado correctamente';
                elseif ($_GET['success'] == 'deleted') echo 'Usuario eliminado correctamente';
                ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-6">
                <h4 style="color: #2C3E50;">
                    <i class="ti-user"></i> Total de Usuarios: <?php echo count($usuarios); ?>
                </h4>
            </div>
            <div class="col-md-6 text-right">
                <a href="addUser.php" class="btn btn-success">
                    <i class="ti-plus"></i> Nuevo Usuario
                </a>
            </div>
        </div>

        <div class="card border-0 shadow rounded">
            <div class="card-body p-0">
                <?php if (count($usuarios) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #2C3E50; color: white;">
                            <tr>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Edad</th>
                                <th>Trabajo</th>
                                <th>Fecha Registro</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo $usuario['id']; ?></td>
                                <td>
                                    <?php if (!empty($usuario['foto'])): ?>
                                        <img src="../<?php echo htmlspecialchars($usuario['foto']); ?>" 
                                             class="rounded-circle" 
                                             style="width: 40px; height: 40px; object-fit: cover;"
                                             alt="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                                    <?php else: ?>
                                        <i class="ti-user" style="font-size: 24px; color: #95a5a6;"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $usuario['role'] == 'admin' ? 'danger' : 'primary'; ?>">
                                        <?php echo htmlspecialchars($usuario['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo $usuario['age']; ?> años</td>
                                <td><?php echo htmlspecialchars($usuario['job'] ?: '-'); ?></td>
                                <td><?php echo formatear_fecha($usuario['date_register'], 'corto'); ?></td>
                                <td>
                                    <?php if ($usuario['activo']): ?>
                                        <span class="badge badge-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="editUser.php?id=<?php echo $usuario['id']; ?>" 
                                       class="btn btn-sm btn-primary" title="Editar">
                                        <i class="ti-pencil"></i>
                                    </a>
                                    
                                    <?php if ($usuario['id'] != $_SESSION['user_id']): ?>
                                    <a href="deleteUser.php?id=<?php echo $usuario['id']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Estás seguro de eliminar este usuario?')"
                                       title="Eliminar">
                                        <i class="ti-trash"></i>
                                    </a>
                                    <?php else: ?>
                                    <button class="btn btn-sm btn-secondary" disabled title="No puedes eliminarte a ti mismo">
                                        <i class="ti-lock"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="p-4 text-center">
                    <p class="text-muted mb-0">No hay usuarios registrados</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<?php include '../includes/footer.php'; ?>