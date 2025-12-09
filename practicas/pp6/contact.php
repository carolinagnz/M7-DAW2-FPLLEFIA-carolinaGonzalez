<?php
/**
 * ========================================
 * ARCHIVO: contact.php
 * PROYECTO: TechSolutions Pro
 * ========================================
 */

require_once 'includes/config.php';
require_once 'includes/functions.php';

iniciar_sesion_segura();

$page_title = "Contacto - " . SITE_NAME;

$error = '';
$success = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = getDBConnection();
    
    $nombre = limpiar_input($_POST['nombre']);
    $email = limpiar_input($_POST['email']);
    $asunto = limpiar_input($_POST['asunto']);
    $mensaje = limpiar_input($_POST['mensaje']);
    
    // Validaciones
    if (!validar_email($email)) {
        $error = "El email no es válido";
    } elseif (empty($nombre) || empty($asunto) || empty($mensaje)) {
        $error = "Todos los campos son obligatorios";
    } else {
        // Insertar mensaje
        $stmt = $mysqli->prepare(
            "INSERT INTO mensajes_contacto (nombre, email, asunto, mensaje, fecha_envio) 
             VALUES (?, ?, ?, ?, NOW())"
        );
        
        if ($stmt) {
            $stmt->bind_param("ssss", $nombre, $email, $asunto, $mensaje);
            
            if ($stmt->execute()) {
                $success = "¡Mensaje enviado correctamente! Te contactaremos pronto.";
                // Limpiar variables
                $nombre = $email = $asunto = $mensaje = '';
            } else {
                $error = "Error al enviar el mensaje. Inténtalo de nuevo.";
            }
            
            $stmt->close();
        }
    }
    
    $mysqli->close();
}

include 'includes/header.php';
?>

<!-- PAGE TITLE -->
<section class="page-title bg-cover" style="background-image: url('images/backgrounds/page-title.jpg'); padding: 100px 0;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="text-white font-weight-bold">Contacto</h1>
            </div>
        </div>
    </div>
</section>

<!-- CONTACTO -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h2 class="mb-4" style="color: #2C3E50;">Ponte en Contacto</h2>
                    <p class="text-muted para-desc mb-0 mx-auto">
                        ¿Tienes alguna pregunta o proyecto en mente? Escríbenos y te responderemos lo antes posible
                    </p>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <!-- INFO DE CONTACTO -->
            <div class="col-lg-4">
                <div class="card border-0 shadow rounded p-4 mb-4">
                    <div class="icon text-center">
                        <i class="ti-location-pin" style="font-size: 48px; color: #2C3E50;"></i>
                    </div>
                    <div class="content mt-3 text-center">
                        <h5 style="color: #2C3E50;">Dirección</h5>
                        <p class="text-muted"><?php echo SITE_ADDRESS; ?></p>
                    </div>
                </div>

                <div class="card border-0 shadow rounded p-4 mb-4">
                    <div class="icon text-center">
                        <i class="ti-mobile" style="font-size: 48px; color: #2C3E50;"></i>
                    </div>
                    <div class="content mt-3 text-center">
                        <h5 style="color: #2C3E50;">Teléfono</h5>
                        <p class="text-muted"><?php echo SITE_PHONE; ?></p>
                    </div>
                </div>

                <div class="card border-0 shadow rounded p-4">
                    <div class="icon text-center">
                        <i class="ti-email" style="font-size: 48px; color: #2C3E50;"></i>
                    </div>
                    <div class="content mt-3 text-center">
                        <h5 style="color: #2C3E50;">Email</h5>
                        <p class="text-muted"><?php echo SITE_EMAIL; ?></p>
                    </div>
                </div>
            </div>

            <!-- FORMULARIO -->
            <div class="col-lg-8">
                <div class="card border-0 shadow rounded p-4">
                    <h4 class="mb-4" style="color: #2C3E50;">
                        <i class="ti-email"></i> Envíanos un Mensaje
                    </h4>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="ti-alert"></i> <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="ti-check"></i> <?php echo htmlspecialchars($success); ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="ti-user"></i> Nombre *</label>
                                    <input type="text" name="nombre" class="form-control" required
                                           value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="ti-email"></i> Email *</label>
                                    <input type="email" name="email" class="form-control" required
                                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label><i class="ti-bookmark"></i> Asunto *</label>
                                    <input type="text" name="asunto" class="form-control" required
                                           value="<?php echo isset($asunto) ? htmlspecialchars($asunto) : ''; ?>">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label><i class="ti-comment"></i> Mensaje *</label>
                                    <textarea name="mensaje" class="form-control" rows="6" required><?php echo isset($mensaje) ? htmlspecialchars($mensaje) : ''; ?></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-main-sm btn-lg">
                                    <i class="ti-email"></i> Enviar Mensaje
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>