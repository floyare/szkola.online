<?php
    if(!isset($_POST["submit"])){
        header("location: ../register/");
    }

    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $mail = $_POST["mail"];
    $pwd = $_POST["pwd"];
    $pwdr = $_POST["pwdr"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';


    if(emptyInputSignup($name, $surname, $mail, $pwd, $pwdr)){
        header("location: ../register/index.php?error=emptyinput");
        exit();
    }

    if(invalidName($name, $surname)){
        header("location: ../register/index.php?error=invalidname");
        exit();
    }

    if(invalidEmail($mail)){
        header("location: ../register/index.php?error=invalidemail");
        exit();
    }

    if(pwdMatch($pwd, $pwdr)){
        header("location: ../register/index.php?error=pwdmatch");
        exit();
    }

    if(emailExists($conn, $mail)){
        header("location: ../register/index.php?error=mailtaken");
        exit();
    }

    $uuid = gen_uuid();
    createUser($conn, $name, $surname, $uuid, $mail, $pwd, 2);
