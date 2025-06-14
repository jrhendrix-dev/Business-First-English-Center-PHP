/**
 * @file student_dash.js
 * @description Handles loading and displaying the student's grades and class schedule in read-only mode.
 * Requires jQuery and Bootstrap.
 */

$(document).ready(function () {
    initializeNotasModule();

    // Handle tab changes to load the appropriate content
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr('href');
        if (target === '#students') {
            loadStudentGradesTable();
        } else if (target === '#schedule') {
            loadStudentScheduleTable();
        }
    });
});

/**
 * Initializes the grades module by loading the student's grades table on page load.
 */
function initializeNotasModule() {
    loadStudentGradesTable();
}

/**
 * Loads the current student's grades table (read-only) via AJAX and inserts it into the dashboard.
 * On failure, displays an error message in the grades container.
 */
function loadStudentGradesTable() {
    $.get('../includes/studentHandlers.php?action=getStudentGrades', function (data) {
        $('#student-grades-table-container').html(data);
    }).fail(function (xhr) {
        console.error('Fallo al cargar notas:', xhr.responseText);
        $('#student-grades-table-container').html(
            `<div class="alert alert-danger">Error del servidor: ${xhr.status}<br>${xhr.responseText}</div>`
        );
    });
}

/**
 * Loads the current student's class schedule via AJAX and inserts it into the dashboard.
 * On failure, displays an error message in the schedule container.
 */
function loadStudentScheduleTable() {
    $.get('../includes/studentHandlers.php?action=getClassSchedule', function (html) {
        $('#student-schedule-table-container').html(html);
    }).fail(function (xhr) {
        console.error('Error al cargar horario:', xhr.responseText);
        $('#student-schedule-table-container').html(
            '<div class="alert alert-danger">Error al cargar el horario.</div>'
        );
    });
}