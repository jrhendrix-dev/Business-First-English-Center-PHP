
$(document).ready(function() {
    $('#login-form').on('submit', function(){
        var username = $('#username').val();
        var password = $('#password').val();

        $.post('login.php', {username, password}, function(response){
            if (response.success) {
                location.reload();
            } else {
                $('#login_error').text(response.message);
            }
        }, 'json').fail(function() {
            $('#login_error').text("Error de conexión con el servidor.");
        });

        return false;
    });
});
