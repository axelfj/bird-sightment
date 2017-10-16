<?php
    session_start();
    if(isset($_SESSION['USER_ID'])) { // Recuerda usar corchetes.
        header('Location: \DBP1/social.php');
    }
    include ('functions.php');
    login();
?>