<?php
include ('db.php');  


function check_login(){
    return (isset($_SESSION['login'])) ? true : false;
}//end check_login




