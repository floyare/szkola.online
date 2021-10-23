<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }
    if(!isset($_GET["question"])){
        header("location: ../panel/index.php?error=noquestion");
        exit();
    }

    if(!isset($_GET["text"])){
        header("location: ../panel/index.php?error=notext");
        exit();
    }

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    echo get_answer($conn, $_GET["text"], $_GET["question"])["answer_id"];
