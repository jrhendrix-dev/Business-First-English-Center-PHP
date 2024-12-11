
<!DOCTYPE html>

<html>
    <head>
        <!--width=device-width sets the width of the page to the screen-width of the device and initial-scale=1 sets the initial zoom level when the page is first loaded-->
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
        <title>Academy</title>
       
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">     
       
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!-- Latest compiled JavaScript -->        
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
           
        <!-- Index.php JS File -->
        <script src="js/index.js"></script>
        <!-- Icon Library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- My Personal CSS Sheet -->
        <link href="css/index.css" rel="stylesheet" type="text/css"/>
      


    </head>

    <body>

<?php 
        include ('db.php');  
        include ('functions.php');
        session_start();    

       
  ?>
 

        <!--NAVBAR START-->
        <a name="Top" class="anchor"></a>       
        <nav class="navbar navbar-expand-sm navbar-dark justify-content-sm-start sticky-top" id="Navegación">
            <!--Brand/Logo-->
            <a class="navbar-brand" href="index.php"><img src="pics/logo.png" alt="" id="Navbar_Logo"/></a>
            
          
            <!-- Links -->
            
            <ul class="navbar-nav list-color">
                <!--Home, Cursos, Contacto, Localización, Iniciar Sesión-->
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item dropdown list-color">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Cursos</a>
                    <div class="dropdown-menu Menu-Style" id="Menu-Cursos">
                          <a class="dropdown-item" href="javascript:void(0)" onclick="window.location = 'ic.php'">Inglés corporativo</a>
                          <a class="dropdown-item" href="javascript:void(0)" onclick="window.location = 'examenes.php'">Preparación exámenes oficiales</a>
                          <a class="dropdown-item" href="javascript:void(0)" onclick ="window.location ='clasesesp.php'">Español para extranjeros</a>
                          
                    </div>
                </li>

                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="Navbar-Contacto">Contacto</a>
                    <div class="dropdown-menu Menu-Style">
                            <span class="dropdown-item">Tlf: 983 542 740</span>
                            <span class="dropdown-item">businessfirstenglish@gmail.com</span>
                            <span class="dropdown-item">Avd. De la Marina 52, Rota (Cádiz)</span>
                            <!--
                            <a class="dropdown-item" href="#">Tlf: 983 542 740</a> 
                            <a class="dropdown-item" href="#">rotabusinessenglish@ymail.com</a> 
                            <a class="dropdown-item" href="#"> Avd. De la Marina 52, Rota (Cádiz)</a>
                            -->
                    </div> 
                </li>
                
                

               
                <!-- LOGIN -->
                <?php if(check_login() === true):?>
                    <!-- insert logged in nav here-->
                            <!-- if admin -->
                            <?php if($_SESSION["lvl"] == 1){  ?>  
                          <li class="nav-item">
                              <a class="nav-link" href="admin.php">Administrador</a>
                          </li>
                              <!-- if teacher -->
                          <?php } elseif($_SESSION["lvl"] == 2){ ?>
                            <li class="nav-item">
                              <a class="nav-link" href="teacher.php">Profesor</a>                             
                            </li>    
                              <!-- if student -->
                              <?php }else{ ?> 
                                <li class="nav-item">
                                  <a class="nav-link" href="student.php">Estudiante</a>
                                  
                                </li>
                            

                            <?php } ?>

                 <li class="nav-item">
                     <a class="nav-link" href="logout.php">
                         <?php
                         echo $_SESSION["user"];?>(logout)
                         
                     </a> 
                 </li>
                
                <?php else: ?>    
                  <!-- nav if not loggged in: -->
                  <li class="nav-item">
                  <!-- The Button -->
                  <a class="nav-link modal-button" id="login" data-toggle="modal" data-target="#login-modal" href="#">Iniciar Sesión</a>
                    
              
                  </li>
                <?php    endif; ?>
                
                
            </ul>
              
        </nav>
        
            <!-- The Modal login, has to be outside the navbar -->
        <div class="container">
        <div class="modal fade" id="login-modal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
                      
        <!-- Modal Header -->
        <div class="modal-header text-center">
            <h4 class="modal-title">Login</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <form id="login-form" name="login" role="form" onsubmit="return test()">
        <div class="modal-body">
            <!-- <div class="form-group"> -->
            <label>Name</label>
            <!-- <i class="fa fa-user prefix grey-text"></i> -->
            <input type="text" name="username" id="username" placeholder="Username..." class="form-control" required />
            
            <br>
            <!-- </div> -->
            <!-- <div class="form-group"> -->
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password..." />
            

            <!-- </div> -->
        </div><!--Modal-body End-->

        <!-- Modal footer -->
        <div class="modal-footer">
            <span id="login_error" class="text-danger"></span>
            <input type="submit" name="login_button" class="btn btn-success"  id="login_button"/>
            <!-- <button type="button" name="login_button" class="btn btn-success" id="login_button" onclick="Login()">Login</button> -->
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div><!--Modal footer end-->
        </form>
       
        </div> <!-- modal-content End-->
        </div> <!-- modal dialog End -->
        </div>   <!-- login-modal End -->

        </div><!-- Container End -->

        <!-- Nav code ends here, even if the modal form is technically outside of the nav, I consider it a part of the nav -->










        <h1 class="TítuloPágina">PREPARACIÓN PARA EXÁMENES OFICIALES</h1>
<div class="wrapper">
        <div class="pageContent flex-column info-column">    
                
                <br>

                <h2 class="TítuloMenor">Cursos de preparación a EO dirigidos a:</h2>

                <ul>
                    <li>Estudiantes que quieren acceder a Universidades extranjeras.</li>
                    <li>Estudiantes que quieren participar en el programa Erasmus.</li>
                    <li>Trabajadores que necesitan el certificado oficial de idiomas.</li>
                </ul>
<br>                
                <h2 class="TítuloMenor">Modalidades de clases:</h2>
                <ul>
                    <li>Preparación para exámenes oficiales de: TOELF, TOEIC, SAT y Cambridge.</li>
                    <li>Grupos reducidos y agrupados según el nivel.</li>
                    <li>Cursos anuales o intensivos en verano.</li>
                    <li>Simulacros de exámenes para analizar tu progreso.</li>
                </ul>
<br>
                <h2 class="TítuloMenor">La academia proporciona:</h2>
                <ul>
                    <li>Profesores nativos cualificados</li>
                    <li>Tecnologías punta como: pizarras electrónicas, portátiles, wifi, etc.</li>
                    <li>Instalaciones amplias, cómodas y con un ambiente agradable</li>
                    <li>Métodos modernos, dinámicos y con resultados rápidos</li>
                </ul>
                
                <h2 class="TítuloMenor">Niveles de preparación de inglés que ofrecemos:</h2>
                <ul>
                    <li>A2 Level (Basic)</li>
                    <li>B2 Level (Intermediate)</li>
                    <li>B1 Level (Medium)</li>
                    <li>C1 Level (Advanced)</li>
                </ul>

                <br>
                <ul>
                    <li id="Bulletchange">Solicita información rellenando el formulario con tus datos, nos pondremos en contacto contigo
                        para informarte<br> sobre nuesros precios, niveles, horarios, modalidades, etc.
                    Responderemos a todas tus preguntas y podrás visitar<br> nuestras instalaciones. </li>
                </ul>
                
                
                <table class="table table-bordered infotable">
                    <thead>
                        <tr>
                            <th colspan="2"><h2>Formulario</h2></th>
                        </tr>
                                        
                    </thead>
                    <tbody>
                        <form id="entryform">
                        <tr>
                            <th>Nombre:</th>
                            <td>
                                <input type="text" id="form_nombre" maxlength="20" class="entry-form-input" required>
                                <span id="nombremsg" class="text-danger"></span>
                            </td>                            
                        </tr>
                        <tr>
                            <th>Apellidos:</th>
                            <td>
                                <input type="text" id="form_apellidos" maxlength="20" class="entry-form-input" required>
                                <span id="apellidosmsg" class="text-danger"></span>
                            </td>                            
                        </tr>  
                        <tr>
                            <th>Teléfono:</th>
                            <td>
                            <input type="tel" id="form_tel" maxlength="9" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}"  class="entry-form-input" onkeyup="this.value = this.value.replace(/[^0-9-]/g, '');" required><br>
                            <span id="telmsg" class="text-danger"></span>                               
                            </td>                            
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>
                                <input type="email" id="form_email" class="entry-form-input" required><br>
                                <span id="emailmsg" class="text-danger"></span>
                            </td>                            
                        </tr>
                        <tr>
                            <th>Mensaje:</th>
                            <td><input type="text" id="form_msg" maxlength="50" class="entry-form-input"></td>                            
                        </tr>                                                                                            
                    </tbody>       
                    <tfoot>
                        <tr>
                            <th colspan="2">
                                <div class="container">
                                <button type="button" name="ic_submit_button" class="btn btn-success" id="entryform_submit_button" onclick="forminsert();">Enviar</button>
                                <input type="reset" class="btn btn-danger"/><br>
                                <span id="form_log" class="text-danger"></span>      
                                </div>               
                            </th>
                        </tr>
                        </form>
                    </tfoot>          
                </table>
        </div> <!--Div pagecontent End-->   


        <div class="pageSide">
                <img src="pics\estudiantes.png" alt="equipo de estudiantes"/>
                <p>Learn fast, expect the best.</p>
                <br>
                <img src="pics\estudiantes de inglés.png" alt="estudiantes de inglés"/>    
                <p>Estudia con nosotros.<br>
                   Invierte en tu futuro, no te arrepentirás.</p>
        </div><!-- DIV pageside end-->

</div> <!-- Div Wrapper End-->       

</body>
</html>