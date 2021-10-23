<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }
    // if($_SESSION["type"] != 2 || $_SESSION["type"] != 1){
    //     header("location: ../panel/index.php?error=accessdenied");
    //     exit();
    // }

    if(!isset($_GET["name"])){
        header("location: ../panel/index.php?error=nameisnull");
        exit();
    }

    if(!isset($_GET["activation"])){
        header("location: ../panel/index.php?error=dateisnull");
        exit();
    }

    if(!isset($_GET["group"])){
        header("location: ../panel/index.php?error=groupisnull");
        exit();
    }

    $EXAM_NAME = $_GET["name"];
    $DATE = $_GET["activation"];
    $GROUP = $_GET["group"];

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    if(!exam_exists($conn, $EXAM_NAME, $_SESSION["uuidv4"])){
        $sql = "INSERT INTO exams (exam_creator, exam_group_id, exam_name, exam_duration, exam_datetime, exam_total_questions) VALUES (?, ?, ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../panel/index.php?error=stmtfail");
            exit();
        }
    
        $DURATION = 0;
        $QUESTIONS = 0;
        mysqli_stmt_bind_param($stmt, "sisisi", $_SESSION["uuidv4"], $GROUP, $EXAM_NAME, $DURATION, $DATE, $QUESTIONS);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo exam_exists($conn, $EXAM_NAME, $_SESSION["uuidv4"])["exam_id"];
    }else{
        echo "EXISTS";
    }