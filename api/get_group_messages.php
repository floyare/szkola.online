<?php
  session_start();
  if(!isset($_SESSION["uuidv4"])){
    header("location: ../login/");
    exit();
  }

  $uuid =  $_SESSION["uuidv4"];
  $target = $_GET["group"];
  $load_msg = $_GET["am"];

  include_once '../includes/dbh.inc.php';
  include_once '../includes/functions.inc.php';

  $sql = "SELECT * FROM group_messages WHERE msg_group = ? ORDER BY msg_id DESC LIMIT " . $load_msg;
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../panel/group.php?error=stmtfail");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "i", $target);
  mysqli_stmt_execute($stmt); 
  $result = mysqli_stmt_get_result($stmt);

  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $time = date('m/d/Y H:i:s', $row["msg_timespan"]);
        if($row["msg_sender"] == $uuid){
            see_message($conn, $row["msg_id"], true);
            echo "<div class='message me'><img src='" . get_avatar($conn, $row["msg_sender"]) . "' title='" . get_fullname($conn, $row["msg_sender"]) . "'><p>" . $row["msg_text"] . "</p><p class='date'>" . $time. "</p></div>";
        }else{
            if(strpos($row["msg_saw"], $_SESSION["uuidv4"]) === false){
               see_message($conn, $row["msg_id"], true);
            }
            echo "<div class='message'><img src='" . get_avatar($conn, $row["msg_sender"]) . "' title='" . get_fullname($conn, $row["msg_sender"]) . "'><p>" . $row["msg_text"] . "</p><p class='date'>" . $time . "</p></div>";
        }
      }
    } else {
      echo "Brak wiadomości";
    }
  mysqli_stmt_close($stmt);