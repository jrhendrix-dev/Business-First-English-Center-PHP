/**
 * @fileoverview
 * Handles grade (notas) management for the admin dashboard.
 * Includes AJAX calls for loading, editing, validating, and saving student grades,
 * as well as dynamic editing of grade rows in the UI.
 *
 * @author Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license MIT
 */

/**
 * Loads the list of student grades from the server and injects them into the DOM.
 *
 * @function
 * @returns {void}
 */
function loadNotas() {
    $.get('/api/admin?loadNotas=1', function (data) {
        $('#notas-table-container').html(data);
    }).fail(function (xhr) {
        console.error('Fallo al cargar notas:', xhr.responseText);
    });
}

/**
 * Handles the click event for editing a grade row.
 * Replaces the grade cells with input fields for editing.
 *
 * @event click
 * @param {Event} e - The click event.
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
 * Handles the click event for canceling grade edit.
 * Refreshes all tabs to restore the original state.
 *
 * @event click
 * @param {Event} e - The click event.
 */
function handleCancelNotaBtnClick(e) {
    refreshAllTabs();
}

/**
 * Handles the click event for saving grade edits.
 * Validates input and sends an AJAX POST request to update the grades.
 *
 * @event click
 * @param {Event} e - The click event.
 */
function handleSaveNotaBtnClick(e) {
    const row = $(this).closest('tr');
    const nota1val = row.find('.nota1 input').val();
    const nota2val = row.find('.nota2 input').val();
    const nota3val = row.find('.nota3 input').val();

    // Accept "" as a valid value (meaning "no grade")
    const nota1 = nota1val === "" ? null : parseFloat(nota1val);
    const nota2 = nota2val === "" ? null : parseFloat(nota2val);
    const nota3 = nota3val === "" ? null : parseFloat(nota3val);

    // Only validate if not empty
    if ([nota1, nota2, nota3].some(n => n !== null && (isNaN(n) || n < 0 || n > 10))) {
        alert('Las notas deben estar entre 0 y 10 o vacías.');
        return;
    }

    $.post('/api/admin?', {
        updateNota: 1,
        idAlumno: row.data('id'),
        nota1: nota1,
        nota2: nota2,
        nota3: nota3
    }).done(function (response) {
        console.log('Respuesta al guardar nota:', response);
        if (response.trim() === 'success') {
            refreshAllTabs();
        } else if (response.includes('fuera de rango')) {
            alert('Las notas deben estar entre 0 y 10');
        } else {
            alert('Error al guardar notas');
        }
    }).fail(function (xhr) {
        console.error('Fallo al guardar notas:', xhr.responseText);
        alert('Error al guardar notas');
    });
}

/**
 * Initializes the grade management UI and binds event handlers.
 *
 * @function
 * @returns {void}
 */
function initializeNotasModule() {
    loadNotas();

    // Event delegation for dynamic elements and handlers
    $(document).on('click', '.edit-nota-btn', handleEditNotaBtnClick);
    $(document).on('click', '.cancel-nota-btn', handleCancelNotaBtnClick);
    $(document).on('click', '.save-nota-btn', handleSaveNotaBtnClick);
}

// Document ready: bind all event handlers and initialize UI
$(document).ready(function () {
    initializeNotasModule();
});