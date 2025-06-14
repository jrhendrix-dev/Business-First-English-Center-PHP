/**
 * @fileoverview
 * Handles schedule (horarios) management for the admin dashboard.
 * Includes AJAX calls for loading and updating class schedules,
 * and dynamic editing of schedule rows in the UI.
 *
 * @author Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license MIT
 */

/**
 * Loads the list of class schedules from the server and injects them into the DOM.
 *
 * @function
 * @returns {void}
 */
function loadHorarios() {
    $.get('dashboard_admin.php?loadHorarios=1', function (data) {
        $('#horarios-table-container').html(data);
    }).fail(function (xhr) {
        console.error('Error al cargar horarios:', xhr.responseText);
    });
}

/**
 * Handles the click event for editing a schedule row.
 * Replaces the row's class cells with <select> dropdowns for editing.
 *
 * @event click
 * @param {Event} e - The click event.
 */
function handleEditHorarioBtnClick(e) {
    const row = $(this).closest('tr');

    ['firstclass', 'secondclass', 'thirdclass'].forEach(col => {
        const originalText = row.find('.' + col).text().trim();
        const currentOption = window.classOptions;
        const select = $('<select class="form-control"></select>').append(currentOption);

        // Try to select the option by visible text
        select.find('option').filter(function () {
            return $(this).text().trim() === originalText;
        }).prop('selected', true);

        row.find('.' + col).html(select);
    });

    row.find('.edit-horario-btn').addClass('d-none');
    row.find('.save-horario-btn, .cancel-horario-btn').removeClass('d-none');
}

/**
 * Handles the click event for canceling schedule edit.
 * Refreshes all tabs to restore the original state.
 *
 * @event click
 * @param {Event} e - The click event.
 */
function handleCancelHorarioBtnClick(e) {
    refreshAllTabs();
}

/**
 * Handles the click event for saving schedule edits.
 * Sends an AJAX POST request to update the schedule for the day.
 *
 * @event click
 * @param {Event} e - The click event.
 */
function handleSaveHorarioBtnClick(e) {
    const row = $(this).closest('tr');
    const day_id = row.data('id');
    const firstclass = row.find('.firstclass select').val();
    const secondclass = row.find('.secondclass select').val();
    const thirdclass = row.find('.thirdclass select').val();

    $.post('dashboard_admin.php', {
        updateHorario: 1,
        day_id,
        firstclass,
        secondclass,
        thirdclass
    }).done(function (response) {
        if (response.trim() === 'success') {
            refreshAllTabs();
        } else {
            alert('Error al guardar horario');
        }
    }).fail(function (xhr) {
        console.error('Error al guardar horario:', xhr.responseText);
        alert('Error al guardar horario');
    });
}

/**
 * Initializes the schedule management UI and binds event handlers.
 *
 * @function
 * @returns {void}
 */
function initializeHorariosModule() {
    loadHorarios();

    // Event delegation for dynamic elements and handlers
    $(document).on('click', '.edit-horario-btn', handleEditHorarioBtnClick);
    $(document).on('click', '.cancel-horario-btn', handleCancelHorarioBtnClick);
    $(document).on('click', '.save-horario-btn', handleSaveHorarioBtnClick);
}

// Document ready: bind all event handlers and initialize UI
$(document).ready(function () {
    initializeHorariosModule();
});