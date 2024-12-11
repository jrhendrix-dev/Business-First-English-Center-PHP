<?php
include ('db.php');  

session_start();




if(isset($_POST['username']) && isset($_POST['password'])){
    $user=$_POST['username'];
    $password=$_POST['password'];
    $_SESSION["lvl"] = 0;
    
    $msg="";
    
    $_SESSION["msg"] = $msg;
    

   
    // $_SESSION["user"] = $user;

    // $_SESSION["login"] = $login;


    $query = "SELECT * FROM users WHERE username='$user'";
    
    $check=$con->query($query);

    if($check->num_rows >0){
        $row = $check->fetch_assoc();
        if($row['pword'] === $password){
            $login=True;
            $_SESSION["login"] = True;
            
            $msg="Yes";
            $_SESSION["msg"] = $msg;
            $_SESSION["user"] = $user;
            $_SESSION["login"] = $login;
            $_SESSION["lvl"] = $row['ulevel'];
            

            if($row['ulevel']==2){
                $_SESSION["curso"] = $row['class'];
            }
           
           // header("Location:test.php");
            // header("Location:index.php");
        }else{

            //wrong password
            $msg="No";
            $_SESSION["msg"] = $msg;
            echo $msg;
            //header("Location:test.php");
            // header("Location:index.php");
            
        }


    }else{
        //no such user
        $msg="No";
        $_SESSION["msg"] = $msg;
        echo $msg;
      //  header("Location:test.php");
    //   header("Location:index.php");
    }
mysqli_free_result($check);
mysqli_close($con);

}




























//--------------ORIGINAL WORKING--------------------------------
// if(isset($_POST['username']) && isset($_POST['password'])){
    //     $user=$_POST['username'];
    //     $password=$_POST['password'];
        
        
    //     $msg="";
        
    //     $_SESSION["msg"] = $msg;

    //     // $_SESSION["user"] = $user;

    //     // $_SESSION["login"] = $login;


    //     $query = "SELECT * FROM users WHERE username='$user'";
        
    //     $check=$con->query($query);

    //     if($check->num_rows >0){
    //         $row = $check->fetch_assoc();
    //         if($row['password'] === $password){
    //             $login=True;
    //             $_SESSION["login"] = True;
                
    //             $msg="yes";
    //             $_SESSION["msg"] = $msg;
    //             $_SESSION["user"] = $user;
    //             $_SESSION["login"] = $login;
                
    //            // header("Location:test.php");
    //             header("Location:index.php");
    //         }else{

    //             $msg="no";
    //             $_SESSION["msg"] = $msg;
    //             //header("Location:test.php");
    //             header("Location:index.php");
    //         }


    //     }else{
    //         $msg="no";
    //         $_SESSION["msg"] = $msg;
           
    //       //  header("Location:test.php");
    //       header("Location:index.php");
    //     }
    // mysqli_free_result($check);
    // mysqli_close($con);

    // }






    ?>
