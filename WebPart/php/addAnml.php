<?php
    session_start();
    if(empty($_SESSION['ADMIN_ID'])) { // Recuerda usar corchetes.
        header('Location: social.php');
    }
    include ("functions.php");

    if($_POST['type'] == 1){
        createClass();
    }
    if($_POST['type'] == 2){
        createOrder();
    }
    if($_POST['type'] == 3){
        createSuborder();
    }
    if($_POST['type'] == 4){
        createFamily();
    }
    if($_POST['type'] == 5){
        createGender();
    }
    if($_POST['type'] == 6){
        createSpecie();
    }
?>