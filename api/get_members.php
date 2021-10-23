<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
      }
    $type = $_GET["t"];
    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    switch($type){
        case 0:
            $sql = "SELECT * FROM students WHERE school_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: ../settings/school.php?error=stmtfail");
                exit();
            }
        
            $schid = get_school($conn, $_SESSION["uuidv4"])["school_id"];

            mysqli_stmt_bind_param($stmt, "i", $schid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        
        
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='element'><p class='name'>" . $row["student_name"] . " " . $row["student_surname"] . "</p><p class='class'>" . $row["student_class"] ."</p><p class='date'>" . $row["student_birth"] . "</p></div>";
                }
              } else {
                    echo "brak";
              }
        
            mysqli_stmt_close($stmt);
            break;
        case 1:
            $sql = "SELECT * FROM teachers WHERE school_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: ../settings/school.php?error=stmtfail");
                exit();
            }
        
            $schid = get_school($conn, $_SESSION["uuidv4"])["school_id"];
            mysqli_stmt_bind_param($stmt, "i", $schid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        
        
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='element'><p class='name'>" . $row["teacher_name"] . " " . $row["teacher_surname"] . "</p><p class='subject'>" . $row["teacher_subject"] . "</p></div>";
                }
              } else {
                 echo "brak";
              }
        
            mysqli_stmt_close($stmt);
            break;
    }