/**
 * @fileoverview
 * Common utility and event handler functions for the admin dashboard.
 * Handles modal events, form resets, window reloads, login lockout countdown,
 * and dashboard tab refresh logic.
 *
 * @author Jonathan Ray Hendrix <jrhendrixdev@gmail.com>
 * @license MIT
 */

//////////////////////////////
// Global Variables
//////////////////////////////

/**
 * Stores the interval ID for the login lockout countdown timer.
 * @type {number|null}
 */
let lockoutInterval = null;

//////////////////////////////
// Utility Functions
//////////////////////////////

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

//////////////////////////////
// Login Lockout Logic
//////////////////////////////

/**
 * Starts a countdown timer for login lockout after too many failed attempts.
 * Disables the login form inputs and updates the error message with the remaining time.
 * Re-enables the form when the countdown finishes.
 *
 * @function
 * @param {number} seconds - The number of seconds to lock out the login form.
 * @returns {void}
 */
function startLockoutCountdown(seconds) {
    clearInterval(lockoutInterval);
    let remaining = seconds;
    $('#login_error').text(`Demasiados intentos fallidos. Intenta de nuevo en ${remaining} segundos.`);
    $('#login-form :input').prop('disabled', true);

    lockoutInterval = setInterval(function() {
        remaining--;
        if (remaining > 0) {
            $('#login_error').text(`Demasiados intentos fallidos. Intenta de nuevo en ${remaining} segundos.`);
        } else {
            clearInterval(lockoutInterval);
            $('#login_error').text('');
            $('#login-form :input').prop('disabled', false);
        }
    }, 1000);
}

//////////////////////////////
// Event Handlers
//////////////////////////////

/**
 * Handles the login form submission.
 * Sends username and password via AJAX POST to login.php.
 * Displays error messages on failure and reloads the page on success.
 * If the server responds with a lockout, starts the lockout countdown.
 *
 * @event submit
 * @param {Event} e - The submit event.
 * @returns {boolean} false to prevent default form submission.
 */
function handleLoginFormSubmit(e) {
    e.preventDefault();
    var username = $('#username').val();
    var password = $('#password').val();

    $.post('login.php', {username, password}, function(response){
        if (response.success) {
            location.reload();
        } else {
            if (response.wait) {
                startLockoutCountdown(response.wait);
            } else {
                $('#login_error').text(response.message);
            }
        }
    }, 'json').fail(function() {
        $('#login_error').text("Error de conexión con el servidor.");
    });

    return false;
}

function handleLoginScreenFormSubmit(e) {
    e.preventDefault();
    var username = $('#login-screen-user').val();
    var password = $('#login-screen-password').val();

    $.post('login.php', {username, password}, function(response){
        if (response.success) {
            window.location.href = '/dashboard';
        } else {
            if (response.wait) {
                startLockoutCountdown(response.wait);
            } else {
                $('#login-screen-error').text(response.message);
            }
        }
    }, 'json').fail(function() {
        $('#login-screen-error').text("Error de conexión con el servidor.");
    });

    return false;
}

/**
 * Event handler for when the login modal is hidden.
 * Resets the login form, clears lockout countdown, and re-enables inputs.
 *
 * @event hidden.bs.modal
 * @param {Event} e - The event object.
 * @returns {void}
 */
function handleLoginModalHidden(e) {
    clearInterval(lockoutInterval);
    $('#login_error').text('');
    $("#login-form")[0].reset();
    $('#login-form :input').prop('disabled', false);
}

//////////////////////////////
// Document Ready: Bind Event Handlers
//////////////////////////////

/**
 * Binds event handlers for login modal and login form when the document is ready.
 */
$(document).ready(function () {
    $('#login-modal').on('hidden.bs.modal', handleLoginModalHidden);
    $('#login-form').on('submit', handleLoginFormSubmit);
    $('#login-screen-form').on('submit', handleLoginScreenFormSubmit);
});