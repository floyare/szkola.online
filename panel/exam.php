<?php 
session_start(); 
if(!isset($_SESSION["uid"])){header("location: ../login/"); exit();} 
if(!isset($_GET["exam"])){header("location: index.php"); exit();}
// if(!isset($_GET["q"])){header("location: index.php"); exit();}
//pytania będa w jednej stronie exam.php bez zmiany strony tylko bedzie ladowalo nowe pytanie z submit_answer.php

include_once '../includes/dbh.inc.php';
include_once '../includes/functions.inc.php';

if(date_parse(get_exam($conn, $_GET["exam"])["exam_datetime"]) >= date_parse(date('m/d/Y h:i:s', time()))){
    header("location: ../panel/index.php?error=examnotready");
    exit();
}

if(!isset($_SESSION["score"])){
    $_SESSION["score"]  = 0;
}

if(user_completed_exam($conn, $_GET["exam"], $_SESSION["uuidv4"])){
    header("location: ../panel/index.php?error=examalreadycompleted");
    exit();
}

//JEZELI UZYTKOWNIK ODŚWIEŻYL STRONE
$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
if($pageWasRefreshed){
    unset($_SESSION["USED_QUESTIONS"]);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>szkola.online - Egzamin</title>

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
            //JAK NIE DZIALA TO SPORBUJ TO
            //unset($_SESSION["score"]); 
            //unset($_SESSION["USED_QUESTIONS"]);
            //unset($_SESSION["CURRENT_QUESTION"]);
            if(isset($_GET["error"])){
                if($_GET["error"] == "stmtfail"){
                    echo "<script>show_info_box('Wystąpił nieznany błąd! Spróbuj ponownie później!', true);</script>";
                }
            }
        ?>
        <div class="question_wrapper">
            <div class="question_box">
                <div class="question_content">
                    
                </ul>
                </div>
            </div>
        </div>
        <script>
            generate_question();
            function generate_question(){
                $.ajax('../api/generate_question.php?exam=<?php echo $_GET["exam"] ?>',
                    {
                        success: function (data, status, xhr) {
                            $(".question_content").empty();
                            $('.question_content').append(data);
                    }
                });
            }

            var selected_answer;
            $('body').on('click', '.answer', function(){
                $(".answer").removeClass("selected");
                $(this).addClass("selected");
                selected_answer = $(this).attr("id");
                console.log(selected_answer);
            })

            //JEŻELI KLIKNIETO "PRZEŚLIJ"
            $('body').on('click', '.answer_send', function(){
                if(selected_answer != null){
                    $.ajax('../api/submit_answer.php?e=<?php echo $_GET["exam"] ?>&a=' + selected_answer,
                        {
                            success: function (data, status, xhr) {
                                $(".question_content").empty();
                                $('.question_content').append(data);
                                if(data == "Przkierowywanie..."){
                                    window.location.href = "index.php?error=examcompleted";
                                }else{
                                    generate_question();
                                }
                                selected_answer = null;
                        }
                    });
                }
            })

            var focus;
            $(window).focus(function() {
                focus = true;
            }).blur(function() {
                focus = false;
            });

            var check = false;
            setInterval(function() {
                if(!check){
                    if(!focus){
                        show_info_box("Nie wychodź poza obszar egzaminu!", true);
                        check = true;
                    }
                }else if(check){
                    if(focus){
                        check = false;
                    }
                }
            }, 500);
        </script>
         <script src="../script.js"></script>
    </body>
</html>