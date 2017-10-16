<?php
    session_start();
    if(empty($_SESSION['USER_ID'])) { // Recuerda usar corchetes.
        header('Location: login.php');
    }
    include ('php/functions.php');
?>
<!DOCTYPE html>
<html>
<title>Bird House</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/social1.css">
<link rel="stylesheet" href="css/social2.css">
<link rel='stylesheet' href=''>
<link rel="stylesheet" href="css/social3.css">
<style>
    html,body,h1,h2,h3,h4,h5 {font-family: "Open Sans", sans-serif}
</style>
<body class="w3-theme-d5">

<!-- Navbar -->
<div class="w3-top">
    <div class="w3-bar w3-theme-d2 w3-left-align w3-large">
        <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>
        <a href="social.php" class="w3-bar-item w3-button w3-padding-large w3-theme-d4"><i class="fa fa-home w3-margin-right"></i>Bird House</a>
        <a href="sightsMap.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Sights map"><i class="fa fa-map-o"></i></a>
        <a href="addSight.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Add sight"><i class="fa fa-plus-square"></i></a>
        <a href="php/logout.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-right" title="Logout"><i class="fa fa-lock"></i></a>
        <a href="settings.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-right" title="Account Settings"><i class="fa fa-user"></i></a>
        <?php
            if(isset($_SESSION['ADMIN_ID'])) { // Recuerda usar corchetes.
                echo ("<a href=\"adminTools.php\" class=\"w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-right\" 
                        title=\"Admin Tools\"><i class=\"fa fa-cube\"></i></a>");
            }
        ?>
    </div>
</div>

<!-- Page Container -->
<div class="w3-container w3-content" style="max-width:1400px;margin-top:80px">
    <!-- The Grid -->
    <div class="w3-row">
        <!-- Left Column -->
        <div class="w3-col m3">
            <!-- Profile -->
            <div class="w3-card-2 w3-round w3-white">
                <div class="w3-container">
                    <h4 class="w3-center">
                        <?php
                            echo ($_SESSION['USER_FIRSTNAME'].' '.$_SESSION['USER_LASTNAME']);
                        ?>
                    </h4>
                    <p class="w3-center"><img src="img/iconbh.png" class="w3-circle" style="height:106px;width:106px" alt="Avatar"></p>
                    <hr>
                    <p><i class="fa fa-pencil fa-fw w3-margin-right w3-text-theme"></i>
                        <?php
                        echo ($_SESSION['USER_PROFESSION']);
                        ?>
                    </p>
                    <p><i class="fa fa-address-book fa-fw w3-margin-right w3-text-theme"></i>
                        <?php
                        echo ($_SESSION['USER_EMAIL']);
                        ?>
                    </p>
                    <p><i class="fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme"></i>
                        <?php
                        echo ($_SESSION['USER_BIRTHDATE']);
                        ?>
                    </p>
                </div>
            </div>
            <br>

            <!-- Accordion -->




            <!-- Alert Box -->
            <div class="w3-container w3-display-container w3-round w3-theme-l4 w3-border w3-theme-border w3-margin-bottom w3-hide-small">
        <span onclick="this.parentElement.style.display='none'" class="w3-button w3-theme-l3 w3-display-topright">
          <i class="fa fa-remove"></i>
        </span>
                <p><strong>Hey!</strong></p>
                <p>We are happy to have you here :)</p><p>Bird House Staff</p>
            </div>

            <!-- End Left Column -->
        </div>

        <!-- Middle Column -->
        <div class="w3-col m7">

            <div class="w3-row-padding">
                <div class="w3-col m12">
                    <div class="w3-card-2 w3-round w3-white">
                        <div class="w3-container w3-padding">
                            <h6 class="w3-opacity w3-center">Welcome <?php

                                echo ($_SESSION['USER_NICKNAME']);

                                ?>!</h6>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                socialWall();
            ?>

            <!--
            <div class="w3-container w3-card-2 w3-white w3-round w3-margin w3-row-padding"><br>
                <img src="img/user+.png" alt="Avatar" class="w3-left w3-circle w3-margin-right" style="width:60px">
                <span class="w3-right w3-opacity">Date</span>
                <h5>Nickname</h5><br>
                <hr class="w3-clear">
                <p><i class="fa fa-location-arrow fa-fw w3-margin-right w3-text-theme"></i> Country, Province, Canton, District.</p>
                <div class="w3-row-padding" style="margin:0 -16px">
                    <div class="w3-image">
                        <img src="sight/animals-snake-bird-photo-background-694x417.jpg" style="width:100%" alt="Northern Lights" class="w3-margin-bottom">
                    </div>
                </div>
                <button type="button" class="w3-button w3-margin-bottom"><i class="fa fa-star"></i></button>
                <button type="button" class="w3-button w3-margin-bottom"><i class="fa fa-star"></i></button>
                <button type="button" class="w3-button w3-margin-bottom"><i class="fa fa-star"></i></button>
                <button type="button" class="w3-button w3-margin-bottom"><i class="fa fa-star"></i></button>
                <button type="button" class="w3-button w3-margin-bottom"><i class="fa fa-star"></i></button>
                <span class="w3-right w3-opacity">Calification: 0</span>
            </div>-->

            <!-- End Middle Column -->
        </div>

        <!-- Right Column -->
        <div class="w3-col m2">


            <div class="w3-hover-text-gray w3-card-2 w3-round w3-theme-light w3-padding-32 w3-center">
                <p><i class="fa fa-star w3-xlarge" style="color: #d42515">
                    </i><br>TOP 5</p>
            </div>
            <?php
                loadTop();
            ?>

            <!-- End Right Column -->
        </div>

        <!-- End Grid -->
    </div>

    <!-- End Page Container -->
</div>
<br>

<!-- Footer -->
<footer class="w3-container w3-theme-d3 w3-padding-16">
    <h5>Bird House. All rights reserved.</h5>
</footer>

<footer class="w3-container w3-theme-d5">
    <p>Powered by <a href="https://www.facebook.com/ivan.2131" target="_blank">Parcex</a></p>
</footer>

<script>
    // Accordion
    function myFunction(id) {
        var x = document.getElementById(id);
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
            x.previousElementSibling.className += " w3-theme-d1";
        } else {
            x.className = x.className.replace("w3-show", "");
            x.previousElementSibling.className =
                x.previousElementSibling.className.replace(" w3-theme-d1", "");
        }
    }

    // Used to toggle the menu on smaller screens when clicking on the menu button
    function openNav() {
        var x = document.getElementById("navDemo");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }
</script>

</body>
</html>