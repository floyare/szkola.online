<?php session_start(); if(!isset($_SESSION["uid"])){header("location: ../login/"); exit();} if($_SESSION["type"] != 2){header("location: index.php"); exit();}  include_once '../includes/dbh.inc.php'; include_once '../includes/functions.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>szkola.online - Ustawienia</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
    <body class="use_background">
    <?php 
        if(isset($_GET["error"])){
            if($_GET["error"] == "stmtfail"){
                echo "<script>show_info_box('Wystąpił nieznany błąd! Spróbuj ponownie później!', true);</script>";
            }
        }
    ?>
    <div class="modal_container" id="add_student">
            <div class="modal">
                <div class="close"><i class='bx bx-x' ></i></div>
                <h1 class="modal_title">Dodaj ucznia</h1>
                <div class="input_container">
                    <div class="input_box">
                        <p>Imię</p>
                        <input type="text" id="STUDENT_NAME">
                    </div>
                    <div class="input_box">
                        <p>Nazwisko</p>
                        <input type="text" id="STUDENT_SURNAME">
                    </div>
                    <div class="input_box">
                        <p>Data urodzenia</p>
                        <input type="date" id="STUDENT_DATE">
                    </div>
                    <div class="input_box">
                        <p>Klasa</p>
                        <input type="text" id="STUDENT_CLASS">
                    </div>
                    <div class="input_box">
                        <p>E-mail</p>
                        <input type="text" id="STUDENT_MAIL">
                    </div>
                    <div class="input_box">
                        <p>Hasło</p>
                        <input type="password" id="STUDENT_PWD">
                    </div>
                    <div class="modal_accept_container">
                        <input class="hidden-xs-up" id="cbx" type="checkbox">
                        <label class="cbx" for="cbx"></label>
                        <label class="lbl" for="cbx">Potwierdzam poprawność danych i akceptuje regulamin dotyczący przechowywania danych o uczniach, nauczycielach i samej szkole.</label>
                    </div>
                    <button class="btn btn_primary" id="create_student">Dodaj</button>
                </div>
            </div>
        </div>



        <div class="modal_container" id="add_teacher">
            <div class="modal">
                <div class="close"><i class='bx bx-x' ></i></div>
                <h1 class="modal_title">Dodaj nauczyciela</h1>
                <div class="input_container">
                    <div class="input_box">
                        <p>Imię</p>
                        <input type="text" id="TEACHER_NAME">
                    </div>
                    <div class="input_box">
                        <p>Nazwisko</p>
                        <input type="text" id="TEACHER_SURNAME">
                    </div>
                    <div class="input_box">
                        <p>Przedmiot</p>
                        <input type="text" id="TEACHER_SUBJECT">
                    </div>
                    <div class="input_box">
                        <p>E-mail</p>
                        <input type="text" id="TEACHER_MAIL">
                    </div>
                    <div class="input_box">
                        <p>Hasło</p>
                        <input type="password" id="TEACHER_PWD">
                    </div>
                    <div class="modal_accept_container">
                        <input class="hidden-xs-up" id="cbxT" type="checkbox">
                        <label class="cbx" for="cbxT"></label>
                        <label class="lbl" for="cbxT">Potwierdzam poprawność danych i akceptuje regulamin dotyczący przechowywania danych o uczniach, nauczycielach i samej szkole.</label>
                    </div>
                    <button class="btn btn_primary" id="create_teacher">Dodaj</button>
                </div>
            </div>
        </div>
        <?php include_once '../header.php';?>
        <div class="panel_container">
            <div class="panel_box">
                <h1 class="box_header">Szkoła</h1>
                <div class="info_container">
                    <p class="box_subheader">Dane szkoły:</p>
                    <ul>
                        <li class="info_text">Nazwa szkoły: <span><?php echo get_school($conn, $_SESSION["uuidv4"])["school_name"]; ?></span></li>
                    </ul>
                    <button class="btn btn_small" onclick="show_modal('add_student')">Dodaj uczniów</button>
                    <button class="btn btn_small" onclick="show_modal('add_teacher')">Dodaj nauczycieli</button>

                    <p class="box_subheader">Lista uczniów:</p>
                        <div class="list" id="students">
                        </div>


                        <p class="box_subheader">Lista Nauczycieli:</p>
                        <div class="list" id="teachers">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../script.js"></script>
        <script>
            load_members(0);
            load_members(1);
            function load_members(type){
                switch(type){
                    case 0:
                        $.ajax('../api/get_members.php?t=' + type,
                            {
                                success: function (data, status, xhr) {
                                    $("#students").empty();
                                    $('#students').append(data);
                            }
                        });
                        break;
                    case 1:
                        $.ajax('../api/get_members.php?t=' + type,
                            {
                                success: function (data, status, xhr) {
                                    $("#teachers").empty();
                                    $('#teachers').append(data);
                            }
                        });
                        break;
                }
            }

            $("#create_student").click(function(){
                if($("#STUDENT_NAME").val() == "" || $("#STUDENT_SURNAME").val() == "" ||  $("#STUDENT_DATE").val() == "" ||  $("#STUDENT_CLASS").val() == "" ||  $("#STUDENT_MAIL").val() == "" ||  $("#STUDENT_PWD").val() == ""){
                    show_info_box("Uzupełnij wszystkie pola!", true);
                }else{
                    if($("#cbx").prop("checked") == true){
                        $.ajax('../api/create_student.php?n=' + $("#STUDENT_NAME").val() + "&s=" +  $("#STUDENT_SURNAME").val() + "&u=<?php echo gen_uuid(); ?>" + "&e=" +  $("#STUDENT_MAIL").val() + "&p=" +  $("#STUDENT_PWD").val() + "&b=" +  $("#STUDENT_DATE").val() + "&c=" +  $("#STUDENT_CLASS").val() + "&sid=<?php echo get_school($conn, $_SESSION["uuidv4"])["school_id"]; ?>",
                            {
                                success: function (data, status, xhr) {
                                    $(".modal_container").css('display', 'none');
                                    show_info_box("Pomyślnie utworzono!", false);
                                    load_members(0);
                            }
                        });
                    }else{
                        show_info_box("Potwierdź regulamin!", true);
                    }
                }
            });

            $("#create_teacher").click(function(){
                if($("#TEACHER_NAME").val() == "" || $("#TEACHER_SURNAME").val() == "" ||  $("#TEACHER_DATE").val() == "" ||  $("#TEACHER_CLASS").val() == "" ||  $("#TEACHER_MAIL").val() == "" ||  $("#TEACHER_PWD").val() == ""){
                    show_info_box("Uzupełnij wszystkie pola!", true);
                }else{
                    if($("#cbxT").prop("checked") == true){
                        $.ajax('../api/create_teacher.php?n=' + $("#TEACHER_NAME").val() + "&s=" +  $("#TEACHER_SURNAME").val() + "&u=<?php echo gen_uuid(); ?>" + "&e=" +  $("#TEACHER_MAIL").val() + "&p=" +  $("#TEACHER_PWD").val() + "&sub=" +  $("#TEACHER_SUBJECT").val() + "&sid=<?php echo get_school($conn, $_SESSION["uuidv4"])["school_id"]; ?>",
                            {
                                success: function (data, status, xhr) {
                                    $(".modal_container").css('display', 'none');
                                    show_info_box("Pomyślnie utworzono!", false);
                                    load_members(1);
                            }
                        });
                    }else{
                        show_info_box("Potwierdź regulamin!", true);
                    }
                }
            });
        </script>
    </body>
</html>