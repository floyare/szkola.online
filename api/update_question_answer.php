<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }

    if(!isset($_GET["question"])){
        header("location: ../panel/index.php?error=questionisnull");
        exit();
    }

    if(!isset($_GET["correct"])){
        header("location: ../panel/index.php?error=correctisnull");
        exit();
    }


    $QUESTION_ID = $_GET["question"];
    $CORRECT_ANSWER = $_GET["correct"];

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    $sql = "UPDATE exam_questions SET question_correct = ? WHERE question_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $CORRECT_ANSWER, $QUESTION_ID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);