<?php
  session_start();
  if(!isset($_SESSION["uuidv4"])){
    header("location: ../login/");
    exit();
  }

  $uuid =  $_SESSION["uuidv4"];
  $target = $_GET["user"];
  $load_msg = $_GET["am"];

  include_once '../includes/dbh.inc.php';
  include_once '../includes/functions.inc.php';

  $sql = "SELECT * FROM messages WHERE msg_outgoing = ? AND msg_ingoing = ? OR msg_outgoing = ? AND msg_ingoing = ?  ORDER BY msg_id DESC LIMIT " . $load_msg;
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../panel/chat.php?error=stmtfail");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "ssss", $uuid, $target, $target, $uuid);
  mysqli_stmt_execute($stmt); 
  $result = mysqli_stmt_get_result($stmt);

  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $time = date('m/d/Y H:i:s', $row["msg_timespan"]);
        if($row["msg_ingoing"] == $uuid){
            echo "<div class='message me'><img src='" . get_avatar($conn, $row["msg_ingoing"]) . "' title='" . get_fullname($conn, $row["msg_ingoing"]) . "'><p>" . $row["msg_text"] . "</p><p class='date'>" . $time. "</p></div>";
        }else{
            if($row["msg_seen"] == 0){
              see_message($conn, $row["msg_id"], false);
            }
            echo "<div class='message'><img src='" . get_avatar($conn, $row["msg_ingoing"]) . "' title='" . get_fullname($conn, $row["msg_ingoing"]) . "'><p>" . $row["msg_text"] . "</p><p class='date'>" . $time . "</p></div>";
        }
        //echo "Wiadomość: " . $row["msg_text"] . " od " . get_fullname($conn, $row["msg_ingoing"]) . "<br>";
      }
    } else {
      echo "<p class='message_info'><i class='bx bxs-happy'></i> Rozpocznij konwersacje z <span>" . get_fullname($conn, $target) . "</span>!</p>";
    }
  mysqli_stmt_close($stmt);