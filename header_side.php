<div class="nav side">
        <ul>
            <li class="logo"><p><i class='bx bxs-school'></i> <span>szkola.online</span></p></li>
            <li class="nav_seperator"></li>
            <li><a href="../index.php"><i class='bx bxs-home'></i> <span>Strona Główna</span></a></li>
            <li><a href="../settings/"><i class='bx bx-cog'></i> <span>Ustawienia</span></a></li>
            <li><a href="../includes/logout.inc.php"><i class='bx bx-log-out-circle'></i> <span>Wyloguj</span></a></li>
            <li class="nav_seperator"></li>
            <li class="user"><img src="<?php echo get_avatar($conn, $_SESSION["uuidv4"]); ?>"><p><span><?php echo get_fullname($conn, $_SESSION["uuidv4"]); ?></span></p></li>
        </ul>
</div>