<?php
include('db.php');
include('functions.php');

session_start();

// $_SESSION["msg2"] = "prueba";
// $_SESSION["msg2"] = "id: " . $_POST['user_id'] . " username: " . $_POST['username'] . " email: " . $_POST['email'] . " password: " . $_POST['pword'] . " create date: " . $_POST['create_time'] . " level: " . $_POST['ulevel'] . " class: " . $_POST['class'];
if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['pword']) && isset($_POST['ulevel']) && isset($_POST['class'])){

// $_SESSION["msg2"] = "dentro prueba";
    // $addID=$_POST['user_id'];
    $addUser=$_POST['username'];
    $addEmail=$_POST['email'];     
    $addPassword=$_POST['pword'];
    // $addCdate=$_POST['create_time'];
    $addLevel=$_POST['ulevel'];
    $addClass=$_POST['class'];

    



$query = "SELECT * from users";

        if(mysqli_query($con,$query)){
            echo "";
        }else{
            echo "error";
        }

        $count=1;
        

        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0){
            
            //search for empty id number to fill in if there is any
            while($row = mysqli_fetch_assoc($result)){
                $_SESSION["msg2"]=$addID;
                // $_SESSION["msg2"]="hehe";
                if($count!=$row['user_id']){
                        $addID=$count;
                       
                        break;
                }
                
                
                $count++;
                
            }//endwhile
                     
            $addID=$count;
            //  $_SESSION["msg2"]=$count;
            

        }else{
            $addID=1;
        }
        // INSERT INTO `academy_db`.`users` (`user_id`, `username`, `email`, `pword`, `ulevel`, `class`) VALUES ('6', 'Pepe', 'Pepe@pepe', 'pepe1234', '3', 'A2');
       $sql = "INSERT INTO users (user_id, username, email, pword, ulevel, class) VALUES ('$addID', '$addUser', '$addEmail', '$addPassword', '$addLevel', '$addClass')";
       
       $insert=$con->query($sql);

        if ($update === TRUE){
            echo "Yes";
        }else{
            echo "No";

        }


        //NOTAS INSERT
        
        if($addLevel=3){
            $sql = "INSERT INTO notas (idAlumno, idClase) VALUES ('$addID', '$addClass')";

            $insert=$con->query($sql);
    
            if ($update === TRUE){
                echo "Yes";
            }else{
                echo "No";
    
            }       
        }



        mysqli_free_result($insert);
        mysqli_close($con);


        echo $addID;
        // $_SESSION["msg2"] = $addID;
}//add user end

if(isset($_POST['classname']) && isset($_POST['username'])){
$addClass=$_POST['classname'];
$addTeacher=$_POST['username'];

$_SESSION["msg2"] = $addClass;

$query = "SELECT * from clases";

    if(mysqli_query($con,$query)){
        echo"";
    }else{
        echo "error";
    }
    $count=1;

    $result = mysqli_query($con, $query);

    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            if($count!=$row['classid']){
                $addID=$count;

                break;
            }
            $count++;
        }//endwhile
        $addID=$count;
    }else{
        $addID=1;
    }

    $sql = "INSERT INTO clases (classid,classname) VALUES('$addID','$addClass')";

    $insert=$con->query($sql);

    $sql = "UPDATE users SET class='$addID' WHERE username='$addTeacher'";

    $update=$con->query($sql);

    mysqli_free_result($insert);
    mysqli_free_result($update);
    mysqli_close($con);
}//add class end

//USUARIOS NUEVOS DE FORMULARIOS:

if(isset($_POST['nombre']) && isset($_POST['apellidos']) && isset($_POST['teléfono']) && isset($_POST['email']) && isset($_POST['mensaje'])){
 $nombre=$_POST['nombre'];
 $apellidos=$_POST['apellidos'];
 $teléfono=$_POST['teléfono'];
 $email=$_POST['email'];
 $mensaje=$_POST['mensaje'];

//  $_SESSION["msg2"] = $nombre . " " . $apellidos . " " . $teléfono . " " . $email . " " . $mensaje;

 $sql = "INSERT INTO formulario (nombre,apellidos,teléfono,email,mensaje) VALUES('$nombre','$apellidos','$teléfono','$email','$mensaje')";

 $insert=$con->query($sql);
 
 
}




?>