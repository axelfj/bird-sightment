<?php
    session_start();
    if(empty($_SESSION['USER_ID'])) { // Recuerda usar corchetes.
        header('Location: \DBP1/social.php');
    }
    include ('functions.php');
    addSight();
    header('Location: \DBP1/social.php');
?>