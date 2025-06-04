function loadUsers() {
    $.get('dashboard_admin.php?loadUsers=1', function(data) {
        $('#user-table-container').html(data);
    });
}

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

function loadAvailableClassesDropdown() {
    fetchAvailableClasses().then(function(select) {
        $('#user-create-form select[name="class"]').replaceWith(select);
    });
}

//              LISTENER
$(document).on('change', '#user-create-form select[name="ulevel"]', function () {
    const selected = $(this).val();
    const $form = $('#user-create-form');

    // Elimina cualquier campo anterior de clase (select o input hidden)
    $form.find('[name="class"]').remove();

    // Crear el nuevo contenedor de clase
    let $classField;

    if (selected === '1') {
        // Admin → sin clase (input hidden)
        $classField = $('<input>', {
            type: 'hidden',
            name: 'class',
            value: ''
        });
        $form.find('button[type="submit"]').before($classField);
    } else if (selected === '2') {
        // Profesor → dropdown con clases disponibles
        fetchAvailableClasses().then(function (select) {
            select.attr({
                name: 'class',
                required: false,
                class: 'form-control mb-2'
            });
            $form.find('button[type="submit"]').before(select);
        });
    } else if (selected === '3') {
        // Alumno → dropdown con todas las clases
        $classField = $(`
            <select name="class" class="form-control mb-2" required>
                <option value="" disabled selected>Seleccione una clase</option>
                ${window.classOptions}
            </select>
        `);
        $form.find('button[type="submit"]').before($classField);
    }
});

// ON PAGE LOAD
$(document).ready(function() {
    if ($('#user-create-form select[name="ulevel"]').val() === '2') {
        loadAvailableClassesDropdown();
    }

    loadUsers();

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

        // Nivel de usuario (admin, profesor, alumno)
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

        // Cargar clases disponibles (según si es profesor)
        if (currentUlevel === '2') {
            fetchAvailableClasses(currentClassId).then(function (select) {
                row.find('.class').html(select);

                // Reemplazar botones cuando todo esté cargado
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



    $(document).on('click', '.cancel-btn', function () {
        refreshAllTabs();
    });

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
