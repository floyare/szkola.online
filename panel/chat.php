<?php 
session_start(); 
if(!isset($_SESSION["uid"])){header("location: ../login/");} 
if(!isset($_GET["user"])){header("location: index.php");}

include_once '../includes/dbh.inc.php';
include_once '../includes/functions.inc.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>szkola.online - Chat z <?php echo get_fullname($conn, $_GET["user"]); ?></title>

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
                }else if($_GET["error"] == "msgtoolong"){
                    echo "<script>show_info_box('Wiadomość może mieć maksymalnie 200 znaków!', true);</script>";
                }
            }
        ?>
        <?php include_once '../header_side.php';?>
            <div class="panel_background">
                <button class="btn btn_float" onclick="window.location.href='index.php'"><i class='bx bx-arrow-back' ></i> Wróć</button>
                <div class="chat_info">
                    <p class="info_small">Użytkownik:</p>
                    <p class="info_name"><img src="<?php echo get_avatar($conn, $_GET["user"]); ?>"><?php echo get_fullname($conn, $_GET["user"]);  ?></p>
                </div>
                <div class="submit_container">
                    <div class="chat_info">
                        <p class="info_small">Wiadomość:</p>
                    </div>
                    <input type="text" class="message">
                    <button type="submit" class="btn btn_small message_send">Wyślij</button>
                </div>
                <div class="messages_container">

                </div>
            </div>
        <script>
            var amount = 10;
            setInterval(function(){
                get_messages();
            }, 1000);

            function get_messages(){
                $.ajax('../api/get_messages.php?user=<?php echo $_GET["user"] ?>&am=' + amount,
                    {
                        success: function (data, status, xhr) {
                            $(".messages_container").empty();
                            $('.messages_container').append(data);
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
                    $.ajax('../api/send_message.php?text=' + $(".message").val() + "&rec=" + "<?php echo $_GET["user"]; ?>",   // request url
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
                                }
                                
                                get_messages();

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