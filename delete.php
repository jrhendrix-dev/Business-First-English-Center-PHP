<?php
include('db.php');
include('functions.php');

session_start();

if(isset($_POST['user_id']) && isset($_POST['ulevel'])){
    $del=$_POST['user_id'];
    $level=$_POST['ulevel'];

    
    $sql = "DELETE FROM users WHERE user_id='$del'";

    $delete = $con->query($sql);

    if($delete === TRUE){
        echo "Yes";
    }else{
        echo "No";
    }

    //notas delete
    if($level=3){
        $sql = "DELETE FROM notas WHERE idAlumno='$del'";

        $delete = $con->query($sql);
    }

    



    mysqli_free_result($delete);
    mysqli_close($con);




}//end user



if(isset($_POST['classid'])){
    $del=$_POST['classid'];

    $sql = "DELETE FROM clases WHERE classid='$del'";

    $delete = $con->query($sql);

    if($delete === TRUE){
        echo "Yes";
    }else{
        echo "No";
    }

    mysqli_free_result($delete);
    mysqli_close($con);
}