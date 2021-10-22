<?php
    session_start();
    $uuid =  $_GET["uuid"];
    include_once '../includes/dbh.inc.php';
    include_once '../includes/functions.inc.php';

    $sql = "SELECT * FROM schools WHERE school_owner = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uuid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    if ($result->num_rows > 0) {
        echo '<p class="subheader">Twoja szkoła:</p>';
        while($row = $result->fetch_assoc()) {
            echo '<ul>';
            echo '  <li>';
            echo '      <p class="element"><i class="bx bxs-school"></i></p>';
            echo '      <p class="element"><span>' . $row["school_name"] . '</span></p>';
            echo '      <p class="element" id="element_right"><button class="btn btn_small" onclick="window.location.href=`school.php`">Zarządzaj</button></p>';
            echo '  </li>';
            echo '</ul>';
            //echo $row["school_name"] . " " . $row["school_address"] . " - " . $row["school_city"];
        }
      } else {
        echo '<div class="school_not_found">';
        echo '  <p onclick="show_modal(`modal_school`)"><i class="bx bxs-plus-circle"></i> Dodaj szkołę, aby rozpocząć konfigurację!</p>';
        echo '</div>';
      }

    mysqli_stmt_close($stmt);