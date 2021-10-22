<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>szkola.online - Zaloguj się</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
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
        <?php include_once '../header.php';?>
        <div class="login_container">
            <form action="../includes/login.inc.php" method="post">
                <div class="login_form">
                    <h1 class="box_header">Zaloguj</h1>
                    <div class="login_input">
                        <input type="email" placeholder="E-mail" name="mail">
                        <p class="login_input_placeholder">E-mail</p>
                    </div>
                    <div class="login_input">
                        <input type="password" placeholder="Hasło" name="pwd">
                        <p class="login_input_placeholder">Hasło</p>
                    </div>
                    <center>
                        <div class="login_text">
                            <p class="login_info">Nie masz jeszcze konta? <a href="../register/">Zarejestruj się!</a></p>
                        </div>
                    </center>
                    <button class="btn btn_primary btn_login" name="submit">Zaloguj</button>
                </div>
            </form>
        </div>

        <?php 

        if(isset($_GET["error"])){

        }
        
        ?>
        <script src="../script.js"></script>
    </body>
</html>