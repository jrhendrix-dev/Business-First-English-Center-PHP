
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
