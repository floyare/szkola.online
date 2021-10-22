<?php
  session_start();
  if(!isset($_SESSION["uuidv4"])){
    header("location: ../login/");
    exit();
  }

  $TYPE = $_GET["t"];
  include_once '../includes/dbh.inc.php';
  include_once '../includes/functions.inc.php';
  $school =  "";
  $school;
  if($TYPE == 0){
      $school = get_student($conn, $_SESSION["uuidv4"])["school_id"];
  }elseif($TYPE == 1){
      $school = get_teacher($conn, $_SESSION["uuidv4"])["school_id"];
  }elseif($TYPE == 2){
      $school = get_school($conn, $_SESSION["uuidv4"])["school_id"];
  }

  switch($TYPE){
    case 0:
      $sql = "SELECT * FROM teachers WHERE school_id = ?";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql)){
          header("location: ../panel/index.php?error=stmtfail");
          exit();
      }
    
      mysqli_stmt_bind_param($stmt, "i", $school);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    
      if ($result->num_rows > 0) {
          echo "Dostępni nauczyciele: <br>";
          while($row = $result->fetch_assoc()) {
            echo "<div class='contact' onclick='window.location.href=`chat.php?user=" . $row["teacher_uuid"] ."`'><ul><li id='contact_avatar'><img src='" . get_avatar($conn, $row["teacher_uuid"]) . "'></li><li id='contact_name'><p>"  . $row["teacher_name"] . " " . $row["teacher_surname"] . "</p></li></ul></div>";
          }
        } else {
          echo "Brak dostępnych nauczycieli :(";
        }
      mysqli_stmt_close($stmt);
      break;
    case 1:
      $sql = "SELECT * FROM students WHERE school_id = ?";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql)){
          header("location: ../panel/index.php?error=stmtfail");
          exit();
      }
    
      mysqli_stmt_bind_param($stmt, "i", $school);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    
      if ($result->num_rows > 0) {
          echo "Uczniowie: <br>";
          while($row = $result->fetch_assoc()) {
            echo "<div class='contact' onclick='window.location.href=`chat.php?user=" . $row["student_uuid"] ."`'><ul><li id='contact_avatar'><img src='" . get_avatar($conn, $row["student_uuid"]) . "'></li><li id='contact_name'><p>"  . $row["student_name"] . " " . $row["student_surname"] . "</p></li></ul></div>";
          }
        } else {
          echo "Brak dostępnych uczniów :(";
        }
      mysqli_stmt_close($stmt);
      break;
  }