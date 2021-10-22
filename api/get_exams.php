<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }
    if(!isset($_GET["group"])){
        header("location: ../login/");
        exit();
    }
    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';
    $uuid = $_SESSION["uuidv4"];

    $sql = "SELECT * FROM exams WHERE exam_group_id = ? ORDER BY exam_id DESC";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $_GET["group"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="exam">';
            echo '<p>' . $row["exam_name"] . '</p>';
            if(result_exist($conn, $uuid, $row["exam_id"])){
                echo '<p>Status: <span class="done">Rozwiązany</span></p>';
                echo '<p>Wynik: ' . result_data($conn, $uuid, $row["exam_id"])["exam_result_score"] . '</p>';
            }else{
                echo '<p>Status: <span class="available">Dostępny</span></p>';
                echo '<p><button class="btn btn_small" onclick="window.location.href=`exam.php?exam=' . $row["exam_id"] .'`">Rozwiąż</button></p>';
            }   
            echo '</div>';

        }
      } else {
          echo "Brak grup!";
      }

    mysqli_stmt_close($stmt);