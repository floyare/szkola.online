<?php
  session_start();
  if(!isset($_SESSION["uuidv4"])){
    header("location: ../login/");
    exit();
  }

  $uuid =  $_SESSION["uuidv4"];
  $ACTIVE_UUIDS = "";
  include_once '../includes/dbh.inc.php';
  include_once '../includes/functions.inc.php';

  $sql = "SELECT * FROM messages WHERE msg_outgoing = ? OR msg_ingoing = ? ORDER BY msg_id DESC";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../register/index.php?error=stmtfail");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "ss", $uuid, $uuid);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        if($row["msg_outgoing"] !== $uuid){
            if(!strpos($ACTIVE_UUIDS, $row["msg_outgoing"])){
                $ACTIVE_UUIDS = $ACTIVE_UUIDS . " " . $row["msg_outgoing"];
                echo "<div class='chat' onclick='window.location.href=`chat.php?user=" . $row["msg_outgoing"] ."`' title='" . get_fullname($conn, $row["msg_outgoing"]) ."'><img src='" . get_avatar($conn, $row["msg_outgoing"]) . "'></div>";
            }
        }elseif($row["msg_ingoing"] !== $uuid){
            if(!strpos($ACTIVE_UUIDS, $row["msg_ingoing"])){
                $ACTIVE_UUIDS = $ACTIVE_UUIDS . " " . $row["msg_ingoing"];
                if($row["msg_seen"] == "0"){
                  echo "<div class='chat' onclick='window.location.href=`chat.php?user=" . $row["msg_ingoing"] ."`' title='" . get_fullname($conn, $row["msg_ingoing"]) ."'><div class='new'></div><img src='" . get_avatar($conn, $row["msg_ingoing"]) . "'></div>";
                }else{
                  echo "<div class='chat' onclick='window.location.href=`chat.php?user=" . $row["msg_ingoing"] ."`' title='" . get_fullname($conn, $row["msg_ingoing"]) ."'><img src='" . get_avatar($conn, $row["msg_ingoing"]) . "'></div>";
                }
            }
        }
        //echo "Wiadomość: " . $row["msg_text"] . " od " . get_fullname($conn, $row["msg_ingoing"]) . "<br>";
      }
    } else {
      echo "Brak wiadomości";
    }
  mysqli_stmt_close($stmt);