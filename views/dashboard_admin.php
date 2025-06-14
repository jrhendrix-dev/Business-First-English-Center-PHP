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
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../includes/adminHandlers.php';
require_once __DIR__ . '/../src/controllers/create.php';
require_once __DIR__ . '/../src/controllers/delete.php';
require_once __DIR__ . '/../src/controllers/update.php';

// ========================== CONEXIÓN A BASE DE DATOS =========================
if (!isset($con)) {
    require_once __DIR__ . '/../src/models/Database.php';
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
<script src="/Business-First-English-Center/assets/js/usuarios.js"></script>
<script src="/Business-First-English-Center/assets/js/clases.js"></script>
<script src="/Business-First-English-Center/assets/js/notas.js"></script>
<script src="/Business-First-English-Center/assets/js/horarios.js"></script>