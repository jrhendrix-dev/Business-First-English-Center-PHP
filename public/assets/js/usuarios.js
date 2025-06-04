/**
 * @fileoverview
 * Handles user (usuarios) management for the admin dashboard.
 * Includes AJAX calls for loading, creating, updating, and deleting users,
 * as well as dynamic class dropdown population and inline editing in the UI.
 *
 * @author Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license MIT
 */

/**
 * Loads the list of users from the server and injects them into the DOM.
 *
 * @function
 * @returns {void}
 */
function loadUsers() {
    $.get('dashboard_admin.php?loadUsers=1', function(data) {
        $('#user-table-container').html(data);
    });
}

/**
 * Fetches available classes for assignment to a user (typically for teachers).
 * Returns a jQuery <select> element with class options.
 *
 * @function
 * @param {?number|string} [selectedId=null] - The ID of the class to pre-select (if any).
 * @returns {Promise<jQuery>} Promise resolving to a jQuery <select> element.
 */
function fetchAvailableClasses(selectedId = null) {
    return $.get('dashboard_admin.php?availableClasses=1').then(function (optionsHtml) {
        const select = $('<select class="form-control" name="class"></select>')
            .append(`<option value="">-- Sin clase' --</option>`, optionsHtml);

        if (selectedId) {
            select.find(`option[value='${selectedId}']`).prop('selected', true);
        }

        return select;
    }).fail(function(xhr) {
        console.error('Fallo al obtener clases disponibles:', xhr.responseText);
    });
}

/**
 * Loads the class dropdown for the user creation form (for teachers).
 *
 * @function
 * @returns {void}
 */
function loadAvailableClassesDropdown() {
    fetchAvailableClasses().then(function(select) {
        $('#user-create-form select[name="class"]').replaceWith(select);
    });
}

/**
 * Handles changes to the user level dropdown in the user creation form.
 * Dynamically updates the class field based on the selected user level.
 *
 * @event change
 * @memberof module:usuarios
 * @param {Event} e - The change event.
 */
$(document).on('change', '#user-create-form select[name="ulevel"]', function () {
    const selected = $(this).val();
    const $form = $('#user-create-form');

    // Remove any previous class field (select or hidden input)
    $form.find('[name="class"]').remove();

    let $classField;

    if (selected === '1') {
        // Admin → no class (hidden input)
        $classField = $('<input>', {
            type: 'hidden',
            name: 'class',
            value: ''
        });
        $form.find('button[type="submit"]').before($classField);
    } else if (selected === '2') {
        // Teacher → dropdown with available classes
        fetchAvailableClasses().then(function (select) {
            select.attr({
                name: 'class',
                required: false,
                class: 'form-control mb-2'
            });
            $form.find('button[type="submit"]').before(select);
        });
    } else if (selected === '3') {
        // Student → dropdown with all classes
        $classField = $(`
            <select name="class" class="form-control mb-2" required>
                <option value="" disabled selected>Seleccione una clase</option>
                ${window.classOptions}
            </select>
        `);
        $form.find('button[type="submit"]').before($classField);
    }
});

$(document).ready(function() {
    // On page load, set up the form and load users
    if ($('#user-create-form select[name="ulevel"]').val() === '2') {
        loadAvailableClassesDropdown();
    }

    loadUsers();

    /**
     * Handles the submission of the user creation form.
     * Sends an AJAX POST request to create a new user.
     *
     * @event submit
     * @memberof module:usuarios
     * @param {Event} e - The submit event.
     */
    $('#user-create-form').on('submit', function(e) {
        e.preventDefault();
        $.post('../src/controllers/create.php', $(this).serialize())
            .done(function(response) {
                $('#create-user-feedback')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .text('Usuario creado correctamente.');
                $('#user-create-form')[0].reset();
                refreshAllTabs();
            })
            .fail(function(xhr) {
                $('#create-user-feedback')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .text('Error al crear el usuario: ' + xhr.responseText);
            });
    });

    /**
     * Handles the click event for editing a user row.
     * Replaces the row's cells with editable inputs and dropdowns.
     *
     * @event click
     * @memberof module:usuarios
     * @param {Event} e - The click event.
     */
    $(document).on('click', '.edit-btn', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const username = row.find('.username').text();
        const email = row.find('.email').text();
        const currentClassId = row.find('.class').data('classid');
        const currentUlevel = row.find('.ulevel').text();

        // Editable inputs
        row.find('.username').html(`<input class="form-control" value="${username}">`);
        row.find('.email').html(`<input class="form-control" value="${email}">`);

        // User level select
        const ulevelSelect = $(`
        <select class='form-control'>
            <option value=''>Rango de usuario</option>
            <option value='1'>Admin</option>
            <option value='2'>Profesor</option>
            <option value='3'>Alumno</option>
        </select>
    `);
        ulevelSelect.val(currentUlevel);
        row.find('.ulevel').html(ulevelSelect);

        // Load available classes (for teachers)
        if (currentUlevel === '2') {
            fetchAvailableClasses(currentClassId).then(function (select) {
                row.find('.class').html(select);

                // Replace buttons after loading
                row.find('.edit-btn').replaceWith('<button class="btn btn-sm btn-success save-btn">Aceptar</button>');
                row.find('.delete-btn').replaceWith('<button class="btn btn-sm btn-secondary cancel-btn">Cancelar</button>');
            });
        } else {
            const classSelect = $('<select class="form-control"></select>')
                .append(`<option value="">-- Sin asignar --</option>`, window.classOptions)
                .val(currentClassId);
            row.find('.class').html(classSelect);

            row.find('.edit-btn').replaceWith('<button class="btn btn-sm btn-success save-btn">Aceptar</button>');
            row.find('.delete-btn').replaceWith('<button class="btn btn-sm btn-secondary cancel-btn">Cancelar</button>');
        }
    });

    /**
     * Handles the change event for the user level select in the edit row.
     * Dynamically updates the class field based on the selected user level.
     *
     * @event change
     * @memberof module:usuarios
     * @param {Event} e - The change event.
     */
    $(document).on('change', 'tr .ulevel select', function () {
        const row = $(this).closest('tr');
        const selected = $(this).val();
        const currentClassId = row.find('.class select').val();

        if (selected === '2') {
            fetchAvailableClasses(currentClassId).then(function (select) {
                row.find('.class').html(select);
            });
        } else if (selected === '1') {
            row.find('.class').html('<input type="hidden" name="class" value="">');
        } else {
            const classSelect = $('<select class="form-control"></select>')
                .append(`<option value="">-- Sin asignar --</option>`, window.classOptions)
                .val(currentClassId);
            row.find('.class').html(classSelect);
        }
    });

    /**
     * Handles the click event for canceling user edit.
     * Refreshes all tabs to restore the original state.
     *
     * @event click
     * @memberof module:usuarios
     * @param {Event} e - The click event.
     */
    $(document).on('click', '.cancel-btn', function () {
        refreshAllTabs();
    });

    /**
     * Handles the click event for saving user edits.
     * Sends an AJAX POST request to update the user.
     *
     * @event click
     * @memberof module:usuarios
     * @param {Event} e - The click event.
     */
    $(document).on('click', '.save-btn', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');
        const username = row.find('.username input').val();
        const email = row.find('.email input').val();
        const clase = row.find('.class select').val();
        const ulevel = row.find('.ulevel select').val();

        $.post('dashboard_admin.php', {
            updateUser: 1,
            user_id: id,
            username: username,
            email: email,
            class: clase,
            ulevel: ulevel
        }).done(function (resp) {
            refreshAllTabs();
        });
    });

    /**
     * Handles the click event for deleting a user.
     * Sends an AJAX POST request to delete the user after confirmation.
     *
     * @event click
     * @memberof module:usuarios
     * @param {Event} e - The click event.
     */
    $(document).on('click', '.delete-btn', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');
        if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
            $.post('dashboard_admin.php', {
                deleteUser: 1,
                user_id: id
            }).done(function (resp) {
                refreshAllTabs();
            });
        }
    });
});