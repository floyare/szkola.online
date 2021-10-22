<?php

function emptyInputLogin($mail, $pwd){
    if(empty($mail) || empty($pwd)){
        return true;
    }else{
        return false;
    }
}

function emptyInputSignup($name, $surname, $email, $pwd, $pwdr){
    if(empty($name) || empty($surname) || empty($email) || empty($pwd) || empty($pwdr)){
        return true;
    }else{
        return false;
    }
}

function invalidName($name, $surname){
    if(preg_match("/^[\s\p{L}]+$/u", $name) && preg_match("/^[\s\p{L}]+$/u", $surname)){
        return false;
    }else{
        return true;
    }
}

function invalidEmail($email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return false;
    }else{
        return true;
    }
}

function pwdMatch($pwd, $pwdr){
    if($pwd !== $pwdr){
        return true;
    }else{
        return false;
    }
}

function emailExists($conn, $email){
    $sql = "SELECT * FROM users WHERE user_email = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../register/index.php?error=stmtfail_EXISTMAIL");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row;
    }else{
        return false;
    }

    mysqli_stmt_close($stmt);
}

function createMember($conn, $name, $surname, $uuid, $email, $pwd, $birth, $class, $school_id, $member_type){
    //STUDENT
    switch($member_type){
        case 0:
            $sql = "INSERT INTO students (student_uuid, student_name, student_surname, student_birth, student_class, school_id) VALUES (?, ?, ?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: ../settings/school.php?error=usercreatefail");
                exit();
            }
        
           // $type = 2; //0 = student, 1 = teacher, 2 = headmaster
            mysqli_stmt_bind_param($stmt, "sssssi", $uuid, $name, $surname, $birth, $class, $school_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
    
            createUser($conn, $name, $surname, $uuid, $email, $pwd, $member_type);
            header("location: ../settings/school.php?error=none");
            exit();
            break;
        case 1:
            $subject = $birth;
            $sql = "INSERT INTO teachers (teacher_uuid, teacher_name, teacher_surname, teacher_subject, school_id) VALUES (?, ?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: ../settings/school.php?error=usercreatefail");
                exit();
            }
        
           // $type = 2; //0 = student, 1 = teacher, 2 = headmaster
            mysqli_stmt_bind_param($stmt, "ssssi", $uuid, $name, $surname, $subject, $school_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
    
            createUser($conn, $name, $surname, $uuid, $email, $pwd, $member_type);
            header("location: ../settings/school.php?error=none");
            exit();
            break;
    }
}

function createUser($conn, $Name, $surname, $uuid, $email, $pwd, $type){
    $sql = "INSERT INTO users (user_uuid, user_email, user_password, user_name, user_surname, user_creation, user_type, user_avatar) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../register/index.php?error=stmtfail_CREATING");
        exit();
    }

    $hashed = password_hash($pwd, PASSWORD_DEFAULT);

    $creation = time();
    $avatar = "https://eu.ui-avatars.com/api/?name=" . $Name . "+" . $surname ."&background=" . rgbcode($Name . " " . $surname . $creation);
   // $type = 2; //0 = student, 1 = teacher, 2 = headmaster
    mysqli_stmt_bind_param($stmt, "ssssssis", $uuid, $email, $hashed, $Name, $surname, $creation, $type, $avatar);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../register/index.php?error=none");
    exit();
}

function get_avatar($conn, $uuidf){
    $sql = "SELECT * FROM users WHERE user_uuid = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../portal/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uuidf);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row["user_avatar"];
    }else{
        return false;
    }

    mysqli_stmt_close($stmt);
}

function see_message($conn, $msgid, $isgroup){
    if(!$isgroup){
        $sql = "UPDATE messages SET msg_seen = 1 WHERE msg_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../portal/index.php?error=stmtfail");
            exit();
        }
    
        mysqli_stmt_bind_param($stmt, "i", $msgid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }else{
        $sql = "UPDATE group_messages SET msg_saw = CONCAT(msg_saw, ?) WHERE msg_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../portal/index.php?error=stmtfail");
            exit();
        }
        
        $fixed = $_SESSION["uuidv4"] . ", ";
        mysqli_stmt_bind_param($stmt, "si", $fixed, $msgid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function get_last_message($conn, $groupid){
    $sql = "SELECT * FROM group_messages WHERE msg_group = ? ORDER BY msg_id DESC";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../portal/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $groupid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row;
    }else{
        return false;
    }

    mysqli_stmt_close($stmt);
}

function get_student($conn, $uuidf){
    $sql = "SELECT * FROM students WHERE student_uuid = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../portal/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uuidf);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row;
    }else{
        return false;
    }

    mysqli_stmt_close($stmt);
}

function get_teacher($conn, $uuidf){
    $sql = "SELECT * FROM teachers WHERE teacher_uuid = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../portal/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uuidf);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row;
    }else{
        return false;
    }

    mysqli_stmt_close($stmt);
}

function get_school($conn, $uuidf){
    $sql = "SELECT * FROM schools WHERE school_owner = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../settings/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uuidf);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row;
    }else{
        header("location: ../settings/index.php?error=noschool");
        exit();
    }

    mysqli_stmt_close($stmt);
}

function get_group($conn, $id){
    $sql = "SELECT * FROM groups WHERE group_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row;
    }else{
        header("location: ../settings/index.php?error=noschool");
        exit();
    }

    mysqli_stmt_close($stmt);
}

function get_fullname($conn, $uuidf){
    $sql = "SELECT * FROM users WHERE user_uuid = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../portal/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uuidf);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row["user_name"] . " " . $row["user_surname"];
    }else{
        return false;
    }

    mysqli_stmt_close($stmt);
}

function get_password($conn, $uuidf){
    $sql = "SELECT * FROM users WHERE user_uuid = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../portal/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uuidf);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row["user_password"];
    }else{
        return false;
    }

    mysqli_stmt_close($stmt);
}

function get_talk_permission($conn, $uuidf){
    $sql = "SELECT school_chat_student_talk_allow FROM schools WHERE school_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", get_student($conn, $uuidf)["school_id"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row;
    }else{
        header("location: ../settings/index.php?error=noschool");
        exit();
    }

    mysqli_stmt_close($stmt);
}

function get_max_questions($conn, $id){
    $sql = "SELECT * FROM exams WHERE exam_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row["exam_total_questions"];
    }else{
        header("location: ../panel/index.php?error=noexamfound3");
        exit();
    }

    mysqli_stmt_close($stmt);
}

function get_current_question($conn, $id){
    $sql = "SELECT * FROM exam_questions WHERE question_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }


    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row;
    }else{
        header("location: ../panel/index.php?error=noexamfound");
        exit();
    }

    mysqli_stmt_close($stmt);
}

function get_question($conn, $id, $USED_QUESTIONS){
    $sql = "SELECT * FROM exam_questions WHERE exam_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if(!empty($USED_QUESTIONS)){
        foreach($USED_QUESTIONS as $QQ){
            $sql .= " AND question_id != " . $QQ;
        }
    }

    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }


    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row;
    }else{
        header("location: ../panel/index.php?error=noexamfound");
        exit();
    }

    mysqli_stmt_close($stmt);
}

function get_answers($conn, $id){
    $sql = "SELECT * FROM exam_answers WHERE answer_question_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/chat.php?error=stmtfail");
        exit();
    }
  
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt);
  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<li class='answer' id='" . $row["answer_id"] . "'><div class='answer_box'><p>"  . $row["answer_text"] . "</p></div></li>";
        }
      } else {
       
      }

    mysqli_stmt_close($stmt);
}

function get_exam($conn, $id){
    $sql = "SELECT * FROM exams WHERE exam_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        return $row;
    }else{
        header("location: ../panel/index.php?error=noexamfound");
        exit();
    }

    mysqli_stmt_close($stmt);
}

function result_data($conn, $uuidf, $id){
    $sql = "SELECT * FROM exam_results WHERE exam_result_exam_id = ? AND exam_result_student = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "is", $id, $uuidf);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row;
        }
    }else{
        return false;
    }
}

function result_exist($conn, $uuidf, $id){
    $sql = "SELECT * FROM exam_results WHERE exam_result_exam_id = ? AND exam_result_student = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "is", $id, $uuidf);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    if ($result->num_rows > 0) {
        return true;
    }else{
        return false;
    }
}

function update_result($conn, $uuidf, $id, $score, $status){
    $sql = "";
    $EXAM = get_exam($conn, $id);
    if(result_exist($conn, $uuidf, $id)){
        $sql = "UPDATE exam_results SET exam_result_name = ?, exam_result_score = ?, exam_result_student = ?, exam_result_max_score = ?, exam_status = ?, exam_result_exam_id = ? WHERE exam_result_student = ? AND exam_result_exam_id = ? ;";
    }else{
        $sql = "INSERT INTO exam_results (exam_result_name, exam_result_score, exam_result_student, exam_result_max_score, exam_status, exam_result_exam_id) VALUES (?, ?, ?, ?, ?, ?);";
    }
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../register/index.php?error=stmtfail");
        exit();
    }

   // $type = 2; //0 = student, 1 = teacher, 2 = headmaster
   if(result_exist($conn, $uuidf, $id)){
    mysqli_stmt_bind_param($stmt, "sisiiisi", $EXAM["exam_name"], $score, $uuidf, $EXAM["exam_total_questions"], $status, $id, $uuidf, $id);
   }else{
        mysqli_stmt_bind_param($stmt, "sisiii", $EXAM["exam_name"], $score, $uuidf, $EXAM["exam_total_questions"], $status, $id);
   }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function user_completed_exam($conn, $examid, $uuidf){
    $sql = "SELECT * FROM exam_results WHERE exam_result_exam_id = ? AND exam_result_student = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/index.php?error=stmtfail");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "is", $examid, $uuidf);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
        if($row["exam_status"] == 1){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function generate_question($conn, $examid, $excepts){
    $sql = "SELECT * FROM exam_questions WHERE exam_id = ? ";
    if($excepts != null){
        foreach($excepts as $exception){
            $sql = $sql . " AND exam_id != " . $exception;
        }
    }

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/chat.php?error=stmtfail");
        exit();
    }
  
    mysqli_stmt_bind_param($stmt, "i", $examid);
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt);
  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<li class='answer' id='" . $row["answer_id"] . "'><div class='answer_box'><p>"  . $row["answer_text"] . "</p></div></li>";
        }
      } else {
       
      }

    mysqli_stmt_close($stmt);
}

function get_groups($conn, $uuidf){
    $sql = "SELECT * FROM groups WHERE group_members = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../panel/chat.php?error=stmtfail");
        exit();
    }
    $pruuid = "%$uuidf%";
  
    mysqli_stmt_bind_param($stmt, "s", $pruuid);
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt);
  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row;
        }
      } else {
       
      }

    mysqli_stmt_close($stmt);
}

function loginUser($conn, $mail, $pwd){
    $exists = emailExists($conn, $mail);
    if(!$exists){
        header("location: ../login/index.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $exists["user_password"];
    $check = password_verify($pwd, $pwdHashed);
    if(!$check){
        header("location: ../login/index.php?error=wronglogin");
        exit();
    }else{
        session_start();
        $_SESSION["uid"] = $exists["user_id"];
        $_SESSION["uuidv4"] = $exists["user_uuid"];
        $_SESSION["mail"] = $exists["user_email"];
        $_SESSION["fullname"] = $exists["user_name"] . " " . $exists["user_surname"];
        $_SESSION["schoolid"] = get_student($conn, $exists["user_uuid"])["school_id"];
        $hidden_pwd = "";
        for($x = 0; $x <= strlen($pwd); $x++){
            $hidden_pwd = $hidden_pwd . "*";
        }
        $_SESSION["pwd"] = $hidden_pwd;
        $_SESSION["type"] = $exists["user_type"];
        header("location: ../panel/");
        exit();
    }
}

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function rgbcode($id){
    return substr(md5($id), 0, 6);
}