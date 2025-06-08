/**
 * teacher_dash.js
 * Maneja la carga de alumnos y edición de notas para el panel del profesor.
 * Requiere jQuery y Bootstrap.
 */

$(document).ready(function () {
    initializeNotasModule();

    // Manejar cambio de pestañas
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr('href');
        if (target === '#students') {
            loadTeacherStudentsTable();
        } else if (target === '#schedule') {
            loadTeacherScheduleTable();
        }
    });
});

/**
 * Carga la tabla de alumnos y notas.
 */
function loadTeacherStudentsTable() {
    $.get('../includes/teacherHandlers.php?action=getStudentsAndGrades', function (data) {
        $('#teacher-students-table-container').html(data);
    }).fail(function (xhr) {
        console.error('Fallo al cargar alumnos:', xhr.responseText);
        $('#teacher-students-table-container').html(
            `<div class="alert alert-danger">Error del servidor: ${xhr.status}<br>${xhr.responseText}</div>`
        );
    });
}

/**
 * Carga la tabla de horarios.
 */
function loadTeacherScheduleTable() {
    $.get('../includes/teacherHandlers.php?action=getClassSchedule', function (html) {
        $('#teacher-schedule-table-container').html(html);
    }).fail(function (xhr) {
        console.error('Error al cargar horario:', xhr.responseText);
        $('#teacher-schedule-table-container').html('<div class="alert alert-danger">Error al cargar el horario.</div>');
    });
}


/**
 * Permite editar una fila de notas.
 */
function handleEditNotaBtnClick(e) {
    const row = $(this).closest('tr');
    row.find('.nota1, .nota2, .nota3').each(function () {
        const value = $(this).text().trim();
        $(this).html(`<input type='number' min='0' max='10' step='0.01' class='form-control' value='${value}' />`);
    });

    row.find('.edit-nota-btn').addClass('d-none');
    row.find('.save-nota-btn').removeClass('d-none');
    row.find('.cancel-nota-btn').removeClass('d-none');
}

/**
 * Cancela la edición de notas recargando la tabla.
 */
function handleCancelNotaBtnClick(e) {
    loadTeacherStudentsTable();
}

/**
 * Guarda las notas editadas vía AJAX.
 */
function handleSaveNotaBtnClick(e) {
    const row = $(this).closest('tr');
    const nota1val = row.find('.nota1 input').val();
    const nota2val = row.find('.nota2 input').val();
    const nota3val = row.find('.nota3 input').val();

    const nota1 = nota1val === "" ? null : parseFloat(nota1val);
    const nota2 = nota2val === "" ? null : parseFloat(nota2val);
    const nota3 = nota3val === "" ? null : parseFloat(nota3val);

    if ([nota1, nota2, nota3].some(n => n !== null && (isNaN(n) || n < 0 || n > 10))) {
        alert('Las notas deben estar entre 0 y 10 o vacías.');
        return;
    }

    $.post('../includes/teacherHandlers.php', {
        updateNota: 1,
        idAlumno: row.data('id'),
        nota1: nota1,
        nota2: nota2,
        nota3: nota3
    }).done(function (response) {
        console.log('Respuesta al guardar nota:', response);
        if (response.trim() === 'success') {
            loadTeacherStudentsTable();
        } else {
            alert('Error al guardar notas: ' + response);
        }
    }).fail(function (xhr) {
        console.error('Fallo al guardar notas:', xhr.responseText);
        alert('Error al guardar notas');
    });
}

/**
 * Inicializa el módulo de notas y enlaza eventos.
 */
function initializeNotasModule() {
    loadTeacherStudentsTable();

    $(document).on('click', '.edit-nota-btn', handleEditNotaBtnClick);
    $(document).on('click', '.cancel-nota-btn', handleCancelNotaBtnClick);
    $(document).on('click', '.save-nota-btn', handleSaveNotaBtnClick);
}
