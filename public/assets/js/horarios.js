function loadHorarios() {
    $.get('dashboard_admin.php?loadHorarios=1', function (data) {
        $('#horarios-table-container').html(data);
    }).fail(function (xhr) {
        console.error('Error al cargar horarios:', xhr.responseText);
    });
}

$(document).ready(function () {
    loadHorarios();

    $(document).on('click', '.edit-horario-btn', function () {
        const row = $(this).closest('tr');

        ['firstclass', 'secondclass', 'thirdclass'].forEach(col => {
            const originalText = row.find('.' + col).text().trim();
            const currentOption = window.classOptions;
            const select = $('<select class="form-control"></select>').append(currentOption);

            // Intenta seleccionar la opción por texto visible
            select.find('option').filter(function () {
                return $(this).text().trim() === originalText;
            }).prop('selected', true);

            row.find('.' + col).html(select);
        });

        row.find('.edit-horario-btn').addClass('d-none');
        row.find('.save-horario-btn, .cancel-horario-btn').removeClass('d-none');
    });


    $(document).on('click', '.cancel-horario-btn', function () {
        refreshAllTabs();
    });

    $(document).on('click', '.save-horario-btn', function () {
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
    });

});
