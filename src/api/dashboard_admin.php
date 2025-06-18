<?php
/**
 * dashboard_admin.php
 *
 * Vista principal del panel de administración para Business First English Center.
 * Incluye la gestión de usuarios, clases, notas y horarios.
 * Inyecta variables PHP en JS para opciones de clases y profesores.
 *
 * PHP version 7+
 *
 * @package    BusinessFirstEnglishCenter
 * @author     Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license    MIT License
 */

// ========================== INCLUDES Y DEPENDENCIAS ==========================
require_once __DIR__ . '/../../bootstrap.php';                       // Configuración general
require_once __DIR__ . '/../../src/controllers/UserController.php';
require_once __DIR__ . '/../../src/controllers/ClassController.php';
require_once __DIR__ . '/../../src/controllers/GradesController.php';
require_once __DIR__ . '/../../src/controllers/ScheduleController.php';
require_once __DIR__ . '/../../src/controllers/delete.php';         // Eliminar usuarios
require_once __DIR__ . '/../../src/controllers/update.php';         // Editar usuarios
require_once __DIR__ . '/../../includes/adminHandlers.php';         // Lógica de carga de usuarios, clases, etc.

// ========================== CONEXIÓN A BASE DE DATOS =========================
if (!isset($con)) {
    require_once __DIR__ . '/../models/Database.php';
    try {
        $con = Database::connect();
} catch (Exception $e) {
    error_log('Exception caught: ' . $e->getMessage());
    // Display an error message to the user
    echo "<div class='alert alert-danger'>Ha ocurrido un error. Por favor, inténtelo de nuevo más tarde.</div>";
}
}

// ========================== VARIABLES DE OPCIONES ============================
/**
 * Asegura que $classOptions y $teacherOptions estén definidos,
 * evitando errores de variable indefinida si los includes fallan.
 */
if (!isset($classOptions)) $classOptions = '';
if (!isset($teacherOptions)) $teacherOptions = '';



                        // ================ GRADES HANDLERS ================//


                        // ================ SCHEDULE HANDLERS ================//


                        // ================ MISC HANDLERS ================//
/**
 * Handles public contact form submissions.
 *
 * @param string $_POST['nombre'] The sender's first name.
 * @param string $_POST['apellidos'] The sender's last name.
 * @param string $_POST['teléfono'] The sender's phone number.
 * @param string $_POST['email'] The sender's email address.
 * @param string $_POST['mensaje'] The message content.
 * @return void Redirects to gracias.php on success, outputs error message on failure.
 */
if (isset($_POST['nombre'], $_POST['apellidos'], $_POST['teléfono'], $_POST['email'], $_POST['mensaje'])) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $telefono = trim($_POST['teléfono']);
    $email = trim($_POST['email']);
    $mensaje = trim($_POST['mensaje']);

    // Basic validation
    if ($nombre === '' || $apellidos === '' || $email === '' || $mensaje === '') {
        echo "Error: Todos los campos obligatorios deben ser completados.";
        exit;
    }

    $stmt = $con->prepare("INSERT INTO formulario (nombre, apellidos, teléfono, email, mensaje) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $apellidos, $telefono, $email, $mensaje);

    if ($stmt->execute()) {
        header("Location: thanks");
        exit;
    } else {
        echo "Error: " . $stmt->error;
        exit;
    }
}


?>

<!-- ===========================================================================
     INYECCIÓN DE VARIABLES PHP EN JAVASCRIPT PARA OPCIONES DE SELECT
     Estas variables contienen HTML <option> para ser usadas en los formularios
     de creación/edición de usuarios y clases desde el frontend JS.
============================================================================ -->
<script>
    // Opciones de clases disponibles para selects dinámicos en JS
    window.classOptions = `<?php echo $classOptions; ?>`;
    // Opciones de profesores disponibles para selects dinámicos en JS
    window.teacherOptions = `<?php echo $teacherOptions; ?>`;
</script>

<!-- ===========================================================================
     PANEL DE ADMINISTRACIÓN
     Estructura principal con pestañas para gestionar usuarios, clases, notas y horarios.
============================================================================ -->
<div class="container mt-4">
    <div class="admin-dashboard">
        <h3 class="text-center mb-4">Panel de Administración</h3>

        <!-- ======================== NAVEGACIÓN POR PESTAÑAS ======================== -->
        <ul class="nav nav-tabs" id="adminTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="usuarios-tab" data-toggle="tab" href="#usuarios" role="tab">Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="clases-tab" data-toggle="tab" href="#clases" role="tab">Clases</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="notas-tab" data-toggle="tab" href="#notas" role="tab">Notas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="horarios-tab" data-toggle="tab" href="#horarios" role="tab">Horarios</a>
            </li>
        </ul>

        <!-- ======================== CONTENIDO DE PESTAÑAS ======================== -->
        <div class="tab-content mt-3" id="adminTabContent">

            <!-- ======================== PESTAÑA USUARIOS ======================== -->
            <div class="tab-pane fade show active" id="usuarios" role="tabpanel">

                <!-- Añadir usuario + formulario -->
                <div class="admin-section mb-4 px-3 px-md-4">
                    <div class="user-toggle-wrapper">
                        <button id="toggleUserForm" class="btn-toggle-user mb-3">
                            + Añadir usuario
                        </button>

                        <div id="userFormContainer" class="user-form-collapsible">
                            <form id="user-create-form">
                                <div class="form-group mb-2">
                                    <label for="username">Nombre de usuario</label>
                                    <input type="text" id="username" name="username" placeholder="Nombre de usuario" class="form-control" required />
                                </div>
                                <div class="form-group mb-2">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" placeholder="Email" class="form-control" required />
                                </div>
                                <div class="form-group mb-2">
                                    <label for="pword">Contraseña</label>
                                    <input type="password" id="pword" name="pword" placeholder="Contraseña" class="form-control" required />
                                </div>
                                <div class="form-group mb-2">
                                    <label for="ulevel">Rango de usuario</label>
                                    <select name="ulevel" class="form-control" id="ulevel" required>
                                        <option value="" disabled selected>Rango de usuario</option>
                                        <option value="1">Admin</option>
                                        <option value="2">Profesor</option>
                                        <option value="3">Alumno</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="class">Clase asignada</label>
                                    <select name="class" class="form-control" id="class">
                                        <option value="" disabled selected>Seleccione una clase</option>
                                        <option value="">Sin clase</option>
                                        <?= $classOptions ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Crear usuario</button>
                            </form>
                        </div>
                    </div>
                    <div id="create-user-feedback" class="mt-2 text-success"></div>
                </div>

                <!-- Lista de usuarios -->
                <div class="admin-section px-3 px-md-4">
                    <h4>Lista de usuarios</h4>
                    <div id="user-table-container"></div>
                </div>
            </div>

            <!-- ======================== PESTAÑA CLASES ======================== -->
            <div class="tab-pane fade" id="clases" role="tabpanel">
                <!-- Crear nueva clase -->
                <div class="admin-section mb-4 px-3 px-md-4">
                    <div class="class-toggle-wrapper">
                        <button id="toggleClassForm" class="btn-toggle-class mb-3">
                            + Añadir clase
                        </button>

                        <div id="classFormContainer" class="class-form-collapsible">
                            <form id="class-create-form">
                                <div class="form-group mb-2">
                                    <label for="classname">Nombre del curso</label>
                                    <input type="text" id="classname" name="classname" placeholder="Nombre del curso" class="form-control" required />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="profesor">Profesor</label>
                                    <select id="profesor" name="profesor" class="form-control" required>
                                        <option value="" disabled selected>Seleccione un profesor</option>
                                        <?= $teacherOptions ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Crear clase</button>
                            </form>
                        </div>
                    </div>
                    <div id="create-class-feedback" class="mt-2 text-success"></div>
                </div>

                <!-- Lista de clases -->
                <div class="admin-section px-3 px-md-4">
                    <h4>Lista de clases</h4>
                    <div id="class-table-container"></div>
                </div>
            </div>


            <!-- ======================== PESTAÑA NOTAS ======================== -->
            <div class="tab-pane fade" id="notas" role="tabpanel">
                <div class="admin-section">
                    <h4>Lista de notas por alumno</h4>
                    <!-- Aquí se carga la tabla de notas vía AJAX -->
                    <div id="notas-table-container"></div>
                </div>
            </div>

            <!-- ======================== PESTAÑA HORARIOS ======================== -->
            <div class="tab-pane fade" id="horarios" role="tabpanel">
                <div class="admin-section">
                    <h4>Horarios</h4>
                    <!-- Aquí se carga la tabla de horarios vía AJAX -->
                    <div id="horarios-table-container"></div>
                </div>
            </div>

        </div><!-- Tab Content End -->
    </div>
</div>

<!-- ===========================================================================
     SCRIPTS DE FUNCIONALIDAD DINÁMICA PARA EL PANEL DE ADMINISTRACIÓN
     Cada archivo JS gestiona la lógica de su sección correspondiente.
============================================================================ -->
<script src="/assets/js/usuarios.js"></script>
<script src="/assets/js/clases.js"></script>
<script src="/assets/js/notas.js"></script>
<script src="/assets/js/horarios.js"></script>