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
            if(isset($_GET["error"])){
                if($_GET["error"] == "stmtfail"){
                    echo "<script>show_info_box('Wystąpił nieznany błąd! Spróbuj ponownie później!', true);</script>";
                }else if($_GET["error"] == "msgtoolong"){
                    echo "<script>show_info_box('Wiadomość może mieć maksymalnie 200 znaków!', true);</script>";
                }
            }
        ?>
        <?php include_once '../header_side.php';?>
            <div class="panel_background">
            <button class="btn btn_float" onclick="window.location.href='index.php'"><i class='bx bx-arrow-back' ></i> Wróć</button>
                <ul>
                    <li>
                        <div class="chat_info">
                            <p class="info_small">Grupa:</p>
                            <p class="info_name"><img src="<?php echo 'https://eu.ui-avatars.com/api/?background=' . rgbcode(get_group($conn, $_GET["group"])["group_name"]) .   '&color=fff&name=' . get_group($conn, $_GET["group"])["group_name"]; ?>"><?php echo get_group($conn, $_GET["group"])["group_name"];  ?></p>
                        </div>
                        <div class="submit_container">
                            <input type="text" class="message">
                            <button type="submit" class="btn btn_small message_send">Wyślij</button>
                        </div>
                        <div class="messages_container">
                            <!-- <div class="message"><p>Siema</p><p class="date">10.09.2021 - 12:20</p></div>
                            <div class="message me"><p>no hej</p><p class="date">10.09.2021 - 12:20</p></div> -->
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