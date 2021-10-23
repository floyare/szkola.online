<?php
    session_start();
    $msg = $_GET["text"];
    $rec = $_GET["rec"];
    $time = time();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    if(strlen($msg) < 200){
        if(!isset($_GET["group"])){
            $sql = "INSERT INTO messages (msg_outgoing, msg_ingoing, msg_text, msg_timespan, msg_seen) VALUES (?, ?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: ../panel/index.php?error=stmtfail");
                exit();
            }
        
            $seen = 0;
            mysqli_stmt_bind_param($stmt, "ssssi", $rec, $_SESSION["uuidv4"], $msg, $time, $seen);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "1";
        }else{
            $sql = "INSERT INTO group_messages (msg_group, msg_sender, msg_text, msg_timespan, msg_saw) VALUES (?, ?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: ../panel/index.php?error=stmtfail");
                exit();
            }

            if($_SESSION["type"] == 0){
                if(get_talk_permission($conn, $_SESSION["uuidv4"])["school_chat_student_talk_allow"] == 0){
                    echo "2";
                    mysqli_stmt_close($stmt);                    
                    return;
                }
            }

            $saw = "";
            mysqli_stmt_bind_param($stmt, "issis", $_GET["group"], $_SESSION["uuidv4"], $msg, $time, $saw);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "1";
        }
    }else{
        echo "0";
    }