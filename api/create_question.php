<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }

    if(!isset($_GET["qtext"])){
        header("location: ../panel/index.php?error=questionisnull");
        exit();
    }

    $QUESTION_TEXT = $_GET["qtext"];
    $EXAM = $_GET["exam"];

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    $sql = "INSERT INTO exam_questions (question_text, question_correct, exam_id) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../settings/index.php?error=stmtfail");
        exit();
    }

    $CORRECT = 0;
    mysqli_stmt_bind_param($stmt, "sii", $QUESTION_TEXT, $CORRECT, $EXAM);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo question_exists($conn, $QUESTION_TEXT, $EXAM)["question_id"];