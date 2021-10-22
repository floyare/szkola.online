<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }

    if($_SESSION["type"] != 2){
        header("location: ../settings/");
        exit();
    }

    if(!isset($_GET["allow"])){
        header("location: school.php");
        exit();
    }
    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    $sql = "UPDATE schools SET school_chat_student_talk_allow = ? WHERE school_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    $per;
    if($_GET["allow"] == 0){
        $per = 0;
    }else if($_GET["allow"] == 1){
        $per = 1;
    }

    mysqli_stmt_bind_param($stmt, "ii", $per, get_school($conn, $_SESSION["uuidv4"])["school_id"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);