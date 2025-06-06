/**
 * @fileoverview
 * Common utility and event handler functions for the admin dashboard.
 * Handles modal events, form resets, window reloads, and dashboard tab refresh logic.
 *
 * @author Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license MIT
 */

/**
 * Refreshes all dashboard tabs by reloading their data.
 * Calls loadUsers, loadClasses, loadTeacherDropdown, loadNotas, and loadHorarios if they are defined.
 *
 * @function
 * @returns {void}
 */
function refreshAllTabs() {
    if (typeof loadUsers === "function") loadUsers();
    if (typeof loadClasses === "function") loadClasses();
    if (typeof loadTeacherDropdown === "function") loadTeacherDropdown();
    if (typeof loadNotas === "function") loadNotas();
    if (typeof loadHorarios === "function") loadHorarios();
}

/**
 * Handles the login form submission.
 * Sends username and password via AJAX POST to login.php.
 * Displays error messages on failure and reloads the page on success.
 *
 * @event submit
 * @param {Event} e - The submit event (captured implicitly).
 * @returns {boolean} false to prevent default form submission.
 */
function handleLoginFormSubmit() {
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
}

/**
 * Event handler for when the login modal is hidden.
 * Resets the login form.
 *
 * @event hidden.bs.modal
 * @param {Event} e - The event object.
 */
function handleLoginModalHidden(e) {
    // login_error.innerHTML = "";
    $("#login-form")[0].reset();
}

// Document ready: bind all event handlers
$(document).ready(function () {
    $('#login-modal').on('show.bs.modal', handleLoginModalShow);
    $('#login-modal').on('hidden.bs.modal', handleLoginModalHidden);
    $('#login-form').on('submit', handleLoginFormSubmit);
});