<?php
    session_start();
    $school_name = $_GET["name"];
    $school_address = $_GET["address"];
    $school_city = $_GET["city"];
    $school_zip = $_GET["zip"];
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }
    if($_SESSION["type"] != 2){
        header("location: ../settings/");
        exit();
    }

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    $sql = "INSERT INTO schools (school_owner, school_name, school_address, school_city, school_zip) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../settings/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssss", $_SESSION["uuidv4"], $school_name, $school_address, $school_city, $school_zip);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);