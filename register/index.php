<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>szkola.online - Zarejestruj się</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="../script.js"></script>
</head>
    <body class="use_background">
        <?php 
            include_once '../header.php';
            if(isset($_GET["error"])){
                if($_GET["error"] == "emptyinput"){
                    echo "<script>show_info_box('Uzupełnij wszystkie pola!', true);</script>";
                }else if($_GET["error"] == "invaldname"){
                    echo "<script>show_info_box('Nieprawidłowe imię lub nazwisko!', true);</script>";
                }
                else if($_GET["error"] == "invalidemail"){
                    echo "<script>show_info_box('Nieprawidłowy adres e-mail!', true);</script>";
                }
                else if($_GET["error"] == "pwdmatch"){
                    echo "<script>show_info_box('Hasła się nie zgadzają!', true);</script>";
                }
                else if($_GET["error"] == "mailtaken"){
                    echo "<script>show_info_box('Adres E-mail jest już w użyciu!', true);</script>";
                }                
                else if($_GET["error"] == "none"){
                    echo "<script>show_info_box('Pomyślnie zarejestrowano', false);</script>";
                }
            }
        ?>
        <div class="login_container">
            <form action="../includes/signup.inc.php" method="post">
                <div class="login_form">
                    <h1 class="box_header">Rejestracja</h1>

                    <div class="login_input">
                        <input type="name" placeholder="Imię" name="name">
                        <p class="login_input_placeholder">Imię</p>
                    </div>

                    <div class="login_input">
                        <input type="name" placeholder="Nazwisko" name="surname">
                        <p class="login_input_placeholder">Nazwisko</p>
                    </div>

                    <div class="login_input">
                        <input type="email" placeholder="E-mail" name="mail">
                        <p class="login_input_placeholder">E-mail</p>
                    </div>

                    <div class="login_input">
                        <input type="password" placeholder="Hasło" name="pwd">
                        <p class="login_input_placeholder">Hasło</p>
                    </div>

                    <div class="login_input">
                        <input type="password" placeholder="Powtórz Hasło" name="pwdr">
                        <p class="login_input_placeholder">Powtórz Hasło</p>
                    </div>


                    <center>
                        <div class="login_text">
                            <p class="login_info">Masz już konto? <a href="../login/">Zaloguj się!</a></p>
                        </div>
                    </center>
                    <button class="btn btn_primary btn_login" type="submit" name="submit">Zarejestruj!</button>
                </div>
            </form>
        </div>
    </body>
</html>