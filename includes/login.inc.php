<?php
if(!isset($_POST["submit"])){
    header("location: ../login/");
    exit();
}
    $mail = $_POST["mail"];
    $pwd = $_POST["pwd"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if(emptyInputLogin($mail, $pwd)){
        header("location: ../register/index.php?error=emptyinput");
        exit();
    }

    loginUser($conn, $mail, $pwd);