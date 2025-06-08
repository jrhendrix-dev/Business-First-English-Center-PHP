<?php
// ========================== INCLUDES Y DEPENDENCIAS ==========================
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../includes/teacherHandlers.php';

// ========================== CONEXIÓN A BASE DE DATOS =========================
if (!isset($con)) {
    require_once __DIR__ . '/../src/models/Database.php';
    try {
        $con = Database::connect();
    } catch (Exception $e) {
        error_log('Exception caught: ' . $e->getMessage());
        echo "<div class='alert alert-danger'>Ha ocurrido un error. Por favor, inténtelo de nuevo más tarde.</div>";
    }
}


$teacher_id = $_SESSION['user_id'] ?? null;
$teacherClassId = $_SESSION['curso'] ?? null;



if (!isset($teacherClassId)) $teacherClassId = '';
if (!isset($className)) $className = '';
?>

<script>
    // Inject class ID for JS use
    window.teacherClassId = `<?php echo $teacherClassId; ?>`;
    window.class_name = `<?php echo $className; ?>`;
</script>

<!-- ===========================================================================
     PANEL DE PROFESORES
============================================================================ -->

<div class="container mt-4">
    <div class="teacher-dashboard">
        <h3 class="text-center mb-4">Panel de Profesor</h3>
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="teacherTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="students-tab" data-toggle="tab" href="#students" role="tab" aria-controls="students" aria-selected="true">
                    Alumnos y Notas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="schedule-tab" data-toggle="tab" href="#schedule" role="tab" aria-controls="schedule" aria-selected="false">
                    Horario de la Clase
                </a>
            </li>
        </ul>
        <div class="tab-content mt-3" id="teacherTabsContent">
            <!-- Students & Grades Tab -->
            <div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="students-tab">
                <h5 class="mb-3">Clase: <?=$className; ?></h5>
                <div id="teacher-students-table-container">
                    <!-- AJAX: Student list and grades will load here via teacher_dash.js -->
                </div>
            </div>
            <!-- Schedule Tab -->
            <div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
                <h5 class="mb-3">Horario de la Clase: <?=$className; ?></h5>
                <div id="teacher-schedule-table-container">
                    <!-- AJAX: Schedule will load here via teacher_dash.js -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===========================================================================
     SCRIPTS DE FUNCIONALIDAD DINÁMICA PARA EL PANEL DE PROFESORES
============================================================================ -->
<script src="/Business-First-English-Center/public/assets/js/teacher_dash.js"></script>