
/**
 * @fileoverview
 * Handles user (usuarios) management for the admin dashboard.
 * Includes AJAX calls for loading, creating, updating, and deleting users,
 * as well as dynamic class dropdown population and inline editing in the UI.
 *
 * @author Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license MIT
 */

let originalUserCreateFormHtml = null;

/**
 * Loads the list of users from the server and injects them into the DOM.
 *
 * @function
 * @returns {void}
 */
function loadUsers() {
    $.get('/api/admin?loadUsers=1', function(data) {
        $('#user-table-container').html(data);
    });
}

/**
 * Fetches available classes for teachers.
 * Always includes their currently assigned class (if any), even if it's already assigned.
 *
 * @param {?number|string} selectedId - The ID of the class that should appear as selected (optional).
 * @returns {JQuery.jqXHR} - A jQuery Deferred object resolving to a <select> element.
 */
function fetchAvailableClasses(selectedId = null) {
    // Build the URL: if there's a selected class, include its ID in the query so it's not excluded
    const url = selectedId
        ? `/api/admin?availableClasses=1&include=${selectedId}`
        : '/api/admin?availableClasses=1';

    return $.get(url).then(function (optionsHtml) {
        // Build the <select> element and prepend a default "no class" option
        const select = $('<select class="form-control" name="class"></select>')
            .append(`<option value="">-- Sin clase --</option>`, optionsHtml);

        // If a selected ID is passed, pre-select it in the dropdown
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
 * Refreshes all admin dashboard tabs (users, classes, teachers, etc.).
 * Should be defined elsewhere in your codebase.
 *
 * @function
 * @returns {void}
 */
// function refreshAllTabs() { ... } // <-- This is function is in common.js

/**
 * Handles changes to the user level dropdown in the user creation form.
 * Dynamically updates the class field based on the selected user level.
 * @param {Event} e - jQuery change event
 */
function handleUserLevelChangeInCreateForm(e) {
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
}

/**
 * Handles the click event for editing a user row.
 * Replaces the row's cells with editable inputs and dropdowns.
 *
 */
function handleEditUserClick() {
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
}

/**
 * Handles the change event for the user level select in the edit row.
 * Dynamically updates the class field based on the selected user level.
 *
 */
function handleUserLevelChangeInEditRow() {
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
}

/**
 * Handles the click event for canceling user edit.
 * Refreshes all tabs to restore the original state.
 *
 * @event click
 * @param {Event} e - The click event.
 */
function handleCancelEditUserClick(e) {
    refreshAllTabs();
}

/**
 * Handles the click event for saving user edits.
 * Sends an AJAX POST request to update the user.
 *
 * @event click
 * @param {Event} e - The click event.
 */
function handleSaveUserEditClick(e) {
    const row = $(this).closest('tr');
    const id = row.data('id');
    const username = row.find('.username input').val();
    const email = row.find('.email input').val();
    const clase = row.find('.class select').val();
    const ulevel = row.find('.ulevel select').val();

    $.post('/api/admin', {
        updateUser: 1,
        user_id: id,
        username: username,
        email: email,
        class: clase,
        ulevel: ulevel
    }).done(function (resp) {
        refreshAllTabs();
    });
}

/**
 * Handles the click event for deleting a user.
 * Sends an AJAX POST request to delete the user after confirmation.
 *
 * * @param {Event} event - Click event
 * */
function handleDeleteUserClick() {
    const row = $(this).closest('tr');
    const id = row.data('id');
    if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
        $.post('/api/admin', {
            deleteUser: 1,
            user_id: id
        }).done(function () {
            refreshAllTabs();
        });
    }
}

/**
 * Handles the submission of the user creation form.
 * Sends an AJAX POST request to create a new user.
 *
 * @event submit
 * @param {Event} e - The submit event.
 */
function handleUserCreateFormSubmit(e) {
    e.preventDefault();
    $.post('/api/admin', $(this).serialize())
        .done(function() {
            $('#create-user-feedback')
                .removeClass('text-danger')
                .addClass('text-success')
                .text('Usuario creado correctamente.');
            // Full reset: replace the form with the original HTML
            $('#user-create-form').replaceWith(originalUserCreateFormHtml);
            // Refresh the users table
            refreshAllTabs(); // or loadUsers();
        })
        .fail(function(xhr) {
            $('#create-user-feedback')
                .removeClass('text-success')
                .addClass('text-danger')
                .text('Error al crear el usuario: ' + xhr.responseText);
        });
}

/**
 * Handles tab switching and resets the user creation form when switching to the Usuarios tab.
 *
 * @event shown.bs.tab
 * @param {Event} e - The tab shown event.
 */
function handleTabShown(e) {
    const target = $(e.target).attr("href");
    if (target === "#usuarios") {
        $('#user-create-form')[0].reset();
        const $ulevel = $('#user-create-form select[name="ulevel"]');
        if ($ulevel.length) {
            $ulevel.prop('selectedIndex', 0).trigger('change');
        }
        $('#create-user-feedback').text('').removeClass('text-success text-danger');
    }
}

/**
 * Handles the full reset of the user creation form when switching to Usuarios or Clases tab.
 *
 * @event shown.bs.tab
 */
function handleFullResetOnTabShown() {
    $('#user-create-form').replaceWith(originalUserCreateFormHtml);
    $('#create-user-feedback').text('').removeClass('text-success text-danger');
}

// Main document ready block: binds all event handlers and initializes the UI. Runs on page start
$(document).ready(function() {
    // Store the original HTML of the user creation form for full reset
    originalUserCreateFormHtml = $('#user-create-form').prop('outerHTML');

    // On page load, set up the form and load users
    if ($('#user-create-form select[name="ulevel"]').val() === '2') {
        loadAvailableClassesDropdown();
    }

    // USER CREATION FORM TOGGLE
    const toggleBtn = $('#toggleUserForm');
    const formContainer = $('#userFormContainer');

    toggleBtn.on('click', function () {
        formContainer.toggleClass('show');

        // Toggle + / -
        const isExpanded = formContainer.hasClass('show');
        toggleBtn.text(isExpanded ? '- Ocultar formulario' : '+ Añadir usuario');
    });

    loadUsers();

    // Event delegation for dynamic elements and handlers
    $(document).on('change', '#user-create-form select[name="ulevel"]', handleUserLevelChangeInCreateForm);
    $(document).on('submit', '#user-create-form', handleUserCreateFormSubmit);
    $(document).on('click', '.edit-btn', handleEditUserClick);
    $(document).on('change', 'tr .ulevel select', handleUserLevelChangeInEditRow);
    $(document).on('click', '.cancel-btn', handleCancelEditUserClick);
    $(document).on('click', '.save-btn', handleSaveUserEditClick);
    $(document).on('click', '.delete-btn', handleDeleteUserClick);

    // Tab shown handlers
    $('a[data-toggle="tab"]').on('shown.bs.tab', handleTabShown);
    $('a[data-toggle="tab"][href="#usuarios"], a[data-toggle="tab"][href="#clases"]').on('shown.bs.tab', handleFullResetOnTabShown);
});