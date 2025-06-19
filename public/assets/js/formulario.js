
$(document).ready(function () {
    loadFormularioTable();
});

function loadFormularioTable() {
    $.get('/api/admin?action=getFormulario', function (html) {
        $('#formulario-table-container').html(html);
    }).fail(function (xhr) {
        $('#formulario-table-container').html(
            `<div class="alert alert-danger">Error al cargar formulario: ${xhr.statusText}</div>`
        );
    });
}

function handleDeleteFormTabBtnClick(e) {
    const row = $(this).closest('tr');
    const idformulario = row.data('id');
    if (confirm('¿Estás seguro de que quieres eliminar este mensaje?')) {
        $.post('/api/admin', {
            deleteForm: 1,
            idformulario: idformulario
        }).done(function (response) {
            console.log('Respuesta del servidor al eliminar mensaje formulario:', response);
            if (response.trim() === 'success') {
                refreshAllTabs();
            } else {
                console.error('Error en respuesta al eliminar mensaje formulario:', response);
                alert('Error al eliminar el mensaje de formulario');
            }
        }).fail(function (xhr) {
            console.error('Fallo al eliminar mensaje:', xhr.responseText);
            alert('Error al eliminar el mensaje');
        });
    }
}

$(document).on('click', '.delete-formtab-btn', handleDeleteFormTabBtnClick);

