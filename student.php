
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

        if(isset($_SESSION["msg2"])===false){
            $_SESSION["msg2"]="";
        }

                      


       
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

<div class="wrapper">

        <nav id="sidebar" class="navbar sidebar">
            <div>
                    <h3 id="side-title">Alumno</h3>
                    <ul class="navbar-nav sidebar list-color flex-column">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#" id="link-student">Notas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#" id="link-horario">Horario</a>
                        </li>
                    </ul>

            </div>
        </nav>


        <div class="content">
        <?php      
        if(check_login() === true){
                if($_SESSION["lvl"] == 3){//if STUDENT
                    ?>Welcome <?php echo $_SESSION["user"]; ?>. Click here to <a href="logout.php">logout</a>
        <?php
        // $curso=$_SESSION["curso"];
        $user=$_SESSION["user"];


                                                                                                                //   $query = "SELECT class from users where user_id='$teacherID'";

        // $query = "SELECT * FROM notas INNER JOIN users ON notas.idAlumno=users.user_id AND users.ulevel='3' INNER JOIN clases ON notas.idClase=clases.classid WHERE notas.idClase=users.class AND users.user_id='$teacherID'";
        $query = "SELECT * FROM notas INNER JOIN users ON notas.idAlumno=users.user_id AND users.ulevel='3' INNER JOIN clases ON notas.idClase=clases.classid AND users.username='$user'"; //change the 4 to the actual variable

        if(mysqli_query($con, $query)){
            echo "";
        }else{
            echo "error";
        }

        $count = 1;

        $result = mysqli_query($con, $query);




        ?>
            
            <div class="db-window">
                <div id="student-window">
                    <!-- Header -->
                    <div>
                        <h3>ALUMNO</h3>
                    </div>
                    <!-- Body -->
                    <div class="container">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th colspan="7" class="table-title"><h2>NOTAS: <?php echo $_SESSION['user'] ?></h2></th>
                                    </tr>
                                    <tr>                                                          
                                        <th>Alumno</th>
                                        <th>Curso</th>
                                        <th>Primer Trimestre</th>
                                        <th>Segundo Trimestre</th>
                                        <th>Tercer Trimestre</th>
                                    </tr>
                                </thead>
                              

                                <?php 
                                
                                if (mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_assoc($result)){
                                        $nombre_curso = $row['classname'];
                                        $curso = $row['class'];
                                        
                                    ?>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php echo $row['username']; ?>
                                            <input type="text" name="edit_studentname" id="edit_studentnameNotas<?php echo $count; ?>" value="<?php echo $row['username']; ?>" class="form-control formEdit-control notasEdit" hidden />
                                          
                                        
                                        </td>
                                        <td>
                                            <?php echo $row['classname']; ?>
                                        
                                            
                                        </td>
                                        <td>
                                            <?php echo $row['Nota1']; ?>
                                            
                                        </td>
                                        <td>
                                            <?php echo $row['Nota2']; ?>
                                            
                                        </td>
                                        <td>
                                            <?php echo $row['Nota3']; ?>
                                            
                                        </td>

                                    </tr>
                                </tbody>   
                                    <?php        
                                    $count++;
                                    }//End While
                                
                                }else{//if rows > 0
                                    echo "0 results";
                                }
                                ?>

                            </table>
                        </div>
                    </div><!-- Body Container End -->

                </div> <!--student-window end-->   

                <div id="horario-window" hidden>
                        <div><!-- HEADER -->
                                <h3>ALUMNO</h3>
                        </div>
                        <!-- BODY -->
                        <div class="container">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th colspan="5" class="table-title" id="table-title"><h2>CURSO DE: <?php echo $nombre_curso; ?></h2></th>
                                            </tr>
                                            <tr>
                                                <th>Día ID</th>
                                                <th>Día</th>
                                                <th>Primera Clase</th>
                                                <th>Segunda Clase</th>
                                                <th>Tercera Clase</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                                <th>Día ID</th>
                                                <th>Día</th>
                                                <th>Primera Clase</th>
                                                <th>Segunda Clase</th>
                                                <th>Tercera Clase</th>
                                        </tfoot>

                                    <?php
                                            $query = "SELECT * FROM schedule WHERE firstclass='$curso' OR secondclass='$curso' OR thirdclass='$curso'";
                                            if (mysqli_query($con, $query)){
                                                echo "";
                                             }else{
                                                 echo "Error";
                                             }

                                             $count = 1;
                                             $result = mysqli_query($con,$query);

                                             if(mysqli_num_rows($result) > 0){
                                                    while($row = mysqli_fetch_assoc($result)){
                                                         
                                                        
                                                        ?>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <?php echo $row['day_id']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['week_day']; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($row['firstclass']==$curso){
                                                     echo $nombre_curso;
                                                     
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($row['secondclass']==$curso){
                                                     echo $nombre_curso; 
                                                    }                                                    
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($row['thirdclass']==$curso){
                                                        echo $nombre_curso;
                                                    }    
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                                     <?php 
                                                     $count++;  
                                                    }//end while
                                             }else{
                                                 echo "No results";
                                             }
                                    ?>

                                    </table>
                                </div><!-- table-responsive end -->
                        </div> <!-- container end -->
                </div>


                <span id="error_msg" class="text-danger"><?php echo $_SESSION["msg2"]; ?></span>               
            </div> <!--db-window end-->







                <?php }else{//if other user
                    exit();
                }
        }//end if check login true  
                                ?>    
        </div><!-- End of content div -->

</div><!--wrapper end-->        

</body>
</html>