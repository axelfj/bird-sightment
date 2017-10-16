<?php
    session_start();
    if(empty($_SESSION['USER_ID'])) { // Recuerda usar corchetes.
        header('Location: login.php');
    }
    include ("functions.php");
    changePass($_POST['currpass'], $new = $_POST['newpass']);
?>