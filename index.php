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
            <a class="navbar-brand logo" href="index.php"><img src="pics/logo.png" alt="logo" id="Navbar_Logo"/></a>
            
          
            <!-- Links -->
            
            <ul class="navbar-nav list-color">
                <!--Home, Cursos, Contacto, Localización, Iniciar Sesión-->
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item dropdown list-color">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Cursos</a>
                    <div class="dropdown-menu Menu-Style" id="Menu-Cursos">
                          <a class="dropdown-item" href="#Sección-Inglés-Corporativo">Inglés corporativo</a>
                          <a class="dropdown-item" href="#Sección-Exámenes-Oficiales">Preparación exámenes oficiales</a>
                          <a class="dropdown-item" href="#Sección-Español-Extranjeros">Español para extranjeros</a>
                          
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
                
                
                <li class="nav-item">
                    <a class="nav-link" href="#Localización">Localización</a>
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
                    <div class="modal fade" id="login-modal" tabindex="-1"  aria-hidden="true" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                      
                                <!-- Modal Header -->
                                <div class="modal-header text-center">
                                  <h4 class="modal-title">Login</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                        
                                <!-- Modal body -->
                                <form id="login-form" name="login" role="form">
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
                                </div>
                        
                                <!-- Modal footer -->
                                <div class="modal-footer">
                                  <span id="login_error" class="text-danger"></span>
                                  <input type="submit" name="login_button" class="btn btn-success"  id="login_button"/>
                                  <!-- <button type="button" name="login_button" class="btn btn-success" id="login_button" onclick="Login()">Login</button> -->
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                                </form>
                            </div>
                         </div>
                     </div>
                  </div>   

     

        <!-- Nav code ends here, even if the modal form is technically outside of the nav, I consider it a part of the nav -->

        <div class="cuerpo">
            

            <h1 class="TítuloPágina">
                BUSINESS FIRST ENGLISH CENTER, tu academia de inglés corporativo 
            </h1>
            
        <img src="pics/new_york_office.png" alt="vista de Nueva York desde oficina" id="Title-Image"/>
        <p id="Contenido">
        Saber idiomas es hoy día imprescindible para conseguir llegar lo más arriba posible en el mundo empresarial.<br>
            Conoce la importancia del inglés corporativo, te abrirá una ventana al mundo.<br>
            Enseñaremos a tus trabajadores a hablar el inglés de los negocios. <br>
            Se podrán comunicar con confianza en cualquier situación. <br>
            Especialistas en cursos para empresas. <br>
        </p>

        </div>

        <div class="Row-Header">
        <h2>Servicios que ofrecemos</h2>
        </div>


        
        <div class="rows">
            
            <div class="Row-Even">
                <a id="Sección-Inglés-Corporativo" class="anchor"></a>                
                <h3>Inglés corporativo para empresas</h3>            
                <img src="pics/presentation.png" alt="office presentation" class="row-pic" />
                <p class="row-text" id="row-text">
                  <br>
                  <br>
                Contrata nuestros cursos adaptados a las necesidades de tu empresa.<br>
                Tus trabajadores no se encontrarán perdidos en una reunión, negociación, o presentación en inglés nunca más.<br>
                Formamos a tu equipo para que puedan interactuar y comunicarse con clientes de todo el mundo.<br>
                No te arrepentiras, tu inversión hará que tu negocio crezca.<br>
                Aprenderán de forma fácil, cómoda y sencilla<br>
                Somos la mejor solución para tu equipo.
                </p>
                <a class="panel-link" href="javascript:void(0)" onclick="window.location = 'ic.php'"></a>     
            </div>
            
            <div class="Row-Uneven">
                <a  id="Sección-Exámenes-Oficiales" class="anchor"></a>
                <h3>Preparación de exámenes oficiales</h3>
                <img src="pics/estudiantes1.png" alt="estudiantes universitarios" class="row-pic" />
                <p class="row-text">
                  <br>
                    Learn fast, expect the best. <br>
                    Learn english to: <br>
                    Attend a foreign University<br>
                    Get into the Erasmus Program<br>
                    Obtain an Official English Certificate<br>
                    Matricúlate con nosotros en nuestra academia y te prepararemos para que apruebes los certificados oficiales de: TOELF, TOEIC y Cambridge.
                   

                </p>
                <a class="panel-link" href="javascript:void(0)" onclick="window.location = 'examenes.php'"></a>
            </div>
            <div class="Row-Even">
                <a id="Sección-Español-Extranjeros" class="anchor"></a>
                <h3>Español para extranjeros</h3>
                <img src="pics/espanol.png" alt="foto de aprende español" class="row-pic" />
                <p class="row-text"><br><br>El español es uno de los idiomas más hablados en todo el mundo.<br>
                    Para dominar un idioma no es sufciente con hablarlo de oído, hay que conocer su estructura estudiando la gramática.<br>
                    Si eres extranjero y quieres poder comunicarte y viajar, o necesitas hablar español para avanzar en tu empresa.<br>
                    Aprende español con nosotros, matricúlate en nuestra Academia.<br>
                </p>
                <a class="panel-link" href="javascript:void(0)" onclick="window.location = 'clasesesp.php'"></a>
           
           


                
           
           
              </div>
        </div>


        <!-- Footer -->
        
<footer class="text-center footer-bg text-lg-start text-muted">
  <!-- Section: Social media -->
  <section
    class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom"
  >
    <!-- Left -->
    <div class="me-5 d-none d-lg-block">
      <span>Get connected with us on social networks:</span>
    </div>
    <!-- Left -->

    <!-- Right -->
    <div>
      <!-- Section: Social media -->
      <a href="" class="me-4 text-reset">
        <i class="fa fa-facebook"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fa fa-twitter"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fa fa-google"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fa fa-instagram"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fa fa-linkedin"></i>
      </a>
      
    </div>
    <!-- Right -->
  </section>
  

  <!-- Section: Links  -->
  <section class="">
    <div class="container text-center text-md-start mt-5">
      <!-- Grid row -->
      <div class="row mt-3">
        <!-- Grid column -->
        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
          <!-- Content -->
          <h6 class="text-uppercase fw-bold mb-4">
            <i class="fas fa-gem me-3"></i>Encuéntranos
          </h6>
          <a name="Localización" class="anchor"></a> 
          <p>
          <img src="pics/google_map.png" alt="" class="footer-map"/>  
          </p>
        </div>


        <!-- Grid column -->
       <!-- <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4"> -->
        <div class="col-lg-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            Contáctanos
          </h6>
          <p><i class="fa fa-home me-3"></i>Avd. De la Marina 52, Rota (Cádiz)</p>
          <p>
            <i class="fa fa-envelope me-3"></i>
            businessfirstenglish@gmail.com
          </p>
          <p><i class="fa fa-phone me-3"></i> + 34 983 542 740</p>
          <p><i class="fa fa-print me-3"></i> + 34 983 542 741</p>
        </div>
        <!-- Grid column -->
      </div>
      <!-- Grid row -->
    </div>
  </section>
  <!-- Section: Links  -->

  <!-- Copyright -->
  <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2022 Copyright:
    <a class="text-reset fw-bold" href="#Top">Business First English Academy</a>
  </div>
  <!-- Copyright -->
</footer>
<!-- Footer -->         

        <?php
        
        
        
        ?>
    </body>
</html>
