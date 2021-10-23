<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }

    if(!isset($_GET["exam"])){
        header("location: index.php?error=examnotfound");
        exit(); 
    }

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    $EXAM_ID = $_GET["exam"];
    if(!isset($_SESSION["USED_QUESTIONS"])){
        $_SESSION["USED_QUESTIONS"] = array();
    }

    if(!isset($_SESSION["score"])){
        $_SESSION["score"] = 0;
    }

    $sql = "SELECT * FROM exam_questions WHERE exam_id = ?";
    if(!empty($_SESSION["USED_QUESTIONS"])){
        foreach($_SESSION["USED_QUESTIONS"] as $QUESTION){
            $sql .= " AND question_id != " . $QUESTION;
        }
    }

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $EXAM_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);



    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["CURRENT_QUESTION"] = $row["question_id"];
        array_push($_SESSION["USED_QUESTIONS"], $row["question_id"]);
        //ILOŚĆ PYTAŃ
        echo '<p class="question_amount">' . count($_SESSION["USED_QUESTIONS"]) . "/" . get_max_questions($conn, $EXAM_ID) . '</p>';

        //NAZWA PYTANIA
        echo '<h2 class="question_name"><span class="question_id">' . count($_SESSION["USED_QUESTIONS"]) . '. </span>' . $row["question_text"] . '</h2>';
        echo '<ul>';

        //ODPOWIEDZI
        echo  get_answers($conn, $row["question_id"]);
        echo '<button class="btn btn_primary answer_send">Prześlij</button>';
        echo '</ul>';
        
        //echo $row["question_text"] . "<br>";
      } else {
          echo "Brak pytan!";
      }

    mysqli_stmt_close($stmt);