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
require_once __DIR__ . '/../../src/controllers/create.php';         // Crear usuarios
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


// ========================== ADMIN PHP HANDLERS ====================================//

            // ==================== CREATION HANDLERS ===================//

                            // ================ USER HANDLERS ================//
/**
 * Handles creation of a new user (admin, teacher, or student).
 *
 * @param string $_POST['username'] The username for the new user.
 * @param string $_POST['email'] The email address for the new user.
 * @param string $_POST['pword'] The password for the new user (should be hashed).
 * @param int $_POST['ulevel'] The user level (1=admin, 2=teacher, 3=student).
 * @param string|int $_POST['class'] The class ID assigned to the user (may be empty for admin).
 * @return void Outputs "Yes" on success, "No. Error: ..." on failure, and error message if notas insertion fails.
 */
if (isset($_POST['username'], $_POST['email'], $_POST['pword'], $_POST['ulevel'], $_POST['class'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['pword']; // Should be hashed before storing!
    $ulevel = intval($_POST['ulevel']);
    $class = $_POST['class'];

    if ($class === "") {
        $class = 0; // or $class = '';
    }

    // Basic validation
    if ($username === '' || $email === '' || $password === '' || $ulevel < 1 || $ulevel > 3) {
        echo "No. Error: Invalid input.";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Find the lowest available user_id
    $result = $con->query("SELECT user_id FROM users ORDER BY user_id ASC");
    $expected_id = 1;
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if ((int)$row['user_id'] != $expected_id) {
                break;
            }
            $expected_id++;
        }
    }

    // Prepare insert statement with explicit user_id
    $stmt = $con->prepare("INSERT INTO users (user_id, username, email, pword, ulevel, class) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssii", $expected_id, $username, $email, $hashed_password, $ulevel, $class);

    if ($stmt->execute()) {
        // If student, create notas entry
        if ($ulevel == 3) {
            $user_id = $expected_id;
            $stmt2 = $con->prepare("INSERT INTO notas (idAlumno) VALUES (?)");
            $stmt2->bind_param("i", $user_id);
            if (!$stmt2->execute()) {
                echo "User created, but failed to create notas: " . $stmt2->error;
                exit;
            }
        }
        echo "Yes";
    } else {
        echo "No. Error: " . $stmt->error;
    }
    exit;
}

                    // ================ CLASS HANDLERS ================//

/**
 * Handles creation of a new class.
 *
 * @param string $_POST['createClass'] Must be set to trigger this handler.
 * @param string $_POST['classname'] The name of the new class.
 * @param string|int|null $_POST['profesor'] (Optional) The user ID of the teacher to assign to the class.
 * @return void Outputs "success" on success, "error" or "error: classname required" on failure.
 */
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['createClass'])
    && isset($_POST['classname'])
) {
    $classname = trim($_POST['classname']);
    $profesor_id = isset($_POST['profesor']) ? trim($_POST['profesor']) : '';

    // Validate classname
    if ($classname === '') {
        echo "error: classname required";
        exit;
    }

    // Find the lowest available classid
    $result = $con->query("SELECT classid FROM clases ORDER BY classid ASC");
    $expected_id = 1;
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if ((int)$row['classid'] != $expected_id) {
                break;
            }
            $expected_id++;
        }
    }

    // Insert the new class with explicit classid
    $stmt = $con->prepare("INSERT INTO clases (classid, classname) VALUES (?, ?)");
    $stmt->bind_param("is", $expected_id, $classname);
    $success1 = $stmt->execute();

    if (!$success1) {
        echo "error";
        exit;
    }

    $classid = $expected_id; // Use the assigned classid

    // If no teacher assigned, finish here
    if ($profesor_id === '' || $profesor_id === null) {
        echo "success";
        exit;
    }

    // Assign the teacher to the class
    $stmt2 = $con->prepare("UPDATE users SET class=? WHERE user_id=?");
    $stmt2->bind_param("ii", $classid, $profesor_id);
    $success2 = $stmt2->execute();

    echo ($success2) ? "success" : "error";
    exit;
}


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
        header("Location: /gracias.php");
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
                <div class="admin-section mb-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6">
                                <h4>Crear nuevo usuario</h4>
                                <!-- Formulario para crear usuarios (admin, profesor, alumno) -->
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
                                                <select name="ulevel" class="form-control mb-2" id="ulevel" required>
                                                    <option value="" disabled selected>Rango de usuario</option>
                                                    <option value="1">Admin</option>
                                                    <option value="2">Profesor</option>
                                                    <option value="3">Alumno</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label for="class">Clase asignada</label>
                                                <select name="class" class="form-control mb-2" id="class">
                                                    <option value="" disabled selected>Seleccione una clase</option>
                                                    <option value="">Sin clase</option>
                                                    <?= $classOptions ?>
                                                </select>
                                            </div>
                                    <button type="submit" class="btn btn-primary">Crear usuario</button>
                                </form>
                            </div> <!-- col -->
                        </div> <!-- row -->
                    </div> <!-- container -->
                    <div id="create-user-feedback" class="mt-2 text-success"></div>
                </div>
                <div class="admin-section">
                    <h4>Lista de usuarios</h4>
                    <!-- Aquí se carga la tabla de usuarios vía AJAX -->
                    <div id="user-table-container"></div>
                </div>
            </div>

            <!-- ======================== PESTAÑA CLASES ======================== -->
            <div class="tab-pane fade" id="clases" role="tabpanel">
                <div class="admin-section mb-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6">
                                <h4>Crear nueva clase</h4>
                                <!-- Formulario para crear clases -->
                                <form id="class-create-form">
                                    <div class="form-group mb-2">
                                            <label for="classname">Nombre del curso</label>
                                            <input type="text" id="classname" name="classname" placeholder="Nombre del curso" class="form-control mb-2" required />
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="profesor">Profesor</label>
                                            <select id="profesor" name="profesor" class="form-control mb-2" required>
                                                <option value="" disabled selected>Seleccione un profesor</option>
                                                <?= $teacherOptions ?>
                                            </select>
                                        </div>
                                    <button type="submit" class="btn btn-primary">Crear clase</button>
                                </form>
                            </div> <!-- col -->
                        </div> <!-- row -->
                    </div> <!-- container -->
                    <div id="create-class-feedback" class="mt-2 text-success"></div>
                </div>
                <div class="admin-section">
                    <h4>Lista de clases</h4>
                    <!-- Aquí se carga la tabla de clases vía AJAX -->
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