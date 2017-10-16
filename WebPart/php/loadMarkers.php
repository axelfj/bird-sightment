<?php
    include ("functions.php");
    $conn = startConnection();

    $province = $_REQUEST['PROVINCE'];
    $canton = $_REQUEST['CANTON'];
    $district = $_REQUEST['DISTRICT'];
    $class = $_REQUEST['CLASS'];
    $order = $_REQUEST['ORDER'];
    $suborder = $_REQUEST['SUBORDER'];
    $family = $_REQUEST['FAMILY'];
    $gender = $_REQUEST['GENDER'];
    $specie = $_REQUEST['SPECIE'];
    $size = $_REQUEST['SIZE'];
    $color = $_REQUEST['COLOR'];
/*
    $province = 0;
    $canton = 0;
    $district = 0;
    $class = 0;
    $order = 0;
    $suborder = 0;
    $family = 0;
    $gender = 0;
    $specie = 0;
    $size = 0;
    $color = "";
*/
    $Qplace = "";
    $QplaceValue = 0;
    if($province != 0){
        $Qplace = "AND PROVINCE.PROVINCE_ID = :placeValue ";
        $QplaceValue = $province;
    }
    if($canton != 0){
        $Qplace = "AND CANTON.CANTON_ID = :placeValue ";
        $QplaceValue = $canton;
    }
    if($district != 0){
        $Qplace = "AND DISTRICT.DISTRICT_ID = :placeValue ";
        $QplaceValue = $district;
    }

    $Qbird = "";
    $QbirdValue = 0;
    if($class != 0){
        $Qbird = "AND ANML_CLASS.CLASS_ID = :birdValue ";
        $QbirdValue = $class;
    }
    if($order != 0){
        $Qbird = "AND ANML_ORDER.ORDER_ID = :birdValue ";
        $QbirdValue = $order;
    }
    if($suborder != 0){
        $Qbird = "AND ANML_SUBORDER.SUBORDER_ID = :birdValue ";
        $QbirdValue = $suborder;
    }
    if($family != 0){
        $Qbird = "AND ANML_FAMILY.FAMILY_ID = :birdValue ";
        $QbirdValue = $family;
    }
    if($gender != 0){
        $Qbird = "AND ANML_GENDER.GENDER_ID = :birdValue ";
        $QbirdValue = $gender;
    }
    if($specie != 0){
        $Qbird = "AND ANML_SPECIE.SPECIE_ID = :birdValue ";
        $QbirdValue = $specie;
    }

    $Qsize = "";
    $QsizeValue = 0;
    if($size != 0){
        $Qsize = "AND ANML_SPECIE.SPECIE_SIZE = :sizeValue ";
        $QsizeValue = $size;
    }

    $Qcolor  = "";
    $QcolorValue  = "";
    if($color != ""){
        $Qcolor = "AND ANML_SPECIE.SPECIE_COLOR = :colorValue ";
        $QcolorValue = $color;
    }

    $sql = 'SELECT SIGHT.SIGHT_ID, SIGHT.LATITUDE, SIGHT.LONGITUDE, SIGHT.IMAGE_URL, SYSTEM_USER.USER_NICKNAME , ANML_SPECIE.SPECIE_NAME
        FROM SIGHT

        INNER JOIN SYSTEM_USER
          ON SYSTEM_USER.USER_ID = SIGHT.USER_ID

        INNER JOIN DISTRICT
          ON SIGHT.DISTRICT_ID = DISTRICT.DISTRICT_ID
        INNER JOIN CANTON
          ON DISTRICT.CANTON_ID = CANTON.CANTON_ID
        INNER JOIN PROVINCE
          ON CANTON.PROVINCE_ID = PROVINCE.PROVINCE_ID
        
        INNER JOIN ANML_SPECIE
          ON SIGHT.SPECIE_ID = ANML_SPECIE.SPECIE_ID
        INNER JOIN ANML_GENDER
          ON ANML_SPECIE.GENDER_ID = ANML_GENDER.GENDER_ID
        INNER JOIN ANML_FAMILY
          ON ANML_GENDER.FAMILY_ID = ANML_FAMILY.FAMILY_ID
        INNER JOIN ANML_SUBORDER
          ON ANML_FAMILY.SUBORDER_ID = ANML_SUBORDER.SUBORDER_ID
        INNER JOIN ANML_ORDER
          ON ANML_SUBORDER.ORDER_ID = ANML_ORDER.ORDER_ID
        INNER JOIN ANML_CLASS
          ON ANML_ORDER.CLASS_ID = ANML_CLASS.CLASS_ID WHERE 1=1'.$Qplace.$Qbird.$Qsize.$Qcolor;
    //echo ($sql);

    $query = oci_parse($conn, ''.$sql);
    if($QplaceValue != 0){
        OCIBindByName($query,":placeValue",$QplaceValue);
    }
    if($QbirdValue != 0){
        OCIBindByName($query,":birdValue",$QbirdValue);
    }
    if($QcolorValue != ""){
        OCIBindByName($query,":colorValue",$QcolorValue);
    }
    if($QsizeValue != ""){
        OCIBindByName($query,":sizeValue",$QsizeValue);
    }
    oci_execute($query);

    $i = 1;
    $output = array();
    while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {

        //$output [] = $row['SIGHT_ID'].','.$row['LATITUDE'].','.$row['LONGITUDE'].','.
          //  $row['IMAGE_URL'].''.$row['USER_NICKNAME'].','.$row['SPECIE_NAME'];

        if ($i != 1){
            echo('#');
        }
        echo ($row['SIGHT_ID'].';'.$row['LATITUDE'].';'.$row['LONGITUDE'].';'.
            $row['IMAGE_URL'].';'.$row['USER_NICKNAME'].';'.$row['SPECIE_NAME']);
        $i++;
    }

    endConnection($conn);
?>