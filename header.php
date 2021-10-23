<?php include_once 'includes/dbh.inc.php'; include_once 'includes/functions.inc.php'; ?>
<div class="nav">
    <div class="nav_items">
        <ul>
            <li class="logo"><p><i class='bx bxs-school'></i> szkola.online</p></li>
        </ul>
        <ul class="nav_toggle">
            <li><i class='bx bx-menu'></i></li>
        </ul>
        <ul class="items">
            <li  onclick='window.location.href=`../`'><i class='bx bxs-home'></i> Strona główna</li>
            <?php
                if(isset($_SESSION["uid"])){
                    echo "<li onclick='window.location.href=`/panel/`'><i class='bx bxs-dashboard' ></i> Panel</li>";
                    echo "<li  onclick='window.location.href=`/settings/`'><i class='bx bx-cog'></i> Ustawienia</li>";
                    echo "<li  onclick='window.location.href=`/includes/logout.inc.php`'><i class='bx bx-log-out-circle'></i> Wyloguj</li>";
                }else{
                    echo "<li  onclick='window.location.href=`../login/`'><i class='bx bx-log-in-circle' ></i> Zaloguj</li>";
                }
            ?>
        </ul>
    </div>
</div>