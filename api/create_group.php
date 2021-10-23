<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }
    if(!isset($_GET["name"])){
        header("location: ../panel/index.php?error=nameisnull");
        exit();
    }

    $GROUP_NAME = $_GET["name"];

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    $sql = "INSERT INTO groups (group_teacher, group_members, group_name, group_users_number) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/chat.php?error=stmtfail");
        exit();
    }
  
    $USERS = 0;
    mysqli_stmt_bind_param($stmt, "sssi", $_SESSION["uuidv4"], $_SESSION["uuidv4"], $GROUP_NAME, $USERS);
    mysqli_stmt_execute($stmt); 
    mysqli_stmt_close($stmt);

    
