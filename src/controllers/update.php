<?php
session_start();
require_once __DIR__ . '/../src/models/Database.php';
$con = Database::connect();
require_once __DIR__ . '/../includes/functions.php';

// $_SESSION["msg2"] = "prueba";


// var id = $("#edit_id" + ctl).val();
// var username = $("#edit_user" + ctl).val();
// var email = $("#edit_email" + ctl).val();
// var pw = $("#edit_password" + ctl).val();
// var cdate = $("#edit_cdate" + ctl).val();
// var level = $("#edit_level" + ctl).val();
// var uclass = $("#edit_class" + ctl).val();
 //$_SESSION["msg2"] = "id: " . $_POST['user_id'] . "username: " . $_POST['username'] . "email: " . $_POST['email'] . "password: " . $_POST['pw'] . "creation date: " . $_POST [cdate] . "level: " . $_POST['level'] . "class: " . $_POST['uclass'];

// $_SESSION["msg2"] = "id: " . $_POST['user_id'] . " username: " . $_POST['username'] . " email: " .$_POST['email'] . " password: " . $_POST['pword'] . " create date: " . $_POST['create_time'] . " level: " . $_POST['ulevel'] . " class: " . $_POST['class'];
//  $_SESSION["msg2"] = "pw: " . $_POST['pword'];
if(isset($_POST['user_id']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['pword']) && isset($_POST['create_time']) && isset($_POST['ulevel']) && isset($_POST['class'])){

$editID=$_POST['user_id'];
$editUser=$_POST['username'];
$editEmail=$_POST['email'];     
$editPassword=$_POST['pword'];
$editCdate=$_POST['create_time'];
$editLevel=$_POST['ulevel'];
$editClass=$_POST['class'];

$msg="";

$_SESSION["msg2"] = $editClass;


$sql = "UPDATE users SET username = '$editUser', email='$editEmail', pword='$editPassword',
create_time='$editCdate', ulevel='$editLevel', class='$editClass' WHERE user_id='$editID'";

$update=$con->query($sql);

if($update === TRUE) {
echo "Yes";
// $msg="Yes";
// echo $msg;
// $_SESSION["msg2"] = $msg;


}else{
// echo "No";
// $msg="No";
// echo $msg;
// $_SESSION["msg"] = "Error updating: " . $con->error;
$msg="error: " . mysql_error();


echo "No";
die('Could not update; ' . mysql_error());


}

//NOTAS UPDATE
// $sql = "INSERT INTO notas (idAlumno, idCurso) VALUES ('$addID', '$addClass')";

if($editLevel=3){
    $sql = "UPDATE notas SET idClase = '$editClass' WHERE idAlumno='$editID'";

    $update=$con->query($sql);

    }




mysqli_free_result($update);
mysqli_close($con);





}//edit user end

if(isset($_POST['classid']) && isset($_POST['username']) && isset($_POST['classname'])){
$editClassID=$_POST['classid'];
$editTeacher=$_POST['username'];
$editClassName=$_POST['classname'];

$sql = "UPDATE clases SET classname = '$editClassName' WHERE classid='$editClassID'";

// users SET class='$editClassID' WHERE username='$editTeacher'";

$_SESSION["msg2"] = $editTeacher;
$update=$con->query($sql);

$sql = "UPDATE users SET class='$editClassID' WHERE username='$editTeacher'";

$update=$con->query($sql);

mysqli_free_result($update);
mysqli_close($con);
    
}//edit class end

if(isset($_POST['day_id']) && isset($_POST['week_day']) && isset($_POST['firstclass']) && isset($_POST['secondclass']) && isset($_POST['thirdclass'])){

$editDayID = $_POST['day_id'];
$editFirstClass = $_POST['firstclass'];
$editSecondClass = $_POST['secondclass'];
$editThirdClass = $_POST['thirdclass'];

$_SESSION["msg2"] = "first: " . $editFirstClass . " second: " . $editSecondClass . " third: " . $editThirdClass;

$sql = "UPDATE schedule SET firstclass='$editFirstClass', secondclass='$editSecondClass', thirdclass='$editThirdClass' WHERE day_id='$editDayID'";

$update=$con->query($sql);

mysqli_free_result($update);
mysqli_close($con);


}//edit schedule end

if(isset($_POST['idAlumno']) && isset($_POST['Nota1']) && isset($_POST['Nota2']) && isset($_POST['Nota3'])){

$editNotasID = $_POST['idAlumno'];
$editNotas1 = $_POST['Nota1'];
$editNotas2 = $_POST['Nota2'];
$editNotas3 = $_POST['Nota3'];



$sql = "UPDATE notas SET Nota1='$editNotas1', Nota2='$editNotas2', Nota3='$editNotas3' WHERE idAlumno='$editNotasID'";

$update=$con->query($sql);

mysqli_free_result($update);
mysqli_close($con);

}//edit notas end





?>
