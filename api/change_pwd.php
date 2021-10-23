<?php
    session_start();
    $oldpwd = $_GET["old"];
    $newpwd = $_GET["new"];
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    $hashed = password_hash($oldpwd, PASSWORD_DEFAULT);
    if($test = password_verify($oldpwd, get_password($conn, $_SESSION["uuidv4"]))){
        $newhashed = password_hash($newpwd, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET user_password = ? WHERE user_uuid = ?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../settings/index.php?error=stmtfail");
            exit();
        }
    
        mysqli_stmt_bind_param($stmt, "ss", $newhashed, $_SESSION["uuidv4"]);
        mysqli_stmt_execute($stmt);
        echo '1';
        mysqli_stmt_close($stmt);
    }else{
        echo "Hasło jest nieprawidłowe";
    }