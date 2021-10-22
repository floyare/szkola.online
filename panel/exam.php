<?php 
session_start(); 
if(!isset($_SESSION["uid"])){header("location: ../login/"); exit();} 
if(!isset($_GET["exam"])){header("location: index.php"); exit();}
// if(!isset($_GET["q"])){header("location: index.php"); exit();}
//pytania będa w jednej stronie exam.php bez zmiany strony tylko bedzie ladowalo nowe pytanie z submit_answer.php

include_once '../includes/dbh.inc.php';
include_once '../includes/functions.inc.php';

if(!isset($_SESSION["qid"])){
    $_SESSION["qid"]  = array();
}

if(!isset($_SESSION["score"])){
    $_SESSION["score"]  = 0;
}

if(user_completed_exam($conn, $_GET["exam"], $_SESSION["uuidv4"])){
    header("location: ../panel/index.php?error=examalreadycompleted");
    exit();
}

if(count($_SESSION["qid"]) < 1){
    array_push($_SESSION["qid"], 1);
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
            //unset($_SESSION["qid"]);
            //unset($_SESSION["COMPLETED_QUESTIONS"]);
            //unset($_SESSION["score"]);
            if(isset($_GET["error"])){
                if($_GET["error"] == "stmtfail"){
                    echo "<script>show_info_box('Wystąpił nieznany błąd! Spróbuj ponownie później!', true);</script>";
                }
            }
        ?>
        <div class="question_wrapper">
            <div class="question_box" id="q1">
                <div class="question_content">
                <?php
                    $QUESTION =  get_question($conn, $_GET["exam"], null);
                    echo '<p class="question_amount">' . count($_SESSION["qid"]) . "/" . get_max_questions($conn, $_GET["exam"]) . '</p>';
                    echo '<h2 class="question_name"><span class="question_id">' . count($_SESSION["qid"]) . '.</span> ' . $QUESTION["question_text"] .'</h2>';
                    echo '<ul>';
                    echo get_answers($conn, $QUESTION["question_id"]);
                    echo '<button class="btn btn_primary answer_send">Prześlij</button>';
                    echo "</ul>";
                
                ?>
                </ul>
                </div>
            </div>
        </div>
        <script>
            var question_number = 1;
            var selected_answer;
            $('body').on('click', '.answer', function(){
                $(".answer").removeClass("selected");
                $(this).addClass("selected");
                selected_answer = $(this).attr("id");
                console.log(selected_answer);
            })

            // $.ajax('../api/get_exam.php?e=<?php echo $_GET["exam"] ?>&q=<?php echo get_question($conn, $_GET["exam"], NULL)["question_id"];?>&a=' + null + '&i=' + question_number,
            //         {
            //             success: function (data, status, xhr) {
            //                 if(data == "Przekierowywanie... proszę czekać"){
            //                     window.location.href=" ../panel/index.php?error=examcompleted";
            //                 }
            //                 question_number++;
            //                 $(".question_content").empty();// success callback function
            //                 $('.question_content').append(data);
            //                 //updateScroll();
            //         }
            //     });

            $('body').on('click', '.answer_send', function(){
                $.ajax('../api/submit_answer.php?e=<?php echo $_GET["exam"] ?>&q=<?php echo get_question($conn, $_GET["exam"], NULL)["question_id"];?>&a=' + selected_answer + '&i=' + question_number,
                    {
                        success: function (data, status, xhr) {
                            if(data == "Przekierowywanie... proszę czekać"){
                                window.location.href=" ../panel/index.php?error=examcompleted";
                            }
                            question_number++;
                            $(".question_content").empty();// success callback function
                            $('.question_content').append(data);
                            //updateScroll();
                    }
                });
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