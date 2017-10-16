<?php
session_start();
if(empty($_SESSION['USER_ID'])) { // Recuerda usar corchetes.
    header('Location: login.php');
}
include ("php/functions.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/social1.css">
    <link rel="stylesheet" href="css/social2.css">
    <link rel='stylesheet' href=''>
    <link rel="stylesheet" href="css/social3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bird House</title>
    <meta name="description" content="A photography-inspired website layout with an expanding stack slider and a background image tilt effect" />
    <meta name="keywords" content="photography, template, layout, effect, expand, image stack, animation, flickity, tilt" />
    <meta name="author" content="Codrops" />
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="css/flickity.css" />
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <script src="js/modernizr.custom.js"></script>

    <style>
        /* NOTE: The styles were added inline because Prefixfree needs access to your styles and they must be inlined if they are on local disk! */
        @import url(https://fonts.googleapis.com/css?family=Open+Sans);
        .btn { display: inline-block; *display: inline; *zoom: 1; padding: 4px 10px 4px; margin-bottom: 0; font-size: 13px; line-height: 18px; color: #333333; text-align: center;text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75); vertical-align: middle; background-color: #f5f5f5; background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6); background-image: -ms-linear-gradient(top, #ffffff, #e6e6e6); background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6)); background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6); background-image: -o-linear-gradient(top, #ffffff, #e6e6e6); background-image: linear-gradient(top, #ffffff, #e6e6e6); background-repeat: repeat-x; filter: progid:dximagetransform.microsoft.gradient(startColorstr=#ffffff, endColorstr=#e6e6e6, GradientType=0); border-color: #e6e6e6 #e6e6e6 #e6e6e6; border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25); border: 1px solid #e6e6e6; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05); -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05); box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05); cursor: pointer; *margin-left: .3em; }
        .btn:hover, .btn:active, .btn.active, .btn.disabled, .btn[disabled] { background-color: #e6e6e6; }
        .btn-large { padding: 9px 14px; font-size: 15px; line-height: normal; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; }
        .btn:hover { color: #333333; text-decoration: none; background-color: #e6e6e6; background-position: 0 -15px; -webkit-transition: background-position 0.1s linear; -moz-transition: background-position 0.1s linear; -ms-transition: background-position 0.1s linear; -o-transition: background-position 0.1s linear; transition: background-position 0.1s linear; }
        .btn-primary, .btn-primary:hover { text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); color: #ffffff; }
        .btn-primary.active { color: rgba(255, 255, 255, 0.75); }
        .btn-primary { background-color: #4a77d4; background-image: -moz-linear-gradient(top, #6eb6de, #4a77d4); background-image: -ms-linear-gradient(top, #6eb6de, #4a77d4); background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#6eb6de), to(#4a77d4)); background-image: -webkit-linear-gradient(top, #6eb6de, #4a77d4); background-image: -o-linear-gradient(top, #6eb6de, #4a77d4); background-image: linear-gradient(top, #6eb6de, #4a77d4); background-repeat: repeat-x; filter: progid:dximagetransform.microsoft.gradient(startColorstr=#6eb6de, endColorstr=#4a77d4, GradientType=0);  border: 1px solid #3762bc; text-shadow: 1px 1px 1px rgba(0,0,0,0.4); box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.5); }
        .btn-primary:hover, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] { filter: none; background-color: #4a77d4; }
        .btn-block { width: 100%; display:block; }

        * { -webkit-box-sizing:border-box; -moz-box-sizing:border-box; -ms-box-sizing:border-box; -o-box-sizing:border-box; box-sizing:border-box; }

        html { width: 100%; height:100%; overflow:hidden; }

        body {
            width: 100%;
            height:100%;
            font-family: 'Open Sans', sans-serif;
        }
        .login {
            position: absolute;
            top: 36%;
            left: 12%;
            margin: -150px 0 0 -150px;
            width:300px;
            height:250px;
        }
        .login h1 { color: #fff; text-shadow: 0 0 10px rgba(0,0,0,0.3); letter-spacing:1px; text-align:center; }

        .login2 {
            position: absolute;
            top: 106%;
            left: 12%;
            margin: -150px 0 0 -150px;
            width:300px;
            height:250px;
        }
        .login2 h1 { color: #fff; text-shadow: 0 0 10px rgba(0,0,0,0.3); letter-spacing:1px; text-align:center; }


        input {
            width: 100%;
            margin-bottom: 10px;
            background: rgba(0,0,0,0.3);
            border: none;
            outline: none;
            padding: 10px;
            font-size: 13px;
            color: #fff;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.3);
            border: 1px solid rgba(0,0,0,0.3);
            border-radius: 4px;
            box-shadow: inset 0 -5px 45px rgba(100,100,100,0.2), 0 1px 1px rgba(255,255,255,0.2);
            -webkit-transition: box-shadow .5s ease;
            -moz-transition: box-shadow .5s ease;
            -o-transition: box-shadow .5s ease;
            -ms-transition: box-shadow .5s ease;
            transition: box-shadow .5s ease;
        }
        input:focus { box-shadow: inset 0 -5px 45px rgba(100,100,100,0.4), 0 1px 1px rgba(255,255,255,0.2); }
        <style>
         html,body,h1,h2,h3,h4,h5 {font-family: "Open Sans", sans-serif}
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            position: absolute;
            height: 93%;
            width: 100%;
            left: 0%;
            bottom: 0%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body class=" w3-theme-d5" >
<div class="w3-round" style="" id="map"></div>
<script>

    function initMap() {
        var myLatLng = {lat: 9.9379722438, lng: -84.0753030896};

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 18,
            center: myLatLng
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Hello World!',
            draggable:true
        });

        var lat;

        var lon;

        google.maps.event.addListener(marker, 'dragend', function (evt) {
            lon = evt.latLng.lng().toFixed(10);
            lat = evt.latLng.lat().toFixed(10);
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lon;
        });

        google.maps.event.addListener(marker, 'dragstart', function (evt) {
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lon;
        });

    }

    var provincia = 0;
    var canton = 0;
    var distrito = 0;
    var clase = 0;
    var orden = 0;
    var suborden = 0;
    var familia = 0;
    var genero = 0;
    var especie = 0;
    var tamano = 0;
    var color = "";

    function addOpt(oCntrl, iPos, sTxt, sVal){
        var selOpcion=new Option(sTxt, sVal);
        eval(oCntrl.options[iPos]=selOpcion);
    }

    function cambiaCanton(obj,oCntrl){
        var val = obj.value;
        provincia = parseInt(val);
        while (oCntrl.length) oCntrl.remove(0);
        switch (provincia){

            case 0:
                addOpt(oCntrl,  0, "Filtrar por  canton", "0");
                break;
        <?php
            addCanton();
            ?>
        }
    }

    function cambiaDistrito(obj,oCntrl){
        var val = obj.value;
        canton = parseInt(val);
        while (oCntrl.length) oCntrl.remove(0);
        switch (canton){
            case 0:
                addOpt(oCntrl, 0, "Filtrar por  distrito", "0");
                break;

        <?php
            addDistrict();
            ?>
        }
    }

    function setDistrito(obj) {
        var val = obj.value;
        distrito = parseInt(val);
        if(distrito == 0){
            document.getElementById("district").value = null;
        } else {
            document.getElementById("district").value = distrito;
        }
    }

    function cambiaOrden(obj,oCntrl){
        var val = obj.value;
        clase = parseInt(val);
        while (oCntrl.length) oCntrl.remove(0);
        switch (clase){

            case 0:
                addOpt(oCntrl, 0, "Filtrar por  orden", "0");
                break;

        <?php
            addOrder();
            ?>
        }
    }

    function cambiaSuborden(obj,oCntrl){
        var val = obj.value;
        orden = parseInt(val);
        while (oCntrl.length) oCntrl.remove(0);
        switch (parseInt(val)){

            case 0:
                addOpt(oCntrl, 0, "Filtrar por  suborden", "0");
                break;

        <?php
            addSuborder();
            ?>
        }
    }

    function cambiaFamilia(obj,oCntrl){
        var val = obj.value;
        suborden = parseInt(val);
        while (oCntrl.length) oCntrl.remove(0);
        switch (parseInt(val)){

            case 0:
                addOpt(oCntrl, 0, "Filtrar por  familia", "0");
                break;

        <?php
            addFamily();
            ?>
        }
    }

    function cambiaGenero(obj,oCntrl){
        var val = obj.value;
        familia = parseInt(val);
        while (oCntrl.length) oCntrl.remove(0);
        switch (parseInt(val)){

            case 0:
                addOpt(oCntrl, 0, "Filtrar por  genero", "0");
                break;

        <?php
            addGender();
            ?>
        }
    }

    function cambiaEspecie(obj,oCntrl){
        var val = obj.value;
        genero = parseInt(val);
        while (oCntrl.length) oCntrl.remove(0);
        switch (parseInt(val)){

            case 0:
                addOpt(oCntrl, 0, "Filtrar por  distrito", "0");
                break;

        <?php
            addSpecie();
            ?>
        }
    }

    function setEspecie(obj) {
        var val = obj.value;
        especie = parseInt(val);
        if(especie == 0){
            document.getElementById("specie").value = null;
        } else {
            document.getElementById("specie").value = especie;
        }
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK67GQM_P0GgmSjBFUApOI2T74Md5s_bc&callback=initMap">
</script>
<div class="w3-top">
    <div class="w3-bar w3-theme-d2 w3-left-align w3-large">
        <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>
        <a href="social.php" class="w3-bar-item w3-button w3-padding-large w3-theme-d4"><i class="fa fa-home w3-margin-right"></i>Bird House</a>
        <a href="sightsMap.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Sights map"><i class="fa fa-map-o"></i></a>
        <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Add sight"><i class="fa fa-plus-square"></i></a>
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
<section>
</section>

<div class="login">
    <form name="frm">
        <select class=" w3-theme-dark btn-block btn-large w3-hover-dark-gray" id="province" name="province" onchange="cambiaCanton(this,document.frm.canton)">
            <option value="0">Seleccionar provincia</option>
            <?php
            addProvinces();
            ?>
        </select>
        <h6></h6>
        <select class="w3-theme-d4 btn-block btn-large" name="canton" onchange="cambiaDistrito(this, document.frm.district)">
            <option value="0">Seleccionar canton</option>
        </select>
        <h6></h6>
        <select class="w3-theme-d4 btn-block btn-large" name="district" onchange="setDistrito(this)">
            <option value="0">Seleccionar distrito</option>
        </select>
        <h6></h6>
        <select class="w3-theme-d3 btn-block btn-large" name="class" onchange="cambiaOrden(this, document.frm.order)">
            <option value="0">Seleccionar clase</option>
            <?php
            addClass();
            ?>
        </select>
        <h6></h6>
        <select class="w3-theme-d3 btn-block btn-large" name="order" onchange="cambiaSuborden(this, document.frm.suborder)">
            <option value="0">Seleccionar orden</option>
        </select>
        <h6></h6>
        <select class="w3-theme-d2 btn-block btn-large" name="suborder" onchange="cambiaFamilia(this, document.frm.family)">
            <option value="0">Seleccionar suborden</option>
        </select>
        <h6></h6>
        <select class="w3-theme-d2 btn-block btn-large" name="family" onchange="cambiaGenero(this, document.frm.gender)">
            <option value="0">Seleccionar familia</option>
        </select>
        <h6></h6>
        <select class="w3-theme-d1 btn-block btn-large" name="gender" onchange="cambiaEspecie(this, document.frm.specie)">
            <option value="0">Seleccionar genero</option>
        </select>
        <h6></h6>
        <select class="w3-theme-d1 btn-block btn-large" name="specie" onchange="setEspecie(this)">
            <option value="0">Seleccionar especie</option>
        </select>
        <h6></h6>
    </form>
</div>
<div class="login2">
    <form name="frm2"  action="php/sight.php" method="post" enctype="multipart/form-data">
        <input hidden id="latitude" type="number" step="0.0000000001" name="lat" placeholder="Latitude" required="required"/>
        <input hidden id="longitude" type="number" step="0.0000000001" name="lon" placeholder="Longitude" required="required"/>
        <input hidden id="district" type="number" name="district" placeholder="District" required="required"/>
        <input hidden id="specie" type="number" name="specie" placeholder="Especie" required="required"/>
        <input type="file" name="photo" placeholder="Photo" required="required" />
        <button type="submit" class="btn btn-primary btn-block btn-large">Add sight</button>
    </form>
</div>
<!-- End Page Container -->
<br>

</body>
</html>