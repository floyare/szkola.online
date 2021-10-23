<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }

    if(!isset($_GET["a"])){
        header("location: ?error=noanswer");
        exit(); 
    }

    if(!isset($_GET["e"])){
        header("location: ?error=noexam");
        exit(); 
    }

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    $QUESTION_ID = $_SESSION["CURRENT_QUESTION"];
    $ANSWER_ID = $_GET["a"];
    $EXAM_ID = $_GET["e"];

    $sql = "SELECT * FROM exam_answers WHERE answer_question_id = ?";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $QUESTION_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if(get_question($conn, $QUESTION_ID)["question_correct"] == $ANSWER_ID){
            //UPDATE SCORE AND STILL WRITING TEST
            $_SESSION["score"] = $_SESSION["score"] + 1;
        }

        //IF TEST ENDED.
        if(count($_SESSION["USED_QUESTIONS"]) >= get_max_questions($conn, $EXAM_ID)){
            //END TEST
            update_result($conn, $_SESSION["uuidv4"], $EXAM_ID, $_SESSION["score"], 1);
            unset($_SESSION["score"]);
            unset($_SESSION["USED_QUESTIONS"]);
            unset($_SESSION["CURRENT_QUESTION"]);
            echo "Przkierowywanie...";
            exit();
        }else{
            update_result($conn, $_SESSION["uuidv4"], $EXAM_ID, $_SESSION["score"], 2);
            //unset($GLOBALS["EXAM"]);
        }

        echo count($_SESSION["USED_QUESTIONS"]) . " " . get_max_questions($conn, $EXAM_ID) . "  " . $_SESSION["score"] . " " . $QUESTION_ID;

    } else {
        echo "Brak ODPOWIEDZI";
    }

    mysqli_stmt_close($stmt);