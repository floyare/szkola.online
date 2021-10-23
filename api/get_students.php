<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }

    if(!isset($_GET["group"])){
        header("location: ../panel/index.php?error=nogroup");
        exit();
    }

    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    if(get_students($conn, $_SESSION) != null || !empty(get_students($conn, $_SESSION) )){
        foreach(get_students($conn, $_SESSION["schoolid"]) as $student){
            //echo get_fullname($conn, $student["student_uuid"]);   
            $sql = "SELECT * FROM groups WHERE group_members LIKE ? AND group_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: ../panel/index.php?error=stmtfail");
                exit();
            }
    
            $pruuid = "%" . $student["student_uuid"] . "%";
          
            mysqli_stmt_bind_param($stmt, "si", $pruuid, $_GET["group"]);
            mysqli_stmt_execute($stmt); 
            $result = mysqli_stmt_get_result($stmt);
          
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
    
                }
              } else {
                  echo '<div class="student">';
                  echo '<p><img src="' . get_avatar($conn, $student["student_uuid"]) .'"></p>';
                  echo '<p>' . get_fullname($conn, $student["student_uuid"]) .'</p>';
                  echo '<p><button class="btn btn_small add_student_btn" onclick="add_user_to_group(`' . $student["student_uuid"] . '`)">Dodaj</button></p>';
                  echo '</div>';
              }
        
            mysqli_stmt_close($stmt);
        }
    }else{
        echo "Brak uczniów";
    }
    