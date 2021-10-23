<?php session_start(); if(!isset($_SESSION["uid"])){header("location: ../login/");} ?>
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
                }elseif($_GET["error"] = "noschool"){
                    echo "<script>show_info_box('Brak uprawnień do zarządzania szkołą!', true);</script>";
                }
            }
        ?>
        <div class="modal_container" id="modal_school">
            <div class="modal">
                <div class="close"><i class='bx bx-x' ></i></div>
                <h1 class="modal_title">Dodaj szkołę</h1>
                <div class="input_container">
                    <div class="input_box">
                        <p>Nazwa szkoły</p>
                        <input type="text" id="Sname">
                    </div>
                    <div class="input_box">
                        <p>Adres szkoły</p>
                        <input type="text" id="Saddress">
                    </div>
                    <div class="input_box">
                        <p>Miasto</p>
                        <input type="text" id="Scity">
                    </div>
                    <div class="input_box">
                        <p>Kod pocztowy</p>
                        <input type="text" id="Szip">
                    </div>
                    <div class="modal_accept_container">
                        <input class="hidden-xs-up" id="cbx" type="checkbox">
                        <label class="cbx" for="cbx"></label>
                        <label class="lbl" for="cbx">Potwierdzam poprawność danych i akceptuje regulamin dotyczący przechowywania danych o uczniach, nauczycielach i samej szkole.</label>
                    </div>
                    <button class="btn btn_primary" id="create_school">Utwórz</button>
                </div>
            </div>
        </div>

        <div class="modal_container" id="modal_password_change">
            <div class="modal">
                <div class="close"><i class='bx bx-x' ></i></div>
                <h1 class="modal_title">Zmień hasło</h1>
                <div class="input_container no-wrap">
                    <div class="input_box">
                        <p>Stare hasło</p>
                        <input type="password" id="Soldpass">
                    </div>
                    <div class="input_box">
                        <p>Nowe hasło</p>
                        <input type="password" id="Snewpass">
                    </div>
                    <div class="input_box">
                        <p>Powtórz nowe hasło</p>
                        <input type="password" id="Srepeatnewpass">
                    </div>
                    <button class="btn btn_primary" id="change_pass">Zmień hasło</button>
                </div>
            </div>
        </div>


        <?php include_once '../header.php';?>
        <div class="panel_container">
            <div class="panel_box">
                <h1 class="box_header">Ustawienia</h1>
                <ul class="tab_container">
                    <li class="tab selected" tab-id="1"><i class='bx bxs-user-circle' ></i> Użytkownik</li>
                    <?php if($_SESSION["type"] == 2){ echo '<li class="tab" tab-id="2"><i class="bx bxs-school" ></i> Szkoła</li>';}  ?>
                </ul>
                <div class="tabpage visible" id="tab1">
                    <p class="subheader">Dane osobowe:</p>
                    <ul class="details_container">
                        <li class="detail"><i class='bx bxs-user' ></i> Imię i nazwisko: <span name="fullname"><?php echo $_SESSION["fullname"]; ?></span></li>
                        <li class="detail"><i class='bx bx-mail-send' ></i> E-mail: <span name="mail"><?php echo $_SESSION["mail"]; ?></span></li>
                        <li class="detail"><i class='bx bxs-key' ></i> Hasło: <span name="pwd"><?php echo $_SESSION["pwd"]; ?></span> <button class="btn btn_small" id="btn_pass_change">Zmień hasło</button></li>
                        <li class="detail"><i class='bx bxs-user-badge' ></i> Typ konta: <span name="type"><?php switch($_SESSION["type"]){case 0: echo "Uczeń"; break; case 1: echo "Nauczyciel"; break; case 2: echo "Dyrektor"; break;} ?></span></li>
                    </ul>
                </div>
                <?php if($_SESSION["type"] == 2){ echo '<div class="tabpage" id="tab2"><div class="school_container"></div></div>'; }?>
            </div>
        </div>
        <script src="../script.js"></script>
        <script>
            $.ajax('../api/get_school.php?uuid=<?php echo $_SESSION["uuidv4"]; ?>',
                {
                    success: function (data, status, xhr) {
                        $('.school_container').append(data);
                }
            });

            $(".tab").click(function(){
                $(".tab").removeClass("selected");
                $(this).toggleClass("selected");
                $(".tabpage").removeClass("visible");
                $("#tab" + $(this).attr("tab-id")).addClass("visible");
            });

            $("#btn_pass_change").click(function(){
                show_modal('modal_password_change');
            });

            $("#change_pass").click(function(){
                if($("#Snewpass").val() == $("#Srepeatnewpass").val()){
                    $.ajax('../api/change_pwd.php?old=' + $("#Soldpass").val() + "&new=" + $("#Snewpass").val(),
                            {
                                success: function (data, status, xhr) {
                                    if(data == "1"){
                                        $(".modal_container").css('display', 'none');
                                        show_info_box("Pomyślnie zmieniono!", false);
                                    }else{
                                        show_info_box(data, true);
                                    }
                            }
                        });
                }else{
                    show_info_box("Hasła się nie zgadzają", true);
                }
            });

            $("#create_school").click(function(){
                if($("#Sname").val() == "" || $("#Saddress").val() == "" || $("#Scity").val() == ""|| $("#Szip").val() == ""){
                    show_info_box("Uzupełnij wszystkie pola!", true);
                }else{
                    if($("#cbx").prop("checked") == true){
                        $.ajax('../api/create_school.php?name=' + $("#Sname").val() + "&address=" + $("#Saddress").val() + "&city=" + $("#Scity").val() + "&zip=" + $("#Szip").val(),
                            {
                                success: function (data, status, xhr) {
                                    $(".modal_container").css('display', 'none');
                                    show_info_box("Pomyślnie utworzono!", false);
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