<?php
    session_start();
    if(empty($_SESSION['USER_ID'])) { // Recuerda usar corchetes.
        header('Location: login.php');
    }
    session_destroy();
    header('Location: \DBP1/index.php');
?>