<?php 
session_start(); 
if(!isset($_SESSION["uid"])){header("location: ../login/");} 
if(!isset($_GET["group"])){header("location: index.php");}

include_once '../includes/dbh.inc.php';
include_once '../includes/functions.inc.php';

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>szkola.online - Chat w <?php echo get_group($conn, $_GET["group"])["group_name"]; ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstaticcom" crossorigin>
    <link href="https://fonts.googleapis.com/css2?famil.y=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
    <body class="use_background">
        <?php 
            unset($_SESSION["TEMP_QUESTIONS"]);
            if(isset($_GET["error"])){
                if($_GET["error"] == "stmtfail"){
                    echo "<script>show_info_box('Wystąpił nieznany błąd! Spróbuj ponownie później!', true);</script>";
                }else if($_GET["error"] == "msgtoolong"){
                    echo "<script>show_info_box('Wiadomość może mieć maksymalnie 200 znaków!', true);</script>";
                }
            }
        ?>
        <?php include_once '../header_side.php';?>

            <div class="modal_container" id="add_students_to_group">
                <div class="modal">
                    <div class="close"><i class='bx bx-x' ></i></div>
                    <h1 class="modal_title">Dodaj uczniów</h1>
                    <div class="students_container">
                    </div>
                </div>
            </div>

            <div class="modal_container" id="view_exam_results">
                <div class="modal">
                    <div class="close"><i class='bx bx-x' ></i></div>
                    <h1 class="modal_title">Wyniki</h1>
                    <div class="exam_results_container">
                    </div>
                </div>
            </div>

            <div class="modal_container" id="create_exam">
                <div class="modal">
                    <div class="close"><i class='bx bx-x' ></i></div>
                    <h1 class="modal_title">Utwórz</h1>
                    <div class="exam_create_container">
                        <div class="input_container">
                            <div class="input_box">
                                <p>Nazwa</p>
                                <input type="text" id="EXAM_NAME">
                            </div>
                            <div class="input_box">
                                <p>Data rozpoczęcia</p>
                                <input type="date" id="EXAM_DATE">
                            </div>
                        </div>
                        <center>
                            <button class="btn btn_primary create_new_exam">Utwórz</button>
                        </center>
                    </div>
                </div>
            </div>

            <div class="modal_container" id="manage_exam">
                <div class="modal">
                    <div class="close"><i class='bx bx-x' ></i></div>
                    <h1 class="modal_title">Edytuj</h1>
                    <div class="exam_create_container">
                        <div class="input_container">
                            <div class="input_box">
                                <p>Pytanie</p>
                                <input type="text" id="QUESTION_TEXT">
                            </div>
                            <!-- <button class="btn btn_primary save_exam">Zapisz</button> -->
                        </div>

                        <div class="input_container">
                            <div class="input_box">
                                <label class="container">
                                    <input type="radio" checked="checked" name="radio" id="QUESTION_CORRECT_1">
                                    <span class="checkmark"></span>
                                </label>
                                <input type="text" id="QUESTION_ANSWER_1">
                            </div>

                            <div class="input_box">
                                <label class="container">
                                    <input type="radio" checked="checked" name="radio" id="QUESTION_CORRECT_2">
                                    <span class="checkmark"></span>
                                </label>
                                <input type="text" id="QUESTION_ANSWER_2">
                            </div>

                            <div class="input_box">
                                <label class="container">
                                    <input type="radio" checked="checked" name="radio" id="QUESTION_CORRECT_3">
                                    <span class="checkmark"></span>
                                </label>
                                <input type="text" id="QUESTION_ANSWER_3">
                            </div>

                            <div class="input_box">
                                <label class="container">
                                    <input type="radio" checked="checked" name="radio" id="QUESTION_CORRECT_4">
                                    <span class="checkmark"></span>
                                </label>
                                <input type="text" id="QUESTION_ANSWER_4">
                            </div>
                        </div>
                        <center>
                            <button class="btn btn_small add_question">Dodaj pytanie</button>
                        </center>
                    </div>
                </div>
            </div>

            <div class="panel_background">
            <button class="btn btn_float" onclick="window.location.href='index.php'"><i class='bx bx-arrow-back' ></i> Wróć</button>
                <ul>
                    <li>
                        <div class="chat_info">
                            <p class="info_small">Grupa:</p>
                            <p class="info_name"><img src="<?php echo 'https://eu.ui-avatars.com/api/?background=' . rgbcode(get_group($conn, $_GET["group"])["group_name"]) .   '&color=fff&name=' . get_group($conn, $_GET["group"])["group_name"]; ?>"><?php echo get_group($conn, $_GET["group"])["group_name"];  ?></p>
                        </div>

                        <?php
                            if($_SESSION["type"] == 1 || $_SESSION["type"] == 2){
                                echo '<div class="chat_info">';
                                echo '<p class="info_small">Ustawienia:</p>';
                                echo '<button class="btn btn_small group_add_students">Dodaj uczniów</button>';
                                echo '</div>';
                            }
                        ?>
                        <div class="submit_container">
                            <input type="text" class="message">
                            <button type="submit" class="btn btn_small message_send">Wyślij</button>
                        </div>
                        <div class="messages_container">
                        </div>
                    </li>
                    <li>
                        <p class="panel_header">Sprawdziany:</p>
                        <div class="exams_container">

                        </div>
                    </li>
                </ul>
            </div>
        <script>
            var amount = 10;
            setInterval(function(){
                get_messages();
            }, 1000);

            load_exams();

            function get_messages(){
                $.ajax('../api/get_group_messages.php?group=<?php echo $_GET["group"] ?>&am=' + amount,   // request url
                    {
                        success: function (data, status, xhr) {
                            $(".messages_container").empty();
                            $('.messages_container').append(data);
                    }
                });
            }

            function load_exams(){
                $.ajax('../api/get_exams.php?group=<?php echo $_GET["group"] ?>',
                    {
                        success: function (data, status, xhr) {
                            $(".exams_container").empty();
                            $('.exams_container').append(data);
                    }
                });
            }

            function updateScroll(){
                $(".messages_container").scrollTop($(".messages_container").prop("scrollHeight"));
            }
            
            function show_exam_results(exam){
                $("#view_exam_results").css('display', 'block');
                $.ajax('../api/get_exam_results.php?exam=' + exam,
                    {
                        success: function (data, status, xhr) {
                            $(".exam_results_container").empty();
                            $('.exam_results_container').append(data);
                    }
                });
            }

            function show_exam_settings(exam){
                $("#manage_exam").css('display', 'block');
                $("#manage_exam").attr("exam_id", exam);
            }

            $(".create_new_exam").click(function(){
                if($("#EXAM_NAME").val() != "" && $("#EXAM_DATE").val() != ""){
                    $.ajax('../api/create_exam.php?name=' + $("#EXAM_NAME").val() + "&activation=" + $("#EXAM_DATE").val() + "&group=<?php echo $_GET["group"]; ?>",
                        {
                            success: function (data, status, xhr) {
                                if(data == "EXISTS"){
                                    show_info_box("Sprawdzian o takiej nazwie już istnieje!", true);
                                }else{
                                    show_info_box("Utworzono nowy sprawdzian!", false);
                                    $("#create_exam").css('display', 'none');
                                    show_exam_settings(data); //MUSI RETURNOWAC EXAM_ID;
                                }
                        }
                    });
                }else{
                    show_info_box("Uzupełnij wszystkie pola!", true);
                }
            });

            var CORRECT_ANSWER;
            $(".add_question").click(function(){
                if($("#QUESTION_TEXT").val() != "" && $("#QUESTION_ANSWER_1").val() != "" && $("#QUESTION_ANSWER_2").val() != "" && $("#QUESTION_ANSWER_3").val() != "" && $("#QUESTION_ANSWER_4").val() != ""){
                    $.ajax('../api/create_question.php?qtext=' + $("#QUESTION_TEXT").val() + "&exam=" + $("#manage_exam").attr("exam_id"),
                        {
                            success: function (data, status, xhr) {
                                show_info_box("Dodano pytanie!", false);
                                $('*[id*=QUESTION_ANSWER]:visible').each(function(){
                                    $.ajax('../api/create_answer.php?answer=' + $(this).val() + "&question=" + data + "&exam=" + $("#manage_exam").attr("exam_id"),
                                        {
                                            success: function (data2, status, xhr) {
                                                var answer = $(check_radio()).val();
                                                var current_answer_id = data; //id answera aktualnego
                                                $.ajax('../api/get_answer.php?question=' + data + "&text=" + answer,
                                                    {
                                                        success: function (data_new, status, xhr) {
                                                            $.ajax('../api/update_question_answer.php?question=' + data + "&correct=" + data_new,
                                                            {
                                                                success: function (data_new2, status, xhr) {
                                                                    $("#QUESTION_TEXT").val("");
                                                                    $("#QUESTION_ANSWER_1").val("");
                                                                    $("#QUESTION_ANSWER_2").val("");
                                                                    $("#QUESTION_ANSWER_3").val("");
                                                                    $("#QUESTION_ANSWER_4").val("");
                                                            }
                                                        });
                                                    }
                                                });
                                        }
                                    });
                                });
                        }
                    });
                }else{
                    show_info_box("Uzupełnij wszystkie pola!", true);
                }
            });

            function check_radio(){
                var radio = $('input[name=radio]:checked');
                var a = radio.attr('id');
                var b = a.replace("QUESTION_CORRECT_", "");
                var c = "#QUESTION_ANSWER_" + b;
                return c;
            }

            function add_user_to_group(uuid){
                $.ajax('../api/add_student_to_group.php?uuid=' + uuid + "&group=<?php echo $_GET["group"]; ?>", 
                    {
                        success: function (data, status, xhr) {
                            get_students();
                            show_info_box("Dodano ucznia!", false);
                    }
                });
            }

            $('body').on('click', '.group_add_students', function(){
                $("#add_students_to_group").css('display', 'block');
                get_students();
            });

            function get_students(){
                $.ajax('../api/get_students.php?group=<?php echo $_GET["group"] ?>', 
                    {
                        success: function (data, status, xhr) {
                            $(".students_container").empty();
                            $('.students_container').append(data);
                    }
                });
            }
            
            $('body').on('click', '.create_exam', function(){
                $("#create_exam").css('display', 'block');
            })

            $(".messages_container").on('scroll', function(){
                if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                    amount = amount + 10;
                }
            });


            $(".message_send").click(function(){
                if($(".message").val().length < 200){
                    $.ajax('../api/send_message.php?text=' + $(".message").val() + "&group=<?php echo $_GET["group"]; ?>" + "&rec=null" ,   // request url
                        {
                            success: function (data, status, xhr) {
                                switch(data){
                                    case "0":
                                        show_info_box("Wiadomość może mieć maksymalnie 200 znaków!", true);
                                        break;
                                    case "1":
                                        show_info_box("Wysłano wiadomość!", false);
                                        $(".message").val("");
                                        break;
                                    case "2":
                                        show_info_box("Możliwość pisania przez uczniów została zablokowana!", true);
                                        $(".message").val("");
                                        break;
                                }
                        }
                    });
                }else{
                    show_info_box("Wiadomość może mieć maksymalnie 200 znaków!", true);
                }
            });
        </script>
         <script src="../script.js"></script>
    </body>
</html>