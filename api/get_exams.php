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
    $ACCOUNT_TYPE = $_SESSION["type"];

    $sql = "SELECT * FROM exams WHERE exam_group_id = ? ORDER BY exam_id DESC";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $_GET["group"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($ACCOUNT_TYPE == 1 || $ACCOUNT_TYPE == 2){
        echo '<button class="btn btn_small create_exam"><i class="bx bxs-plus-circle" ></i> Utwórz nowy sprawdzian!</button><br>';
    }
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($ACCOUNT_TYPE == 1 || $ACCOUNT_TYPE == 2){
                echo '<div class="exam">';
                echo '<p class="exam_name">' . $row["exam_name"] . '</p>';
                echo '<p><button class="btn btn_small" onclick="show_exam_results(`' . $row["exam_id"] . '`)"><i class="bx bxs-bar-chart-alt-2" ></i> Wyniki</button></p>';
                echo '<p><button class="btn btn_small" onclick="show_exam_settings(`' . $row["exam_id"] . '`)"><i class="bx bxs-edit-alt"></i> Zarządzaj</button></p>';
                echo '</div>';
            }else{
                echo '<div class="exam">';
                echo '<p>' . $row["exam_name"] . '</p>';
                if(result_exist($conn, $uuid, $row["exam_id"])){
                    echo '<p>Status: <span class="done">Rozwiązany</span></p>';
                    echo '<p>Wynik: ' . result_data($conn, $uuid, $row["exam_id"])["exam_result_score"] . '</p>';
                }else{
                    if(date_parse(get_exam($conn, $row["exam_id"])["exam_datetime"]) >= date_parse(date('m/d/Y h:i:s', time()))){
                        echo '<p>Status: <span class="notavailable">Niedostępny</span></p>';
                        echo '<p><button class="btn btn_small disabled" onclick="window.location.href=`exam.php?exam=' . $row["exam_id"] .'`">Rozwiąż</button></p>';
                    }else{
                        echo '<p>Status: <span class="available">Dostępny</span></p>';
                        echo '<p><button class="btn btn_small" onclick="window.location.href=`exam.php?exam=' . $row["exam_id"] .'`">Rozwiąż</button></p>';
                    }
                }   
                echo '</div>';
            }
        }
      } else {
          echo "Brak sprawdzianów!";
      }

    mysqli_stmt_close($stmt);