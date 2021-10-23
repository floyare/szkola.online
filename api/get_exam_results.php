<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }
    if(!isset($_GET["exam"])){
        header("location: ../panel/index.php?error=examnotselected");
        exit();
    }

    $ACCOUNT_TYPE = $_SESSION["type"];

    // if($ACCOUNT_TYPE != 1 || $ACCOUNT_TYPE != 2){
    //     header("location: ../panel/index.php?error=accessdenied");
    //     exit();
    // }


    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';
    $uuid = $_SESSION["uuidv4"];
    $EXAM_ID = $_GET["exam"];

    $sql = "SELECT * FROM exam_results WHERE exam_result_exam_id = ? ORDER BY exam_result_id DESC";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $EXAM_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="result">';
            echo '<p class="result_icon"><img src="' . get_avatar($conn, $row["exam_result_student"]) . '"></p>';
            echo '<p class="result_name">' . get_fullname($conn, $row["exam_result_student"]) . '</p>';
            echo '<p class="result_score">Wynik: <span>' . $row["exam_result_score"] .'/' . $row["exam_result_max_score"] .' pkt.</span></p>';
            echo '</div>';
        }
      } else {
          echo "Brak wyników!";
      }

    mysqli_stmt_close($stmt);