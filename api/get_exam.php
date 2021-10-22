<?php

session_start();
if(!isset($_SESSION["uuidv4"])){
    header("location: ../login/");
    exit();
}
if(!isset($_GET["e"]) && !isset($_GET["q"]) && !isset($_GET["i"])){
    header("location: ../panel/");
    exit();
}

$EXAM_ID = $_GET["e"];
$QUESTION_ID = $_GET["q"];
$QUESTIONS_AMOUNT = $_GET["i"];
if(empty($_SESSION["COMPLETED_QUESTIONS"]) || !isset($_SESSION["COMPLETED_QUESTIONS"])){
    $_SESSION["COMPLETED_QUESTIONS"] = array();
}

array_push($_SESSION["qid"], $QUESTIONS_AMOUNT);
array_push($_SESSION["COMPLETED_QUESTIONS"], $QUESTION_ID);

include_once '../includes/dbh.inc.php';
include_once '../includes/functions.inc.php';

echo '<p class="question_amount">' . count($_SESSION["qid"]) . "/" . get_max_questions($conn, $EXAM_ID) . '</p>';
echo '<h2 class="question_name"><span class="question_id">' . count($_SESSION["qid"]) . '.</span> ' . $QUESTION["question_text"] .'</h2>';
echo '<ul>';
echo get_answers($conn, $QUESTION["question_id"]);
echo '<button class="btn btn_primary answer_send">Prześlij</button>';
echo "</ul>";