$(document).ready(function(){
//CURRENT DAY WORK

    //when the modal is activated---------------------------------------
    $('#login-modal').on('show.bs.modal', function(e){

    });

    $('#login-modal').on('hidden.bs.modal', function(e){
        //login_error.innerHTML = "";
        $("#login-form")[0].reset();

    });



}); //$(document).ready(function() END
//PAGE LOAD END


// }

function closeWindow(){
    location.reload();
}


function resetForm(){
    $("#entryform")[0].reset();
}

// DASHBOARD FUNCTIONS ///////////////////


//          This function runs everytime the dashboard has any kind of change
function refreshAllTabs() {
    if (typeof loadUsers === "function") loadUsers();
    if (typeof loadClasses === "function") loadClasses();
    if (typeof loadTeacherDropdown === "function") loadTeacherDropdown();
    if (typeof loadNotas === "function") loadNotas();
    if (typeof loadHorarios === "function") loadHorarios();
}
