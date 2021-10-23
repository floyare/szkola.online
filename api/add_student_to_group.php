<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }

    if(!isset($_GET["uuid"])){
        header("location: ../panel/index.php?error=nouuid");
        exit();
    }

    if(!isset($_GET["group"])){
        header("location: ../panel/index.php?error=nouuid");
        exit();
    }


    $UUID = $_GET["uuid"];
    $GROUP = $_GET["group"];

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    $sql = "UPDATE groups SET group_members = CONCAT(group_members, ?) WHERE group_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "si", $UUID, $GROUP);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);