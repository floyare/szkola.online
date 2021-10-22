<?php
    session_start();
    if(!isset($_SESSION["uuidv4"])){
        header("location: ../login/");
        exit();
    }
    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';
    $uuid = $_SESSION["uuidv4"];

    $pr_uuid = "%$uuid%";
    $sql = "SELECT * FROM groups WHERE group_members LIKE ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $pr_uuid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $LIST = get_last_message($conn, $row["group_id"])["msg_saw"];
            $UUID = $_SESSION["uuidv4"];
            //echo get_last_message($conn, $row["group_id"])["msg_saw"];
            if(strpos($LIST, $UUID) === false){
                echo '<div class="group" onclick="window.location.href=`group.php?group=' . $row["group_id"] . '`"><div class="new"></div><img class="group_image" src="https://eu.ui-avatars.com/api/?background=' . rgbcode($row["group_name"]) .   '&color=fff&name=' . $row["group_name"] .'"><p class="group_name">' . $row["group_name"] . '</p></div>';
            }
            else{
                echo '<div class="group" onclick="window.location.href=`group.php?group=' . $row["group_id"] . '`"><img class="group_image" src="https://eu.ui-avatars.com/api/?background=' . rgbcode($row["group_name"]) .   '&color=fff&name=' . $row["group_name"] .'"><p class="group_name">' . $row["group_name"] . '</p></div>';
            }
            //echo $row["school_name"] . " " . $row["school_address"] . " - " . $row["school_city"];
        }
      } else {
          echo "Brak grup!";
      }

    mysqli_stmt_close($stmt);