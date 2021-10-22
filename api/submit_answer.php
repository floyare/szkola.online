<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }
    if(!isset($_GET["e"]) && !isset($_GET["q"]) && !isset($_GET["a"]) && !isset($_GET["i"])){
        header("location: ../panel/");
        exit();
    }

    $EXAM_ID = $_GET["e"];
    $QUESTION_ID = $_GET["q"];
    $ANSWER_ID = $_GET["a"];
    $QUESTIONS_AMOUNT = $_GET["i"];
    if(empty($_SESSION["COMPLETED_QUESTIONS"]) || !isset($_SESSION["COMPLETED_QUESTIONS"])){
        $_SESSION["COMPLETED_QUESTIONS"] = array();
    }

    array_push($_SESSION["qid"], $QUESTIONS_AMOUNT);
    array_push($_SESSION["COMPLETED_QUESTIONS"], $QUESTION_ID);

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    //EXAM STATUS:
    //1-COMPLETED
    //2-IN PROGRESS

    if(count($_SESSION["qid"]) > get_max_questions($conn, $EXAM_ID)){
        unset($_SESSION["qid"]);
        unset($_SESSION["COMPLETED_QUESTIONS"]);
        update_result($conn, $_SESSION["uuidv4"], $EXAM_ID, $_SESSION["score"], 1);
        unset($_SESSION["score"]);
        echo "Przekierowywanie... proszę czekać";
        exit();
    }

    $USED_QUESTIONS = array();
    $QUESTION_CURRENT = get_current_question($conn, $QUESTION_ID);
    if($QUESTION_CURRENT["question_correct"] == $ANSWER_ID){
        $_SESSION["score"] = $_SESSION["score"] + 1;
        echo $QUESTION_CURRENT["question_correct"];
        update_result($conn, $_SESSION["uuidv4"], $EXAM_ID, $_SESSION["score"], 2);
    }else{
        echo $QUESTION_CURRENT["question_correct"];
        update_result($conn, $_SESSION["uuidv4"], $EXAM_ID, $_SESSION["score"], 2);
    }

    $QUESTION = get_question($conn, $EXAM_ID, $_SESSION["COMPLETED_QUESTIONS"]);
    
    echo '<p class="question_amount">' . count($_SESSION["qid"]) . "/" . get_max_questions($conn, $EXAM_ID) . '</p>';
    echo '<h2 class="question_name"><span class="question_id">' . count($_SESSION["qid"]) . '.</span> ' . $QUESTION["question_text"] .'</h2>';
    echo '<ul>';
    echo get_answers($conn, $QUESTION["question_id"]);
    echo '<button class="btn btn_primary answer_send">Prześlij</button>';
    echo "</ul>";



