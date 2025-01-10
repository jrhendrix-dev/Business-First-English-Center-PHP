
//https://www.codemag.com/article/1511031/CRUD-in-HTML-JavaScript-and-jQuery
//declared outside of any function so that its available to any function
var _row= null;
var rowsopen= null;



//THESE FUNCTIONS PERFORM ON PAGE LOADED
$(document).ready(function(){
   

    $('#login-form').on('submit', function(){
        
        var username = $('#username').val();
        var password = $('#password').val();
        var url = "login.php";
      //  alert("user: " + username);

        $.post(url, {username:username, password:password}, function(data){
            //alert(data);
            if (data == "No"){
                login_error.innerHTML = "Nombre o contraseña erróneo";
            }else{
                login_error.innerHTML = "Salió Bien";
                location.reload();
            }
        });
         return false;
    }); //end of login-form submit


    //ADMIN LINKS
    $("#link-usuarios").click(function(){
        $("#users-window").load(" #users-window > *");
        $(".db-usuarios").attr('hidden',false);
        $(".db-clases").attr('hidden',true);
        $(".db-horarios").attr('hidden', true);
        $(".db-notas").attr('hidden',true);

        rowNormal();
    });   
    $("#link-clases").click(function(){
        $("#class-window").load(" #class-window > *");
        $(".db-usuarios").attr('hidden',true);
        $(".db-clases").attr('hidden',false);
        $(".db-horarios").attr('hidden', true);
        $(".db-notas").attr('hidden',true);

        rowNormal();
    });    
    $("#link-horarios").click(function(){
        $("#horario-window").load(" #horario-window > *");
        $(".db-usuarios").attr('hidden',true);
        $(".db-clases").attr('hidden',true);
        $(".db-horarios").attr('hidden', false);
        $(".db-notas").attr('hidden',true);
        
        rowNormal();
    });    
    $("#link-notas").click(function(){
        $("#notas-window").load(" #notas-window > *");
        $(".db-usuarios").attr('hidden',true);
        $(".db-clases").attr('hidden',true);
        $(".db-horarios").attr('hidden', true);
        $(".db-notas").attr('hidden',false);

        rowNormal();
    });     

    //TEACHER LINKS & STUDENT LINKS
    $("#link-teacher").click(function(){
        $("#teacher-window").load(" #teacher-window > *");
        $("#teacher-window").attr('hidden',false);
        $("#horario-window").attr('hidden',true);
    });

    $("#link-horario").click(function(){
        $("#horario-window").load(" #horario-window > *");
        $("#teacher-window").attr('hidden',true);
        $("#horario-window").attr('hidden',false);
        $("#student-window").attr('hidden',true);       
    });

    
    $("#link-student").click(function(){
        $("#student-window").load(" #student-window > *");
        $("#student-window").attr('hidden',false);
        $("#horario-window").attr('hidden',true);
    });

   
   //when the modal is activated
    $('#login-modal').on('show.bs.modal', function(e){
        
    });

    $('#login-modal').on('hidden.bs.modal', function(e){
        login_error.innerHTML = "";
        $("#login-form")[0].reset();
           
    });

    $(".resetBtn").click(function(){
        
        nombremsg.innerHTML="";
        apellidosmsg.innerHTML="";
        telmsg.innerHTML="";
        emailmsg.innerHTML="";

        
    });

    // $("#login").click(function(){
    //     alert("hi");
    //     $_SESSION["msg"]="";
    // });

    // if($("#login-modal").modal(data-focus)=false){
    //     alert("hi");
    //     $("#login-form")[0].reset();
    // }

}); //$(document).ready(function() END
//PAGE LOAD END

function rowDelete(ctl,ctl_window){


    if(ctl_window=="users"){
        // $(ctl).parents("tr").remove();
        var user_id = $("#edit_id" + ctl).val();
        var level = $("#edit_level" + ctl).val();
        var url = "delete.php";

       

        $.post(url,{user_id:user_id, ulevel:level}, function(data){
            if ( data == "No"){
                editUser_error.innerHTML = "Error";
            }else{
                // $("#db-usuarios").load("admin.php" + "#db-usuarios");       
                        // $(ctl).parents("tr").reload();             
                        // $("#users-window").reload();


                        // https://www.codegrepper.com/code-examples/javascript/refresh+page+after+ajax+call
                        //This code loads only the DIV specified
                        $("#users-window").load(" #users-window > *");
                        $("#class-window").load(" #class-window > *");
                    //keep the div unhidden on reload
                        $(".db-usuarios").attr('hidden',false);
                        // editUser_error.innerHTML = "Actualizado con éxito";
                        error_msg.innerHTML = "Eliminado con éxito";
                        // location.reload();

            }


        });
    }//if ctl_window=users end

    if(ctl_window=="clases"){
        var classid = $("#edit_classid" + ctl).val();
        // var teacher = $("#edit_classteacher" + ctl).val();
        var url = "delete.php";

        $.post(url,{classid:classid}, function(data){
            if ( data == "No"){
                error_msg.innerHTML = "Eliminado con éxito";
               
            }else{

                $("#users-window").load(" #users-window > *");
                $("#class-window").load(" #class-window > *");
                $(".db-clases").attr('hidden',false);
                error_msg.innerHTML = "Eliminado con éxito";

            }
        });

    }

}//row delete end

// function getRank(ctl){
//     if(ctl == 1){
//         return "admin";
//     }else{
//         if(ctl == 2){
//             return "profesor";
//         }else{
//             return "estudiante";
//         }
//     }
// }

function closeWindow(){
    location.reload();
    
}

function rowEdit(ctl, ctl_window){
//    alert("row: " + ctl);
$(".formEdit-control").attr('hidden',true);



  



rowNormal();

    if(ctl_window=="users"){
        // if(ctl_window.classList.contains(users)){
        //    $("#edit_id" + ctl).attr('hidden',false);
        $("#edit_user" + ctl).attr('hidden',false);
        $("#edit_email" + ctl).attr('hidden',false);
        $("#edit_password" + ctl).attr('hidden',false);
        $("#edit_cdate" + ctl).attr('hidden',false);
        $("#edit_level" + ctl).attr('hidden',false);
        $("#edit_class" + ctl).attr('hidden',false);
        $("#update_button" + ctl).attr('hidden', false);
        $("#editIcon" + ctl).attr('hidden',true);

    
    
    }

    if(ctl_window=="clases"){
       
        $("#edit_classname" + ctl).attr('hidden',false);
        $("#edit_classteacher" + ctl).attr('hidden',false);
        $("#editClassIcon" + ctl).attr('hidden', true);
        $("#updateClass_button" + ctl).attr('hidden', false);


    }

    if(ctl_window=="horarios"){
        $("#edit_class1_"+ ctl).attr('hidden',false);
        $("#edit_class2_"+ ctl).attr('hidden',false);
        $("#edit_class3_"+ ctl).attr('hidden',false);
        $("#updateHorario_button" + ctl).attr('hidden',false);
        $("#editHorarioIcon" + ctl).attr('hidden',true);

    }

    if(ctl_window=="notas"){

        $("#edit_nota1_" + ctl).attr('hidden',false);
        $("#edit_nota2_" + ctl).attr('hidden',false);
        $("#edit_nota3_" + ctl).attr('hidden',false);
        $("#editNotasIcon" + ctl).attr('hidden',true);
        $("#updateNotas_button" + ctl).attr('hidden',false);
    }
   
 
}//end rowEdit


//NEW USER FORM ic, clasesesp, exámenes
function forminsert(){
    
    var name = $("#form_nombre").val();
    var lastname = $("#form_apellidos").val();
    var teléfono = $("#form_tel").val();
    var email = $("#form_email").val();
    var mensaje = $("#form_msg").val();

    if(name==""){
        nombremsg.innerHTML = "Por favor rellena este campo";
        
    }else{
        nombremsg.innerHTML= "";
    }

    if(lastname==""){
        apellidosmsg.innerHTML = "Por favor rellena este campo";
    }else{
        apellidosmsg.innerHTML= "";
    }

    if(teléfono==""){
        telmsg.innerHTML = "Por favor rellena este campo";
    }else{
        telmsg.innerHTML= "";
    }

    if(email==""){
        emailmsg.innerHTML = "Por favor rellena este campo";
    }else{
        emailmsg.innerHTML= "";
    }

    if(name=="" || lastname=="" || teléfono=="" || email==""){
        return;
    }

    var url = "insert.php"

        $.post(url, {nombre:name, apellidos:lastname, teléfono:teléfono, email:email, mensaje:mensaje},function(data){
            if(data == "No"){
                form_log.innerHTML= "Ha habido un error";
                // alert("no");
            }else{
                form_log.innerHTML = "¡Gracias por confiar en nosotros!";

                 resetForm();             
                

            }

        });
}

function resetForm(){
    
    $("#entryform")[0].reset();

}



function rowUpdate(ctl, ctl_window){



    if(ctl_window=="users"){
       
            var user_id = $("#edit_id" + ctl).val();
            var username = $("#edit_user" + ctl).val();
            var email = $("#edit_email" + ctl).val();
            var pw = $("#edit_password" + ctl).val();
            var cdate = $("#edit_cdate" + ctl).val();
            var level = $("#edit_level" + ctl).val();
            var uclass = $("#edit_class" + ctl).val();
            
            
            var url = "update.php"


            
            if(username==" " || username==""){
                userediterror.innerHTML = "Rellenar";
               
            }
            if(email==" " || email==""){
                usereditemailerror.innerHTML = "Rellenar";
            }
            if(pw==" " || pw==""){
                usereditpwerror.innerHTML = "Rellenar";
            }

            if(username=="" || email=="" || pw=="" || username==" " || email == " " || pw == " "){
                return;
            }

                    $.post(url, {user_id:user_id, username:username, email:email, pword:pw, create_time:cdate, ulevel:level, class:uclass}, function(data){
                    
                    
                        if( data == "No"){
                            editUser_error.innerHTML = "Error";
                        }else{
                            
                            // $("#db-usuarios").load("admin.php" + "#db-usuarios");       
                            // $(ctl).parents("tr").reload();             
                            // $("#users-window").reload();


                            // https://www.codegrepper.com/code-examples/javascript/refresh+page+after+ajax+call
                            //This code loads only the DIV specified
                            
                            $("#users-window").load(" #users-window > *");
                            $("#class-window").load(" #class-window > *");
                        //keep the div unhidden on reload
                            $(".db-usuarios").attr('hidden',false);
                            // editUser_error.innerHTML = "Actualizado con éxito";
                            error_msg.innerHTML = "Actualizado con éxito";
                            // location.reload();
                            
                        
                        }
                    });

    }   //end users
    
    if(ctl_window=="clases"){
        var class_id = $("#edit_classid" + ctl).val();
        var classname = $("#edit_classname" + ctl).val();
        var teacher = $("#edit_classteacher" + ctl).val();

        var url = "update.php";

       

        if(classname==""){
            
            Edit_classname_log.innerHTML = "Rellenar";
            return;
        }
        
            $.post(url, {classid:class_id, classname:classname, username:teacher}, function(data){
                if (data == "No"){
                    
                }else{
                    
                    // $("#users-window").load(" #users-window > *");
                    $("#class-window").load(" #class-window > *");
                    $(".db-clases").attr('hidden',false);
                    // $("#users-window").attr('hidden',true);
                   
                    error_msg.innerHTML = "Actualizado con éxito";
                    
                }

            });
   
    }//end clases

    if(ctl_window=="horarios"){
        var day_id = $("#edit_horarioid" + ctl).val();
        var week_day = $("#edit_horarioday" + ctl).val();
        var firstclass = $("#edit_class1_" + ctl).val();
        var secondclass = $("#edit_class2_" + ctl).val();
        var thirdclass = $("#edit_class3_" + ctl).val();

        

        // alert("first: " + firstclass + " second: " + secondclass + " third: " + thirdclass);

        var url = "update.php";
        

            $.post(url, {day_id:day_id, week_day:week_day, firstclass:firstclass, secondclass:secondclass, thirdclass:thirdclass}, function(data){
                if (data == "No"){

                }else{

                    $("#horario-window").load(" #horario-window > *");
                    $(".db-horarios").attr('hidden',false);

                    error_msg.innerHTML = "Actualizado con éxito";

                }

            });
    }//end horarios

    if(ctl_window=="notas"){
        var idNotas = $("#edit_notasid" + ctl).val();
        var Notas1 = $("#edit_nota1_" + ctl).val();
        var Notas2 = $("#edit_nota2_" + ctl).val();
        var Notas3 = $("#edit_nota3_" + ctl).val();

        var url = "update.php";

        $.post(url, {idAlumno:idNotas, Nota1:Notas1, Nota2:Notas2, Nota3:Notas3},function(data){
            if (data == "No"){

            }else{
                $("#notas-window").load(" #notas-window > *");
                $(".dbnotas").attr('hidden',false);

                error_msg.innerHTML = "Actualizado con éxito";
            }

        });
    }//end notas
    // alert(ctl_window);
    if(ctl_window=="teacher"){
        
        var idNotas = $("#edit_notasid" + ctl).val();
        var Notas1 = $("#edit_nota1_" + ctl).val();
        var Notas2 = $("#edit_nota2_" + ctl).val();
        var Notas3 = $("#edit_nota3_" + ctl).val();

        
        
        var url = "update.php";

        $.post(url, {idAlumno:idNotas, Nota1:Notas1, Nota2:Notas2, Nota3:Notas3},function(data){
            if (data == "No"){

            }else{
                $("#teacher-window").load(" #teacher-window > *");
                // alert("hi");
                // location.reload();
                $("#teacher-window").load(" #teacher-window > *");
               
                

                error_msg.innerHTML = "Actualizado con éxito";
            }

        });
    }

}//rowUpdate End



function rowAdd(ctl_window){
    if(ctl_window=="users"){
            var user_id = $("#add_id").val();
            var username = $("#add_user").val();
            var email = $("#add_email").val();
            var pw = $("#add_password").val();
            var cdate = $("#add_cdate").val();
            var level = $("#add_level").val();
            var uclass = $("#add_class").val();

            var url = "insert.php";

            if(username==""){
                useradderror.innerHTML = "Rellenar";
               
            }
            if(email==""){
                useraddmailerror.innerHTML = "Rellenar";
            }
            if(pw==""){
                useraddpwerror.innerHTML = "Rellenar";
            }

            if(username=="" || email=="" || pw==""){
                return;
            }

            $.post(url, {user_id:user_id, username:username, email:email, pword:pw, create_time:cdate, ulevel:level, class:uclass}, function(data){

                if ( data == "No"){
                    alert("no");
                }else{
                    
                    // https://www.codegrepper.com/code-examples/javascript/refresh+page+after+ajax+call
                            //This code loads only the DIV specified
                            $("#users-window").load(" #users-window > *");
                            $("#class-window").load(" #class-window > *");
                        //keep the div unhidden on reload
                            $(".db-usuarios").attr('hidden',false);
                            // editUser_error.innerHTML = "Actualizado con éxito";
                        
                            error_msg.innerHTML = "Añadido con éxito";
                        
                            // location.reload();

                }



            });    
        }//end users

    if(ctl_window=="clases"){
        var classname = $("#add_classname").val();
        var teachername = $("#add_classteacher").val();
        
        
        var url = "insert.php";

       

        if(classname==""){
            add_classname_log.innerHTML="Rellenar";
            return;
        }



        $.post(url, {classname:classname, username:teachername}, function(data){

            if ( data == "No"){
                alert("no");
            }else{
                $("#users-window").load(" #users-window > *");
                $("#class-window").load(" #class-window > *");
                //keep the div unhidden on reload
                $(".db-clases").attr('hidden',false);
                    // editUser_error.innerHTML = "Actualizado con éxito";
                
                    error_msg.innerHTML = "Añadido con éxito";

            }

        });

    }    
}

function rowNormal(){
    $(".editIcon").attr('hidden',false);
    $(".update_button").attr('hidden', true);
    $(".formEdit-control").attr('hidden',true);


}//rowNormal End


