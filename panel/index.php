<?php session_start(); if(!isset($_SESSION["uid"])){header("location: ../login/");} include_once '../includes/dbh.inc.php'; include_once '../includes/functions.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>szkola.online - Panel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="../script.js"></script>
</head>
    <body class="use_background">
        <?php 
            if(isset($_GET["error"])){
                if($_GET["error"] == "stmtfail"){
                    echo "<script>show_info_box('Wystąpił nieznany błąd! Spróbuj ponownie później!', true);</script>";
                }elseif($_GET["error"] == "examcompleted"){
                    echo "<script>show_info_box('Dziękujemy za rozwiązanie testu!', false);</script>";
                }elseif($_GET["error"] == "examnotfound"){
                    echo "<script>show_info_box('Nie znaleziono egzaminu!', true);</script>";
                }elseif($_GET["error"] == "examalreadycompleted"){
                    echo "<script>show_info_box('Egzamin został już zatwierdzony!', true);</script>";
                }elseif($_GET["error"] == "examnotready"){
                    echo "<script>show_info_box('Egzamin nie został jeszcze aktywowany!', true);</script>";
                }
            }
        ?>

        <div class="modal_container" id="modal_teacher">
                <div class="modal">
                    <div class="close"><i class='bx bx-x' ></i></div>
                    <h1 class="modal_title">Kontakty</h1>
                    <div class="modal_contacts">
                    </div>
                </div>
        </div>

        
        <div class="modal_container" id="new_group">
                <div class="modal">
                    <div class="close"><i class='bx bx-x' ></i></div>
                    <h1 class="modal_title">Nowa grupa</h1>
                    <div class="new_group_container">
                        <div class="input_container">
                            <div class="input_box">
                                <p>Nazwa grupy</p>
                                <input type="text" id="GROUP_NAME">
                            </div>
                        </div>
                        <center>
                            <button class="btn btn_primary create_new_group">Utwórz</button>
                        </center>
                    </div>
                </div>
        </div>

        <?php include_once '../header_side.php';?>
        <div class="panel_background">
            <ul>
                <li>
                    <div class="panel_board">
                        <p class="test"></p>
                        <p class="panel_header">Aktywne chaty:</p>
                        <div class="active_chats">
                        </div>
                        <div class="panel_seperated">
                        <?php 

                            if($_SESSION["type"] == 2 || $_SESSION["type"] == 1){
                                echo '<p class="panel_text">Rozpocznij konwersację! <button class="btn btn_small" id="s_help">Wybierz ucznia!</button></p>';
                            }else{
                                echo '<p class="panel_text">Potrzebujesz pomocy? <button class="btn btn_small" id="t_help">Skontaktuj się z nauczycielem!</button></p>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="panel_board">
                        <p class="panel_header">Grupy:</p>
                        <?php
                            if($_SESSION["type"] == 1 || $_SESSION["type"] == 2){
                                echo '<button class="btn btn_small create_group">Utwórz grupę</button>';
                            }
                        ?>
                        <div class="active_groups">
                        </div>
                    </div>
                </li>
                <li>
                </li>
            </ul>
        </div>
        <script>
             $('body').on('click', '.create_group', function(){
                show_modal('new_group');
             });

             $(".create_new_group").click(function(){
                if($("#GROUP_NAME").val() != null){
                    $.ajax('../api/create_group.php?name=' + $("#GROUP_NAME").val(),   
                        {
                            success: function (data, status, xhr) {
                                $("#new_group").css('display', 'none');
                                show_info_box("Utworzono grupę!", false);
                        }
                    });
                }else{
                    show_info_box("Uzupełnij wszystkie pola!", true);
                }
             });

            function get_chats(){
                $.ajax('../api/get_chats.php',   
                {
                    success: function (data, status, xhr) {
                        $('.active_chats').empty();
                        $('.active_chats').append(data);
                        check_for_notify();
                }
            });
            }

            function get_contacts(type){
                $.ajax('../api/get_contacts.php?t=' + type, 
                    {
                        success: function (data, status, xhr) {
                            $(".modal_contacts").empty();
                            $('.modal_contacts').append(data);
                    }
                });
            }

            function get_groups(){
                $.ajax('../api/get_groups.php',
                    {
                        success: function (data, status, xhr) {
                            $(".active_groups").empty();
                            $('.active_groups').append(data);
                    }
                });
            }

            var a = false;
            function check_for_notify(){
                if(!a){
                    if($(".new").is(":visible")){
                        playSound("https://audio-previews.elements.envatousercontent.com/files/111190363/preview.mp3?response-content-disposition=attachment%3B+filename%3D%22PTQJUES-message-notification.mp3%22");
                        a = true;
                    }else{
                        a = false;
                    }
                }

            }

            $("#t_help").click(function(){
                show_modal("modal_teacher");
                get_contacts(0);
            });

            
            $("#s_help").click(function(){
                show_modal("modal_teacher");
                get_contacts(1);
            });

            setInterval(function(){
                get_chats();
                get_groups();
            }, 1000);
        </script>
        <script src="../script.js"></script>
    </body>
</html>