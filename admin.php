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

        //Si no eres admin, te redirige al índice
        if(check_login()===true){
                if($_SESSION["lvl"] != 1){
                    header("Location:index.php");
                }
        }else{
            header("Location:index.php");
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


<!--side bar-->
<div class="wrapper">
    <nav id="sidebar" class="navbar sidebar">
                <!-- <h3 id="side-title">Admin Tools</h3> -->
        <div>
            <h3 id="side-title">Admin Tools</h3>
            <ul class="navbar-nav sidebar list-color flex-column">
                <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#" id="link-usuarios">Usuarios</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#" id="link-clases">Cursos</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#" id="link-horarios">Horarios</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#" id="link-notas">Notas</a>
                </li>

            </ul>
    
        </div>
        
        
    </nav>

  


    <!--side bar end-->





    <div class="content">

            <?php      
            if(check_login() === true){
                    if($_SESSION["lvl"] == 1){//if admin
                        ?>Welcome <?php echo $_SESSION["user"]; ?>. Click here to <a href="logout.php">logout</a>
          
        <div class="db-window">
            <div class="db-usuarios" id="users-window" hidden>
           

                            <!-- Header -->
                            <div class="">
                                <h4 class="">Usuarios</h4>
                                
                            </div> <!-- Header End-->
                            <!-- body -->
                            <div class="container">
                                    <div class="table-responsive">
                                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="9" class="table-title"><h2>USUARIOS REGISTRADOS</h2></th>
                                                            </tr>
                                                            <tr>
                                                                <th>User ID</th>
                                                                <th>Nombre</th>
                                                                <th>email</th>
                                                                <th>contraseña</th>
                                                                <th>Creación</th>
                                                                <th>Rango</th>
                                                                <th>Curso</th>
                                                                <th>Editar</th>
                                                                <th>Eliminar</th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                            <tr>
                                                                <th>User ID</th>
                                                                <th>Nombre</th>
                                                                <th>email</th>
                                                                <th>contraseña</th>
                                                                <th>Creación</th>
                                                                <th>Rango</th>
                                                                <th>Curso</th>
                                                                <th>Editar</th>
                                                                <th>Eliminar</th>
                                                            </tr>
                                                        </tfoot>

                                                
                                    </div>
                                            <?php

                                            $classQuery = "SELECT classname, classid FROM clases";
                                            if(mysqli_query($con,$classQuery)){
                                                echo"";
                                            }else{
                                                echo"Error";
                                            }
                                            $count=1;

                                           unset($classnameList);
                                           unset($classidList); 

                                            $resultClass = mysqli_query($con, $classQuery);

                                            while($row2 = mysqli_fetch_assoc($resultClass)){
                                                $classnameList[] = $row2['classname'];
                                                $classidList[] = $row2['classid'];
                                            }
                                            mysqli_free_result($resultClass);


                                            // $query = "SELECT * from users, clases WHERE clases.classid=users.class";
                                            $query = "SELECT * FROM users LEFT JOIN clases ON users.class=clases.classid";
                                            

                                            if(mysqli_query($con, $query)){
                                                echo "";
                                            }else{
                                                echo "Error: " . $query . "<br>" . mysqli_error($con);
                                            }

                                            $count=1;

                                            $result = mysqli_query($con, $query);

                                            if (mysqli_num_rows($result) > 0){
                                                //output data of each row

                                                while($row = mysqli_fetch_assoc($result)){ 
                                                       
                                                      //filtering the presentation of the level and class rows  
                                                        if($row['ulevel'] == 1){
                                                            $rank="admin";
                                                        }else{
                                                            if($row['ulevel'] == 2){
                                                                $rank="profesor";
                                                            }else
                                                            $rank = "estudiante";
                                                        }

                                                        // if($row['class'] == 1){
                                                        //     $class="B1";
                                                        // }else{
                                                        //     if($row['class'] == 2){
                                                        //         $class="B2";
                                                        //     }else{
                                                        //         if($row['class'] == 3){
                                                        //             $class="C1";
                                                        //         }else{
                                                        //             if($row['class'] == 4){
                                                        //                 $class="C2";
                                                        //             }else{
                                                        //                 $class="ninguna";
                                                        //             }
                                                                    
                                                        //         }
                                                        //     }
                                                        // }


                                                        ?>
                                                    <tbody>
                                                        <tr id="edit_<?php echo $count; ?>">
                                                            <td id="edit2_id<?php echo $count; ?>">
                                                                <?php echo $row['user_id']; ?>
                                                                <input type="number" name="edit_id" id="edit_id<?php echo $count; ?>" value="<?php echo $row['user_id']; ?>" class="form-control formEdit-control UserEdit" hidden required/>
                                                            </td>

                                                            <td>
                                                                <?php echo $row['username']; ?>
                                                                <input type="text" name="edit_user" id="edit_user<?php echo $count; ?>" value="<?php echo $row['username']; ?>" class="form-control formEdit-control" hidden required />
                                                                <span id="userediterror" class="text-danger"></span>
                                                            </td>

                                                            <td>
                                                                <?php echo $row['email']; ?>
                                                                <input type="email" name="edit_email" id="edit_email<?php echo $count; ?>" value="<?php echo $row['email']; ?>" class="form-control formEdit-control" hidden required />
                                                                <span id="usereditemailerror" class="text-danger"></span>
                                                            </td>

                                                            <td>
                                                                <?php echo $row['pword']; ?>
                                                                <input type="password" name="edit_password" id="edit_password<?php echo $count; ?>"value="<?php echo $row['pword']; ?>" class="form-control formEdit-control" hidden required />
                                                                <span id="usereditpwerror" class="text-danger"></span>
                                                            </td>

                                                            <td>
                                                                <?php echo $row['create_time']; ?>
                                                                <input type="text" name="edit_cdate" id="edit_cdate<?php echo $count; ?>" value="<?php echo $row['create_time']; ?>" class="form-control formEdit-control" hidden required />
                                                            </td>

                                                            <td>
                                                                <?php echo $rank; ?>
                                                                <select name="edit_level" id="edit_level<?php echo $count; ?>" value="<?php echo $row['ulevel'] ?>" class="form-control formEdit-control" hidden required>
                                                                    <?php 
                                                                    if($rank=="admin"){
                                                                        ?>
                                                                        <option value=1 selected>Administrador</option>
                                                                        <option value=2>Profesor</option>
                                                                        <option value=3>Estudiante</option>
                                                                        </select>
                                                                        <?php
                                                                    }else{
                                                                        if($rank=="profesor"){
                                                                            ?>
                                                                            <option value=1>Administrador</option>
                                                                            <option value=2 selected>Profesor</option>
                                                                            <option value=3>Estudiante</option>
                                                                            </select>
                                                                            <?php     
                                                                        }else{
                                                                            ?>
                                                                            <option value=1>Administrador</option>
                                                                            <option value=2>Profesor</option>
                                                                            <option value=3 selected>Estudiante</option>
                                                                            </select>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    
                                                                    ?>

                                                            </td>

                                                            <td>
                                                                <?php echo $row['classname']; 
                                                                 $i=0;
                                                                //  $j=1;
                                                                 while($i < count($classnameList)){
                                                                         if($row['classname']==$classnameList[$i]){$class_select=$classidList[$i];}else{$class_select="";}
                                                                         $i++;
                                                                        //  $j++;
                                                                 }
                                                                ?>
                                                                <select name="edit_class" id="edit_class<?php echo $count; ?>" class="form-control formEdit-control" hidden required>
                                                                    <?php 
 
                                                                        $i = 0;
                                                                        $j = 1;
                                                                        while($i < count($classnameList)){
                                                                                ?>                                                                                        
                                                                                       <option <?php if($row['classname']==$classnameList[$i]){echo ("selected");}?> value='<?php print_r($classidList[$i]) ?>'><?php print_r($classnameList[$i]) ?> </option>    <!-- https://stackoverflow.com/questions/3518002/how-can-i-set-the-default-value-for-an-html-select-element -->
                                                                               <?php
                                                                            $i++;
                                                                            $j++;
                                                                        }

                                                                    ?>    
                                                                </select>                                                            
                                                            </td>

                                                            <td>
                                                                
                                                                <a href="#" class="rowEdit users" onclick="rowEdit(<?php echo $count; ?>,'users');">
                                                                <!-- <a href="#" onclick="rowEdit(this);"> -->
                                                                <i class="fa fa-edit editIcon users" id="editIcon<?php echo $count; ?>"></i><button type="button" name="update_button" id="update_button<?php echo $count; ?>" class="btn btn-success update_button users" onclick="rowUpdate(<?php echo $count; ?>, 'users')";   hidden>Update</button>
                                                                </a>
                                                            </td>

                                                            <td>
                                                                <a href="#" onclick="rowDelete(<?php echo $count; ?>, 'users');">
                                                                <i class="fa fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>

                                            <?php
                                            $count++;
                                 
                                                } //end while
                                                    ?>
 <tbody>                            <!-- AQUI COMIENZA LA FILA DE AÑADIR ELEMENTO -->
                                                        <tr id="edit_<?php echo $count+1; ?>" class="addRow">
                                                            <td>
                                                                
                                                                <!-- <input type="number" name="add_id" id="add_id" value="" class="form-control UserEdit" required/> -->
                                                            </td>

                                                            <td>
                                                                
                                                                <input type="text" name="add_user" id="add_user" value="" class="form-control"  required />
                                                                <span id="useradderror" class="text-danger"></span>
                                                            </td>

                                                            <td>
                                                                
                                                                <input type="email" name="add_email" id="add_email" value="" class="form-control"  required />
                                                                <span id="useraddmailerror" class="text-danger"></span>
                                                            </td>

                                                            <td>
                                                               
                                                                <input type="password" name="add_password" id="add_password" value="" class="form-control"  required />
                                                                <span id="useraddpwerror" class="text-danger"></span>
                                                            </td>

                                                            <td>
                                                               
                                                                <!-- <input type="text" name="add_cdate" id="add_cdate" value="" class="form-control"  required /> -->
                                                            </td>

                                                            <td>
                                                               
                                                                <select name="add_level" id="add_level" value="" class="form-control"  required>
                                                                    <option value=1>Administrador</option>
                                                                    <option value=2>Profesor</option>
                                                                    <option value=3 selected>Estudiante</option>                        
                                                                </select>    
                                                            </td>

                                                            <td>
                                                               
                                                                <select name="add_class" id="add_class" value="" class="form-control"  required>
                                                                    <?php
                                                                       $i = 0;
                                                                       $j = 1;
                                                                       while($i < count($classnameList)){
                                                                               ?>
                                                                                       <option value='<?php print_r($classidList[$i]); ?>'><?php print_r($classnameList[$i]); ?></option> <!-- https://stackoverflow.com/questions/3518002/how-can-i-set-the-default-value-for-an-html-select-element -->
                                                                               <?php
                                                                           $i++;
                                                                           $j++;
                                                                       }
                                                                    ?>
                                                                
                                                                <!-- <option value=1>B1</option>
                                                                    <option value=2 selected>B2</option>
                                                                    <option value=3>C1</option>
                                                                    <option value=4>C2</option> -->
                                                                </select>                                                                
                                                            </td>

                                                            <td>                                                                              
                                                                <!-- <a href="#" onclick="rowEdit(this);"> -->
                                                                <button type="button" name="add_user_btn" id="add_user_btn" class="btn btn-success add_button" onclick="rowAdd('users');">Add</button>
                                                                
                                                            </td>

                                                            <td>
                                                            
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                    <?php

                                            }//end if (mysqli_num_rows($result) > 0)
                                            else{
                                                echo "0 results";
                                            }


                                            ?>
                                                    </table>
                            
                            </div>
                            <!-- footer -->
                            <div class="">                
                                <!-- <button type="button" name="update_button" class="btn btn-success update_button"   disabled>Update</button> -->
                                <button type="button" class="btn btn-danger" onclick="closeWindow();" >Close</button>
                                <span id="editUser_error" class="text-danger"></span>        
                             
                            </div>

                                        </div>               
                                                              
                       
            </div> <!--End db-usuarios-->
                                       
            <div class="db-clases" id="class-window" hidden>
                <div>
                            <!-- Header -->
                            <div class="">
                                <h4 class="">Cursos</h4>
                                
                            </div> <!-- Header End-->
                            <!-- body -->
                            <div class="">
                                    <div class="table-responsive">
                                                <table class="table table-bordered" id="dataTable-class" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="7" class="table-title"><h2>CURSOS</h2></th>
                                                            </tr>
                                                            <tr>
                                                                <th>Class ID</th>
                                                                <th>Nombre Curso</th>
                                                                <th>Nombre Profesor</th>                                                                                                    
                                                                <th>Editar</th>
                                                                <th>Eliminar</th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Class ID</th>
                                                                <th>Nombre Curso</th>
                                                                <th>Nombre Profesor</th>                                                                                                       
                                                                <th>Editar</th>
                                                                <th>Eliminar</th>
                                                            </tr>
                                                        </tfoot>

                                                
                                    </div>
                                            <?php
                                           
                                           
                                            $teacherQuery = "SELECT username, username FROM users WHERE ulevel='2'";
                                            


                                            if(mysqli_query($con, $teacherQuery)){
                                                echo "";
                                            }else{
                                                echo "Error: " . $teacherQuery . "<br>" . mysqli_error($con);
                                            }
                                            $count=1;
                                            
                                           

                                            $resultTeacher = mysqli_query($con, $teacherQuery);
                                            while($row2 = mysqli_fetch_assoc($resultTeacher)){
                                                $teacher = $row2['username'];
                                                $teacherList[] = $row2['username'];
                                            }
                                          

                                            // while($i = mysqli_fetch_assoc($result)){
                                            //     $teacherid[] = array($i['user_id']);
                                            //     $teachername[] = array($i['username']);
                                            //     $teacherlist[][]=array($i['user_id'],$i['username']);
                                               
                                            //     // echo $i['username'];
                                               
                                            // }
                                            
                                            // print json_encode($teacherlist, JSON_UNESCAPED_UNICODE);
                                            // print_r($teacherlist);

                                              mysqli_free_result($resultTeacher);
                                            






                                           
                                            // $query = "SELECT * FROM clases, users WHERE clases.classid=users.class AND users.ulevel='2'";
                                            $query = "SELECT * FROM clases LEFT JOIN users ON clases.classid=users.class AND users.ulevel='2'";



                                            if(mysqli_query($con, $query)){
                                                echo "";
                                            }else{
                                                echo "Error: " . $query . "<br>" . mysqli_error($con);
                                            }

                                            $count=1;

                                            $result = mysqli_query($con, $query);
                                            

                                            if (mysqli_num_rows($result) > 0){
                                                //output data of each row

                                                while($row = mysqli_fetch_assoc($result)){
                                                    
                                                    ?>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <?php echo $row['classid']; ?>
                                                                <input type="number" name="edit_classid" id=edit_classid<?php echo $count; ?> value="<?php echo $row['classid']; ?>" class="form-control formEdit-control classEdit" hidden required/>
                                                            </td>

                                                            <td>
                                                                <?php echo $row['classname']; ?>
                                                                <input type="text" name="edit_classname" id="edit_classname<?php echo $count; ?>" value="<?php echo $row['classname']; ?>" class="form-control formEdit-control classEdit" hidden required />
                                                                <span id="Edit_classname_log" class="text-danger"></span>
                                                            </td>

                                                            <td>
                                                                <?php echo $row['username']; ?>
                                                                <select name="edit_classteacher" id="edit_classteacher<?php echo $count; ?>" class="form-control formEdit-control classEdit" hidden required>
                                                                    <?php
                                                                        
                                                                        $i = 0;
                                                                        while($i < count($teacherList)){
                                                                    //    print_r($teacherList[1]);
                                                                  
                                                                        ?>
                                                                            <option <?php if($row['username']==$teacherList[$i]){echo ("selected");}?> id="edit_classteacherOption<?php echo $count ?>-<?php echo $i ?>" value='<?php print_r($teacherList[$i]); ?>'><?php print_r($teacherList[$i]); ?> </option>
                                                                        <?php

                                                                        $i++;
                                                                        }
                                                                      
                                                                      
                                                                    ?>
                                                                </select>
                                                                <?php   ?>
                                                            </td>

                                                          

                                                            <td>
                                                                <a href="#" class="rowEdit" onclick="rowEdit(<?php echo $count; ?>,'clases');">
                                                                <!-- <a href="#" onclick="rowEdit(this);"> -->
                                                                <i class="fa fa-edit editIcon" id="editClassIcon<?php echo $count; ?>"></i><button type="button" name="update_button" id="updateClass_button<?php echo $count; ?>" class="btn btn-success update_button" onclick="rowUpdate(<?php echo $count; ?>, 'clases')";   hidden>Update</button>
                                                                </a>
                                                            </td>
                                                            
                                                            <td>
                                                                <a href="#" onclick="rowDelete(<?php echo $count; ?>, 'clases');">
                                                                <i class="fa fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>

                                                
            



                                            <?php
                                            $count++;



                                            
                                                } //end while
                                                ?>
                                               <!-- AQUI COMIENZA LA FILA DE AÑADIR ELEMENTO -->
                                               <tr>
                                                    <td>

                                                    </td>

                                                    <td>
                                                         <input type="text" name="add_classname" id="add_classname" value="" class="form-control" required />
                                                         <span id="add_classname_log" class="text-danger"></span>   
                                                    </td>
                                                    <td>
                                                         <select name="add_classteacher" id="add_classteacher" class="form-control" required>
                                                                    <?php
                                                                        
                                                                        $i = 0;
                                                                        while($i < count($teacherList)){
                                                                    //    print_r($teacherList[1]);
                                                                  
                                                                        ?>
                                                                            <option  id="add_classteacher" value='<?php print_r($teacherList[$i]); ?>'><?php print_r($teacherList[$i]); ?> </option>
                                                                        <?php

                                                                        $i++;
                                                                        }
                                                                      
                                                                      
                                                                    ?>
                                                            </select>
                                                    </td>
                                                    <td>
                                                    <button type="button" name="add_class_btn" id="add_class_btn" class="btn btn-success add_button" onclick="rowAdd('clases');">Add</button>
                                                    </td>
                                               </tr>


                                                <?php

                                            }//end if (mysqli_num_rows($result) > 0)
                                            else{
                                                echo "0 results";
                                            }


                                            ?>
    
                                                    </table>
                            
                            </div>
                            <!-- footer -->
                            <div class="">                
                                <!-- <button type="button" name="update_button" class="btn btn-success update_button"   disabled>Update</button> -->
                                <button type="button" class="btn btn-danger" onclick="closeWindow();" >Close</button>
                                <span id="editClass_error" class="text-danger"></span>        
                                       
                            </div>

                    </div>     
                </div>        
            </div><!--End of db-clases-->

            <div class="db-horarios" id="horario-window" hidden>
              <div>
                  
                <div>       <!-- Header-->
                    <h4>Horarios</h4>
                </div>      <!-- Header End-->
                
                <!-- body -->

                <div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable-horario" width="100%" cellspacing="0">
                             <thead>
                                 <tr>
                                     <th colspan="7" class="table-title"><h2>HORARIOS</h2></th>
                                 </tr>
                                 <tr>
                                     <th>Día ID</th>
                                     <th>Día</th>
                                     <th>Primera Clase</th>
                                     <th>Segunda Clase</th>
                                     <th>Tercera Clase</th>
                                     <th>Editar</th>
                                 </tr>
                             </thead>            
                             <tfoot>
                                     <th>Día ID</th>
                                     <th>Día</th>
                                     <th>Primera Clase</th>
                                     <th>Segunda Clase</th>
                                     <th>Tercera Clase</th>   
                                     <th>Editar</th>                              
                             </tfoot>   
                         
                             <?php 

                                $classQuery = "SELECT classid, classname FROM clases";
                                if(mysqli_query($con,$classQuery)){
                                    echo"";
                                }else{
                                    echo"Error";
                                }
                                $count=1;
                                unset($classnameList);
                                unset($classidList);

                                $resultClass = mysqli_query($con, $classQuery);

                                while($row2 = mysqli_fetch_assoc($resultClass)){
                                    $classnameList[] = $row2['classname'];
                                    $classidList[] = $row2['classid'];
                                    

                                }
                                
                                mysqli_free_result($resultClass);
                             
                             $query = "SELECT * FROM schedule";
                             if (mysqli_query($con, $query)){
                                echo "";
                             }else{
                                 echo "Error";
                             }

                             $count=1;

                             $result = mysqli_query($con,$query);

                             if (mysqli_num_rows($result) > 0 ){
                                 while($row = mysqli_fetch_assoc($result)){

                                    ?>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo $row['day_id']; ?>
                                        <input type="number" name="edit_horarioid" id=edit_horarioid<?php echo $count; ?> value="<?php echo $row['day_id']; ?>" class="form-control formEdit-control horarioEdit" hidden required/>
                                        
                                    </td>
                                    <td>
                                        <?php echo $row['week_day']; ?>
                                        <input type="text" name="edit_horarioday" id=edit_horarioday<?php echo $count; ?> value="<?php echo $row['week_day']; ?>" class="form-control formEdit-control horarioEdit" hidden required/>
                                    </td>
                                    <td><!-- FIRST CLASS -->
                                        <?php 
                                        $i=0;
                                        $j=1;
                                        while($i < count($classnameList)){
                                            if($row['firstclass']==$j){echo $classnameList[$i]; $class_select=$classidList[$i];}
                                            $i++;
                                            $j++;
                                        }
                                        
                                        ?>                                        
                                        <!-- input here -->                                        
                                        <select name="edit_class1" id="edit_class1_<?php echo $count; ?>" class="form-control formEdit-control  horarioEdit" hidden>
                                            <?php 
                                                $i=0;
                                                $j=1;
                                                while ($i < count($classnameList)){
                                                ?>
                                                    <option <?php if($class_select==$j){echo("selected");} ?> value='<?php echo $j ?>'><?php print_r($classnameList[$i]); ?> </option>

                                            <?php
                                                $i++;
                                                $j++;
                                                }
                                            ?>
                                        </select>
                                    </td>

                                    <td> <!-- SECOND CLASS -->
                                        <?php 
                                            $i=0;
                                            $j=1;
                                            while($i < count($classnameList)){
                                                if($row['secondclass']==$j){echo $classnameList[$i]; $class_select=$classidList[$i];}
                                                $i++;
                                                $j++;
                                            }

                                            ?> 
                                        <!-- input here -->
                                        <select name="edit_class2" id="edit_class2_<?php echo $count; ?>" class="form-control  formEdit-control  horarioEdit" hidden>
                                            <?php 
                                                $i=0;
                                                $j=1;
                                                while ($i < count($classnameList)){
                                                ?>
                                                    <option <?php if($class_select==$j){echo("selected");} ?> value='<?php echo $j ?>'><?php print_r($classnameList[$i]); ?> </option>

                                            <?php
                                                $i++;
                                                $j++;
                                                }
                                            ?>
                                        </select>
                                    </td>

                                    <td> <!-- THIRD CLASS -->
                                    <?php 
                                        $i=0;
                                        $j=1;
                                        while($i < count($classidList)){
                                            if($row['thirdclass']==$j){echo $classnameList[$i]; $class_select=$classidList[$i];}
                                            $i++;
                                            $j++;
                                        }

                                        ?> 
                                        <!-- input here -->
                                        <select name="edit_class3" id="edit_class3_<?php echo $count; ?>" class="form-control formEdit-control  horarioEdit" hidden>
                                            <?php 
                                                $i=0;
                                                $j=1;
                                                while ($i < count($classnameList)){
                                                ?>
                                                    <option <?php if($class_select==$j){echo("selected");} ?> value='<?php echo $j ?>'><?php print_r($classnameList[$i]); ?> </option>

                                            <?php
                                                $i++;
                                                $j++;
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                    <a href="#" class="rowEdit" onclick="rowEdit(<?php echo $count; ?>,'horarios');">
                                    <i class="fa fa-edit editIcon" id="editHorarioIcon<?php echo $count; ?>"></i><button type="button" name="update_button" id="updateHorario_button<?php echo $count; ?>" class="btn btn-success update_button" onclick="rowUpdate(<?php echo $count; ?>, 'horarios')";   hidden>Update</button>
                                    </td>
                                </tr>
                            </tbody>



                                  <?php  
                                  $count++;
                                 }//end while
                             }else{
                                 echo "0 results";
                             }
                             
                             
                             ?>
                        </table>
                    </div><!-- end table-responsive div BOOKMARK -->   
                    <!-- Footer -->
                    <div>
                                <button type="button" class="btn btn-danger" onclick="closeWindow();" >Close</button>
                                <span id="editHorario_error" class="text-danger"></span>
                    </div>
                    
                </div>
              </div>                                
            </div><!-- End of db-horario-->

            <div class="db-notas" id="notas-window" hidden>
                <div>
                      <div><!-- Header -->
                          <h4>Notas</h4>
                      </div>       
                       
                      <!-- Body -->

                      <div>
                          <div class="table-responsive">
                             <table class="table table-bordered" id="dataTable-notas" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                       <th colspan="7" class="table-title"><h2>NOTAS</h2></th>
                                    </tr>

                                    <tr>
                                        
                                        <th>Alumno</th>
                                        <th>Curso</th>
                                        <th>Primer Trimestre</th>
                                        <th>Segundo Trimestre</th>
                                        <th>Tercer Trimestre</th>
                                        <th>Editar</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                        
                                        <th>Alumno</th>
                                        <th>Curso</th>
                                        <th>Primer Trimestre</th>
                                        <th>Segundo Trimestre</th>
                                        <th>Tercer Trimestre</th>
                                        <th>Editar</th>
                                </tfoot>

                             <?php
                                            
                                $query = "SELECT * FROM notas INNER JOIN users ON notas.idAlumno=users.user_id AND users.ulevel='3' INNER JOIN clases ON notas.idClase=clases.classid";

                                if(mysqli_query($con, $query)){
                                    echo "";
                                }else{
                                    echo "error";
                                }
                             
                                $count = 1;

                                $result = mysqli_query($con, $query);

                                if (mysqli_num_rows($result) > 0){

                                    while($row = mysqli_fetch_assoc($result)){
                                        ?>
                                 <tbody>
                                     <tr>
                                        <!-- <td>
                                            <?php echo $row['idNotas']; ?>
                                            
                                        </td> -->
                                        <td>                                            
                                            <?php echo $row['username']; ?>
                                            <input type="text" name="edit_studentname" id="edit_studentnameNotas<?php echo $count; ?>" value="<?php echo $row['username']; ?>" class="form-control formEdit-control notasEdit" hidden />
                                            <input type="number" name="edit_notasid" id=edit_notasid<?php echo $count; ?> value="<?php echo $row['idAlumno']; ?>" class="form-control formEdit-control notasEdit" hidden required />
                                        </td>
                                        <td>
                                            <?php echo $row['classname']; ?>
                                            <input type="text" name="edit_classname" id="edit_classnameNotas<?php echo $count; ?>" value="<?php echo $row['idClase']; ?>" class="form-control formEdit-control notasEdit" hidden  />
                                                
                                        </td>
                                        <td>
                                            <?php echo $row['Nota1']; ?>
                                            <input type="number" name="edit_nota1" id="edit_nota1_<?php echo $count; ?>" value="<?php echo $row['Nota1']; ?>" class="form-control formEdit-control notasEdit" hidden />
                                               
                                        </td>
                                        <td>
                                            <?php echo $row['Nota2']; ?>
                                            <input type="number" name="edit_nota2" id="edit_nota2_<?php echo $count; ?>" value="<?php echo $row['Nota2']; ?>" class="form-control formEdit-control notasEdit" hidden />
                                        </td>
                                        <td>
                                            <?php echo $row['Nota3']; ?>
                                            <input type="number" name="edit_nota3" id="edit_nota3_<?php echo $count; ?>" value="<?php echo $row['Nota3']; ?>" class="form-control formEdit-control notasEdit" hidden />
                                        </td>
                                        <td>
                                                <a href="#" class="rowEdit" onclick="rowEdit(<?php echo $count; ?>,'notas');">
                                                <!-- <a href="#" onclick="rowEdit(this);"> -->
                                                <i class="fa fa-edit editIcon" id="editNotasIcon<?php echo $count; ?>"></i><button type="button" name="update_button" id="updateNotas_button<?php echo $count; ?>" class="btn btn-success update_button" onclick="rowUpdate(<?php echo $count; ?>, 'notas')";   hidden>Update</button>
                                                </a>
                                        </td>
                                     </tr>       
                                 </tbody>       

                                    <?php    
                                $count++;    
                                }//end while

                                }else{
                                    echo "0 results";
                                }

                             ?>

                             </table>

                          </div>   <!-- Table responsive end -->


                      </div><!-- Body End -->


                </div>             
                
            </div><!--End of db-notas-->

        <?php 
        
        // mysqli_free_result($result);
        // mysqli_free_result($teacherlist);
        // mysqli_close($con);
        
        
        ?>

        <span id="error_msg" class="text-danger"><?php echo $_SESSION["msg2"]; ?></span>                                  
        </div> <!--End db-window"-->


                                  
                




















            <?php    
                    }else{//if other user
                    echo "No deberías estar aquí";
                    exit();
                    }


            }else{//if not logged
                exit();
            }   
            ?>




    </div><!--Content-->
</div><!--Wrapper-->

  

</body>
</html>
