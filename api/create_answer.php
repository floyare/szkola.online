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

    $ANSWER_TEXT = $_GET["answer"];
    $QUESTION_ID = $_GET["question"];
    $EXAM_ID = $_GET["exam"];

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    $sql = "INSERT INTO exam_answers (answer_text, answer_exam_id, answer_question_id) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sii", $ANSWER_TEXT, $EXAM_ID, $QUESTION_ID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    add_question_to_exam($conn, $EXAM_ID);

    echo get_answer($conn, $ANSWER_TEXT, $QUESTION_ID)["answer_id"];