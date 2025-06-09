<?php
// ========================== INCLUDES Y DEPENDENCIAS ==========================
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../includes/studentHandlers.php';

if (!isset($studentClassId)) $studentClassId = '';
if (!isset($className)) $className = '';
?>

<script>
    // Inject class ID for JS use
    window.studentClassId = `<?php echo $studentClassId; ?>`;
    window.class_name = `<?php echo $className; ?>`;
</script>

<!-- ===========================================================================
     PANEL DE ESTUDIANTES
============================================================================ -->

<div class="container mt-4">
    <div class="student-dashboard">
        <h3 class="text-center mb-4">Panel de Estudiante</h3>
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="studentTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="student-tab" data-toggle="tab" href="#students" role="tab" aria-controls="students" aria-selected="true">
                    Notas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="schedule-tab" data-toggle="tab" href="#schedule" role="tab" aria-controls="schedule" aria-selected="false">
                    Horario de la Clase
                </a>
            </li>
        </ul>
        <div class="tab-content mt-3" id="studentTabsContent">
            <!-- Grades Tab -->
            <div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="students-tab">
                <h5 class="mb-3">Clase: <?=$className; ?></h5>
                <div id="student-grades-table-container">
                    <!-- AJAX: grades will load here via student_dash.js -->
                </div>
            </div>
            <!-- Schedule Tab -->
            <div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
                <h5 class="mb-3">Horario de la Clase: <?=$className; ?></h5>
                <div id="student-schedule-table-container">
                    <!-- AJAX: Schedule will load here via student_dash.js -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===========================================================================
     SCRIPTS DE FUNCIONALIDAD DINÁMICA PARA EL PANEL DE PROFESORES
============================================================================ -->
<script src="/Business-First-English-Center/public/assets/js/student_dash.js"></script>