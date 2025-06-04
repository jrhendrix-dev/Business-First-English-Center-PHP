/**
 * @fileoverview
 * Handles class management (CRUD) for the admin dashboard.
 * Includes AJAX calls for loading, creating, updating, and deleting classes,
 * as well as dynamic teacher dropdown population.
 *
 * @author Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license MIT
 */

/**
 * Loads the list of classes from the server and injects them into the DOM.
 *
 * @function
 * @returns {void}
 */
function loadClasses() {
    $.get('dashboard_admin.php?loadClasses=1', function(data) {
        $('#class-table-container').html(data);
    }).fail(function(xhr) {
        console.error('Fallo al cargar clases:', xhr.responseText);
    });
}

/**
 * Fetches available teachers for assignment to a class.
 * Returns a jQuery <select> element with teacher options.
 *
 * @function
 * @param {?number|string} [selectedId=null] - The ID of the teacher to pre-select (if any).
 * @returns {Promise<jQuery>} Promise resolving to a jQuery <select> element.
 */
function fetchAvailableTeachers(selectedId = null) {
    return $.get('dashboard_admin.php?availableTeachers=1').then(function (optionsHtml) {
        const select = $('<select class="form-control" name="profesor"></select>').append(`<option value="">-- Sin asignar --</option>`, optionsHtml);
        if (selectedId) {
            select.find(`option[value='${selectedId}']`).prop('selected', true);
        }
        return select;
    }).fail(function(xhr) {
        console.error('Fallo al obtener profesores disponibles:', xhr.responseText);
    });
}

/**
 * Loads the teacher dropdown for the class creation form.
 *
 * @function
 * @returns {void}
 */
function loadTeacherDropdown() {
    fetchAvailableTeachers().then(function(select) {
        $('#class-create-form select[name="profesor"]').replaceWith(select);
    });
}


//ON PAGE LOAD
$(document).ready(function () {
    loadClasses();
    loadTeacherDropdown();

    /**
     * Handles the submission of the class creation form.
     * Sends an AJAX POST request to create a new class.
     *
     * @event submit
     * @memberof module:clases
     * @param {Event} e - The submit event.
     */
    $('#class-create-form').on('submit', function (e) {
        e.preventDefault();
        const classname = $(this).find('[name="classname"]').val();
        const profesor = $(this).find('[name="profesor"]').val();

        $.post('dashboard_admin.php', {
            createClass: 1,
            classname: classname,
            profesor: profesor || ''
        }).done(function (response) {
            console.log('Respuesta del servidor al crear clase:', response);
            response = response.trim();
            if (response === 'success') {
                $('#create-class-feedback')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .text('Clase creada correctamente.');
                $('#class-create-form')[0].reset();
                refreshAllTabs();
                loadTeacherDropdown();
            } else {
                console.error('Error en respuesta:', response);
                $('#create-class-feedback')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .text('Error al crear la clase.');
            }
        }).fail(function (xhr) {
            console.error('Fallo AJAX al crear clase:', xhr.responseText);
            $('#create-class-feedback')
                .removeClass('text-success')
                .addClass('text-danger')
                .text('Error al crear la clase.');
        });
    });

    /**
     * Handles the click event for editing a class row.
     * Replaces the row's cells with editable inputs and dropdowns.
     *
     * @event click
     * @memberof module:clases
     */
    $(document).on('click', '.edit-class-btn', async function () {
        const row = $(this).closest('tr');
        const classid = row.data('id');

        const classnameVal = row.find('.classname').text().trim();
        row.find('.classname').html(`<input type='text' class='form-control' value='${classnameVal}' />`);

        const profesorId = row.find('.profesor').data('profid');
        const select = await fetchAvailableTeachers(profesorId);
        row.find('.profesor').html(select);

        row.find('.edit-class-btn').replaceWith('<button class="btn btn-sm btn-success save-class-btn">Guardar</button>');
        row.find('.delete-class-btn').replaceWith('<button class="btn btn-sm btn-secondary cancel-class-btn">Cancelar</button>');
    });

    /**
     * Handles the click event for canceling class edit.
     * Refreshes all tabs to restore the original state.
     *
     * @event click
     * @memberof module:clases
     */
    $(document).on('click', '.cancel-class-btn', function () {
        refreshAllTabs();
    });

    /**
     * Handles the click event for saving class edits.
     * Sends an AJAX POST request to update the class.
     *
     * @event click
     * @memberof module:clases
     */
    $(document).on('click', '.save-class-btn', function () {
        const row = $(this).closest('tr');
        const classid = row.data('id');
        const classname = row.find('.classname input').val();
        const profesor = row.find('.profesor select').val();

        $.post('dashboard_admin.php', {
            updateClass: 1,
            classid: classid,
            classname: classname,
            profesor: profesor || ''
        }).done(function (response) {
            console.log('Respuesta del servidor al actualizar clase:', response);
            if (response.trim() === 'success') {
                refreshAllTabs();
                loadTeacherDropdown();
            } else {
                console.error('Error en respuesta al actualizar clase:', response);
                alert('Error al actualizar la clase');
            }
        }).fail(function (xhr) {
            console.error('Fallo al actualizar clase:', xhr.responseText);
            alert('Error al actualizar la clase');
        });
    });

    /**
     * Handles the click event for deleting a class.
     * Sends an AJAX POST request to delete the class after confirmation.
     *
     * @event click
     * @memberof module:clases
     */
    $(document).on('click', '.delete-class-btn', function () {
        const row = $(this).closest('tr');
        const classid = row.data('id');
        if (confirm('¿Estás seguro de que quieres eliminar esta clase?')) {
            $.post('dashboard_admin.php', {
                deleteClass: 1,
                classid: classid
            }).done(function (response) {
                console.log('Respuesta del servidor al eliminar clase:', response);
                if (response.trim() === 'success') {
                    refreshAllTabs();
                    loadTeacherDropdown();
                } else {
                    console.error('Error en respuesta al eliminar clase:', response);
                    alert('Error al eliminar la clase');
                }
            }).fail(function (xhr) {
                console.error('Fallo al eliminar clase:', xhr.responseText);
                alert('Error al eliminar la clase');
            });
        }
    });
});