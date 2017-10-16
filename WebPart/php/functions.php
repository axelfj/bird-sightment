<?php

    function startConnection(){
        $conn = oci_connect('p_user', 'surfo2131', 'localhost/orcl');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['connection failed'], ENT_QUOTES), E_USER_ERROR);
        }
        return $conn;
    };

    function endConnection($input){
        OCILogoff($input);
    };

    function commit($input){
        OCICommit($input);
    };

    function registerUser(){
        $conn = startConnection();

        $var1 = $_POST["name"];
        $var2 = $_POST["lastname"];
        $var3 = $_POST["nick"];
        $var4 = $_POST["mail"];
        $var5 = $_POST["pass"];
        $var6 = $_POST["birth"];
        $var7 = $_POST["profession"];
        $format = 'YYYY-MM-DD';

        $salt= 25565;
        $passHash = crypt($var5,'$2y$8$'.$salt.'$');

        $query = OCIParse($conn, '
        CALL ADDSYSTEMUSER(:userName,:userLastname,:userNick,:userMail,:userPassword, :userProfession,TO_DATE(:userBirth,:birthFormat))');



        OCIBindByName($query,":userName",$var1);
        OCIBindByName($query,":userLastname",$var2);
        OCIBindByName($query,":userNick",$var3);
        OCIBindByName($query,":userPassword",$passHash);
        OCIBindByName($query,":userMail",$var4);
        OCIBindByName($query,":userBirth",$var6);
        OCIBindByName($query,":userProfession",$var7);
        OCIBindByName($query,":birthFormat",$format);

        oci_execute($query);

        commit($conn);
        endConnection($conn);
        return $var3;
    };

    function login(){
        $conn = startConnection();

        $var1 = $_POST["email"];
        $var2 = $_POST["password"];

        $query = oci_parse($conn, 'SELECT * FROM SYSTEM_USER WHERE USER_EMAIL = :em');
        OCIBindByName($query,":em",$var1);
        oci_execute($query);

        if($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $salt = 25565;
            if (crypt($var2,'$2y$8$'.$salt.'$') == $row['USER_PASSWORD']) {
                $_SESSION['USER_ID'] = $row['USER_ID'];
                $_SESSION['USER_FIRSTNAME'] = $row['USER_FIRSTNAME'];
                $_SESSION['USER_LASTNAME'] = $row['USER_LASTNAME'];
                $_SESSION['USER_NICKNAME'] = $row['USER_NICKNAME'];
                $_SESSION['USER_EMAIL'] = $row['USER_EMAIL'];
                $_SESSION['USER_BIRTHDATE'] = $row['USER_BIRTHDATE'];
                $_SESSION['USER_PROFESSION'] = $row['USER_PROFESSION'];

                $query2 = oci_parse($conn, 'SELECT * FROM ADMIN_USER WHERE USER_ID = :em');
                OCIBindByName($query2, ":em", $row['USER_ID']);
                oci_execute($query2);

                if ($row2 = oci_fetch_array($query2, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    $_SESSION['ADMIN_ID'] = $row['USER_ID'];
                }

                endConnection($conn);
                header('Location: \DBP1/social.php');
            } else {
                header('Location: \DBP1/login.php');
                endConnection($conn);
            }
        } else {
            header('Location: \DBP1/login.php');
            endConnection($conn);
        }
    };

    function addSight(){
        $conn = startConnection();

        $lat = $_POST["lat"];
        $lon = $_POST["lon"];
        $district = $_POST["district"];
        $specie = $_POST["specie"];
        $seqval = 0;

        //Obtener valor de la secuencia
        $query = oci_parse($conn, 'SELECT SEQ_SIGHT.NEXTVAL VAL FROM DUAL');
        oci_execute($query);
        if($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)){
            $seqval = $row['VAL'];
        }

        //Guardar imagen en file system
        $imagen = $_FILES["photo"];
        $nombre = $_FILES["photo"]["name"];
        $auxiliar = explode(".", $imagen["name"]);
        $sizex = sizeof($auxiliar, 0);
        $extension = $auxiliar[$sizex-1];
        $nombre = $seqval.".".$extension;
        move_uploaded_file($imagen["tmp_name"], "sight_Images/".$nombre);

        $imagePatch = "php/sight_Images/".$nombre;

        //Inserta imagen en BD
        $query = oci_parse($conn, 'INSERT INTO SIGHT(SIGHT_ID, USER_ID, SPECIE_ID, DISTRICT_ID, LONGITUDE, LATITUDE, IMAGE_URL) 
                                            VALUES (:id, :usuario, :especie, :distrito, :lon, :lat, :img)');
        OCIBindByName($query,":id",$seqval);
        OCIBindByName($query,":usuario",$_SESSION['USER_ID']);
        OCIBindByName($query,":especie",$specie);
        OCIBindByName($query,":distrito",$district);
        OCIBindByName($query,":lon",$lon);
        OCIBindByName($query,":lat",$lat);
        OCIBindByName($query,":img",$imagePatch);
        oci_execute($query);

        commit($conn);

        endConnection($conn);

        echo ($imagePatch.' ');
        echo ($lat.' '.$lon.' '.$district.' '.$specie.' '.$_SESSION['USER_ID'].' '.$seqval);

    };

    function socialWall(){
        $conn = startConnection();
        $query = oci_parse($conn, 'SELECT ANML_SPECIE.SPECIE_NAME, SIGHT.SIGHT_ID,SIGHT.DATE_CREATION, SIGHT.IMAGE_URL, SYSTEM_USER.USER_NICKNAME, DISTRICT.DISTRICT_NAME, CANTON.CANTON_NAME, PROVINCE.PROVINCE_NAME, COUNTRY.COUNTRY_NAME
    FROM SIGHT INNER JOIN SYSTEM_USER
    ON SYSTEM_USER.USER_ID = SIGHT.USER_ID
    INNER JOIN ANML_SPECIE
    ON SIGHT.SPECIE_ID = ANML_SPECIE.SPECIE_ID
    INNER JOIN DISTRICT
    ON DISTRICT.DISTRICT_ID = SIGHT.DISTRICT_ID
    INNER JOIN CANTON
    ON DISTRICT.CANTON_ID = CANTON.CANTON_ID
    INNER JOIN PROVINCE
    ON CANTON.PROVINCE_ID = PROVINCE.PROVINCE_ID
    INNER JOIN COUNTRY
    ON PROVINCE.COUNTRY_ID = COUNTRY.COUNTRY_ID
    ORDER BY SIGHT.SIGHT_ID DESC');
        oci_execute($query);

        while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo ("
            <div id=\"".$row['SIGHT_ID']."\" class=\"w3-container w3-card-2 w3-white w3-round w3-margin w3-row-padding\"><br>
                <img src=\"img/iconbh.png\" alt=\"Avatar\" class=\"w3-left w3-circle w3-margin-right\" style=\"width:60px\">
                <span class=\"w3-right w3-opacity\">".$row['DATE_CREATION']."</span>
                <h5>".$row['USER_NICKNAME']."</h5><br>
                <hr class=\"w3-clear\">
                <p><i class=\"fa fa-camera fa-fw w3-margin-right w3-text-theme\"></i>".$row['SPECIE_NAME']."</p>
                <p><i class=\"fa fa-location-arrow fa-fw w3-margin-right w3-text-theme\"></i> ".$row['COUNTRY_NAME'].",
                 ".$row['PROVINCE_NAME'].", ".$row['CANTON_NAME'].", ".$row['DISTRICT_NAME'].".</p>
                <div class=\"w3-row-padding\" style=\"margin:0 -16px\">
                    <div class=\"w3-image\">
                        <img src=\"".$row['IMAGE_URL']."\" style=\"width:100%\" alt=\"Northern Lights\" 
                        class=\"w3-margin-bottom\">
                    </div>
                </div>
                <button type=\"button\" class=\"w3-button w3-margin-bottom\"><i class=\"fa fa-star\"></i></button>
                <button type=\"button\" class=\"w3-button w3-margin-bottom\"><i class=\"fa fa-star\"></i></button>
                <button type=\"button\" class=\"w3-button w3-margin-bottom\"><i class=\"fa fa-star\"></i></button>
                <button type=\"button\" class=\"w3-button w3-margin-bottom\"><i class=\"fa fa-star\"></i></button>
                <button type=\"button\" class=\"w3-button w3-margin-bottom\"><i class=\"fa fa-star\"></i></button>
                <span class=\"w3-right w3-opacity\">Calification: 0</span>
            </div>");
        }
        endConnection($conn);
    };

    function addProvinces(){
        $conn = startConnection();
        $query = oci_parse($conn, 'SELECT * FROM PROVINCE');
        oci_execute($query);

        while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo ("
                <option value=\"".$row['PROVINCE_ID']."\">".$row['PROVINCE_NAME']."</option>
            ");
        }
        endConnection($conn);
    };

    function addCanton(){
    $conn = startConnection();
    $switch = 1;

    $query = oci_parse($conn, 'SELECT * FROM PROVINCE');
    oci_execute($query);

    while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo ("
                case ".$row['PROVINCE_ID'].":
                addOpt(oCntrl,  0, \"Filtrar por canton\", \"0\");
        ");

        $query2 = oci_parse($conn, 'SELECT * FROM CANTON WHERE PROVINCE_ID = :p_id');
        OCIBindByName($query2,":p_id",$row['PROVINCE_ID']);
        oci_execute($query2);
        $i = 1;
        while ($row2 = oci_fetch_array($query2, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo ("
                    addOpt(oCntrl,  ".$i.", \"".$row2['CANTON_NAME']."\", \"".$row2['CANTON_ID']."\");
                ");
            $i = $i+1;
        }
        echo ("
                break;
        ");
        $switch = $switch+1;
    }
    endConnection($conn);
};

    function addDistrict(){
        $conn = startConnection();

        $switch = 1;
        $query = oci_parse($conn, 'SELECT * FROM CANTON');
        oci_execute($query);

        while ($row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS)) {
            echo("
                case " . $row['CANTON_ID'] . ":
                addOpt(oCntrl,  0, \"Filtrar por distrito\", \"0\");
        ");

            $query2 = oci_parse($conn, 'SELECT * FROM DISTRICT WHERE CANTON_ID = :p_id');
            OCIBindByName($query2, ":p_id", $row['CANTON_ID']);
            oci_execute($query2);
            $i = 1;
            while ($row2 = oci_fetch_array($query2, OCI_ASSOC + OCI_RETURN_NULLS)) {
                echo("
                    addOpt(oCntrl,  " . $i . ", \"" . $row2['DISTRICT_NAME'] . "\", \"" . $row2['DISTRICT_ID'] . "\");
                ");
                $i = $i + 1;
            }
            echo("
                break;
        ");
            $switch = $switch + 1;
        }

        endConnection($conn);
    };

    function addClass(){
    $conn = startConnection();
    $query = oci_parse($conn, 'SELECT * FROM ANML_CLASS');
    oci_execute($query);

    while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo ("
                <option value=\"".$row['CLASS_ID']."\">".$row['CLASS_NAME']."</option>
            ");
    }
    endConnection($conn);
};

    function addOrder(){
    $conn = startConnection();
    $switch = 1;

    $query = oci_parse($conn, 'SELECT * FROM ANML_CLASS');
    oci_execute($query);

    while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo ("
                case ".$row['CLASS_ID'].":
                addOpt(oCntrl,  0, \"Filtrar por orden\", \"0\");
        ");

        $query2 = oci_parse($conn, 'SELECT * FROM ANML_ORDER WHERE CLASS_ID = :p_id');
        OCIBindByName($query2,":p_id",$row['CLASS_ID']);
        oci_execute($query2);
        $i = 1;
        while ($row2 = oci_fetch_array($query2, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo ("
                    addOpt(oCntrl,  ".$i.", \"".$row2['ORDER_NAME']."\", \"".$row2['ORDER_ID']."\");
                ");
            $i = $i+1;
        }
        echo ("
                break;
        ");
        $switch = $switch+1;
    }
    endConnection($conn);
};

    function addSuborder(){
    $conn = startConnection();
    $switch = 1;

    $query = oci_parse($conn, 'SELECT * FROM ANML_ORDER');
    oci_execute($query);

    while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo ("
                case ".$row['ORDER_ID'].":
                addOpt(oCntrl,  0, \"Filtrar por suborden\", \"0\");
        ");

        $query2 = oci_parse($conn, 'SELECT * FROM ANML_SUBORDER WHERE ORDER_ID = :p_id');
        OCIBindByName($query2,":p_id",$row['ORDER_ID']);
        oci_execute($query2);
        $i = 1;
        while ($row2 = oci_fetch_array($query2, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo ("
                    addOpt(oCntrl,  ".$i.", \"".$row2['SUBORDER_NAME']."\", \"".$row2['SUBORDER_ID']."\");
                ");
            $i = $i+1;
        }
        echo ("
                break;
        ");
        $switch = $switch+1;
    }
    endConnection($conn);
};

    function addFamily(){
    $conn = startConnection();
    $switch = 1;

    $query = oci_parse($conn, 'SELECT * FROM ANML_SUBORDER');
    oci_execute($query);

    while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo ("
                case ".$row['SUBORDER_ID'].":
                addOpt(oCntrl,  0, \"Filtrar por familia\", \"0\");
        ");

        $query2 = oci_parse($conn, 'SELECT * FROM ANML_FAMILY WHERE SUBORDER_ID = :p_id');
        OCIBindByName($query2,":p_id",$row['SUBORDER_ID']);
        oci_execute($query2);
        $i = 1;
        while ($row2 = oci_fetch_array($query2, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo ("
                    addOpt(oCntrl,  ".$i.", \"".$row2['FAMILY_NAME']."\", \"".$row2['FAMILY_ID']."\");
                ");
            $i = $i+1;
        }
        echo ("
                break;
        ");
        $switch = $switch+1;
    }
    endConnection($conn);
};

    function addGender(){
    $conn = startConnection();
    $switch = 1;

    $query = oci_parse($conn, 'SELECT * FROM ANML_FAMILY');
    oci_execute($query);

    while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo ("
                case ".$row['FAMILY_ID'].":
                addOpt(oCntrl,  0, \"Filtrar por genero\", \"0\");
        ");

        $query2 = oci_parse($conn, 'SELECT * FROM ANML_GENDER WHERE FAMILY_ID = :p_id');
        OCIBindByName($query2,":p_id",$row['FAMILY_ID']);
        oci_execute($query2);
        $i = 1;
        while ($row2 = oci_fetch_array($query2, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo ("
                    addOpt(oCntrl,  ".$i.", \"".$row2['GENDER_NAME']."\", \"".$row2['GENDER_ID']."\");
                ");
            $i = $i+1;
        }
        echo ("
                break;
        ");
        $switch = $switch+1;
    }
    endConnection($conn);
};

    function addSpecie(){
    $conn = startConnection();
    $switch = 1;

    $query = oci_parse($conn, 'SELECT * FROM ANML_GENDER');
    oci_execute($query);

    while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo ("
                case ".$row['GENDER_ID'].":
                addOpt(oCntrl,  0, \"Filtrar por especie\", \"0\");
        ");

        $query2 = oci_parse($conn, 'SELECT * FROM ANML_SPECIE WHERE GENDER_ID = :p_id');
        OCIBindByName($query2,":p_id",$row['GENDER_ID']);
        oci_execute($query2);
        $i = 1;
        while ($row2 = oci_fetch_array($query2, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo ("
                    addOpt(oCntrl,  ".$i.", \"".$row2['SPECIE_NAME']."\", \"".$row2['SPECIE_ID']."\");
                ");
            $i = $i+1;
        }
        echo ("
                break;
        ");
        $switch = $switch+1;
    }
    endConnection($conn);
};

    function addColor(){
        $conn = startConnection();
        $query = oci_parse($conn, 'SELECT ANML_SPECIE.SPECIE_COLOR FROM SIGHT INNER JOIN ANML_SPECIE
                                            ON SIGHT.SPECIE_ID = ANML_SPECIE.SPECIE_ID
                                            GROUP BY ANML_SPECIE.SPECIE_COLOR');
        oci_execute($query);

        while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo ("
                    <option value='".$row['SPECIE_COLOR']."'>".$row['SPECIE_COLOR']."</option>
                ");
        }
        endConnection($conn);
    };

    function addSize(){
        $conn = startConnection();
        $query = oci_parse($conn, 'SELECT ANML_SPECIE.SPECIE_SIZE FROM SIGHT INNER JOIN ANML_SPECIE
                                            ON SIGHT.SPECIE_ID = ANML_SPECIE.SPECIE_ID
                                            GROUP BY ANML_SPECIE.SPECIE_SIZE');
        oci_execute($query);

        while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo ("
                    <option value='".$row['SPECIE_SIZE']."'>".$row['SPECIE_SIZE']."</option>
                ");
        }
        endConnection($conn);
};

    function changePass($currp, $newp){
        $conn = startConnection();
        $query = oci_parse($conn, 'SELECT SYSTEM_USER.USER_PASSWORD FROM SYSTEM_USER WHERE USER_ID = :usuario');
        OCIBindByName($query,":usuario",$_SESSION['USER_ID']);
        oci_execute($query);
        $salt = 25565;
        $cryptnp = crypt($newp,'$2y$8$'.$salt.'$');
        while ($row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS)) {
            if(crypt($currp,'$2y$8$'.$salt.'$') == $row['USER_PASSWORD']){

                $query2 = oci_parse($conn, 'UPDATE SYSTEM_USER SET USER_PASSWORD = :newpass WHERE USER_ID = :usuario');
                OCIBindByName($query2,":usuario",$_SESSION['USER_ID']);
                OCIBindByName($query2,":newpass",$cryptnp);
                oci_execute($query2);

                $query3 = oci_parse($conn, 'INSERT INTO BINNACLE(BINNACLE_ID, USER_ID, OLD_PASSWORD, CURRENT_PASSWORD)
                                                   VALUES (SEQ_BINNACLE.NEXTVAL, :userid, :oldpw, :newpw)');
                OCIBindByName($query3,":userid",$_SESSION['USER_ID']);
                OCIBindByName($query3,":oldpw",$row['USER_PASSWORD']);
                OCIBindByName($query3,":newpw",$cryptnp);

                oci_execute($query3);

                commit($conn);
                header('Location: successful.php');
            } else {
                header('Location: wrongPass.php');
            }
        };
        endConnection($conn);
    }

    function loadTop(){
        $conn = startConnection();
        $query = oci_parse($conn, 'select * 
                                            FROM  
                                            (SELECT USER_ID, COUNT(*) 
                                                FROM SIGHT 
                                                GROUP BY USER_ID 
                                                order by COUNT(*) desc) 
                                                  WHERE ROWNUM <=5');
        oci_execute($query);
        $position = 1;
        while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $query2 = oci_parse($conn, 'SELECT * FROM SYSTEM_USER WHERE USER_ID = :id');
            OCIBindByName($query2,":id",$row['USER_ID']);
            oci_execute($query2);
            $row2 = oci_fetch_array($query2, OCI_ASSOC+OCI_RETURN_NULLS);
            echo ("
            <br>
                <div class=\"w3-hover-text-gray w3-card-2 w3-round w3-gray w3-padding-32 w3-center\">
                    <p><i class=\"fa fa-trophy w3-xlarge\" style=\"color: #d42515\">
                            <i class=\"fa w3-badge w3-orange w3-small w3-right\">".$position."</i>
                        </i>
                    <br><i class=\"fa fa-user\" style=\"color: #000000\"></i> ".$row2['USER_FIRSTNAME']." ".$row2['USER_LASTNAME']."</br>
                        <i class=\"fa fa-binoculars\" style=\"color: #000000\"></i> ".$row['COUNT(*)']."</p>
                </div>
            ");
            $position++;
        }
        endConnection($conn);
    }

    function loadUsers(){
        $conn = startConnection();
        $query = oci_parse($conn, 'SELECT * FROM SYSTEM_USER');
        oci_execute($query);

        while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo ("
            <div id=\"".$row['USER_ID']."\" class=\"w3-container w3-card-2 w3-white w3-round w3-margin w3-row-padding\"><br>
                <img src=\"img/iconbh.png\" alt=\"Avatar\" class=\"w3-left w3-circle w3-margin-right\" style=\"width:60px\">
                <span class=\"w3-right w3-opacity\">".$row['DATE_CREATION']."</span>
                <h5>".$row['USER_FIRSTNAME'].' '.$row['USER_LASTNAME']." (".$row['USER_NICKNAME'].") ".$row['USER_EMAIL']."
                </h5><br>
            </div>");
        }
        endConnection($conn);
    }

    function dangerExtinction(){
        $conn = startConnection();
        $query = oci_parse($conn, 'SELECT ANML_SPECIE.DANGER_OF_EXTINCTION,ANML_SPECIE.SPECIE_NAME, SIGHT.SIGHT_ID,SIGHT.DATE_CREATION, SIGHT.IMAGE_URL, SYSTEM_USER.USER_NICKNAME, SYSTEM_USER.USER_EMAIL, DISTRICT.DISTRICT_NAME, CANTON.CANTON_NAME, PROVINCE.PROVINCE_NAME, COUNTRY.COUNTRY_NAME
    FROM SIGHT INNER JOIN SYSTEM_USER
    ON SYSTEM_USER.USER_ID = SIGHT.USER_ID
    INNER JOIN ANML_SPECIE
    ON SIGHT.SPECIE_ID = ANML_SPECIE.SPECIE_ID
    INNER JOIN DISTRICT
    ON DISTRICT.DISTRICT_ID = SIGHT.DISTRICT_ID
    INNER JOIN CANTON
    ON DISTRICT.CANTON_ID = CANTON.CANTON_ID
    INNER JOIN PROVINCE
    ON CANTON.PROVINCE_ID = PROVINCE.PROVINCE_ID
    INNER JOIN COUNTRY
    ON PROVINCE.COUNTRY_ID = COUNTRY.COUNTRY_ID
    ORDER BY SIGHT.SIGHT_ID DESC');
        oci_execute($query);
        while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
            if($row['DANGER_OF_EXTINCTION']!=0) {
                echo("
                <div id=\"" . $row['SIGHT_ID'] . "\" class=\"w3-container w3-card-2 w3-white w3-round w3-margin w3-row-padding\"><br>
                    <img src=\"img/iconbh.png\" alt=\"Avatar\" class=\"w3-left w3-circle w3-margin-right\" style=\"width:60px\">
                    <span class=\"w3-right w3-opacity\">" . $row['DATE_CREATION'] . "</span>
                    <h5>" . $row['USER_NICKNAME'] ." ".$row['USER_EMAIL']."</h5><br>
                    <hr class=\"w3-clear\">
                    <p><i class=\"fa fa-camera fa-fw w3-margin-right w3-text-theme\"></i>" . $row['SPECIE_NAME'] . "</p>
                    <p><i class=\"fa fa-location-arrow fa-fw w3-margin-right w3-text-theme\"></i> " . $row['COUNTRY_NAME'] . ",
                     " . $row['PROVINCE_NAME'] . ", " . $row['CANTON_NAME'] . ", " . $row['DISTRICT_NAME'] . ".</p>
                    <div class=\"w3-row-padding\" style=\"margin:0 -16px\">
                        <div class=\"w3-image\">
                            <img src=\"" . $row['IMAGE_URL'] . "\" style=\"width:100%\" alt=\"Northern Lights\" 
                            class=\"w3-margin-bottom\">
                        </div>
                    </div>
                </div>
                ");
            }
        }
        endConnection($conn);
    }


    function createClass(){
        $conn = startConnection();

        $query = oci_parse($conn, 'INSERT INTO ANML_CLASS(CLASS_ID, CLASS_NAME) VALUES(SEQ_CLASS.NEXTVAL, :cname)');
        OCIBindByName($query,":cname",$_POST['name']);
        oci_execute($query);

        commit($conn);
        endConnection($conn);
        header('Location: \DBP1/adminTools_addClass.php');
    }

    function createOrder(){
        $conn = startConnection();

        $query = oci_parse($conn, 'INSERT INTO ANML_ORDER(ORDER_ID, ORDER_NAME, CLASS_ID) VALUES(SEQ_ORDER.NEXTVAL, :cname, :cid)');
        OCIBindByName($query,":cname",$_POST['name']);
        OCIBindByName($query,":cid",$_POST['motherid']);
        oci_execute($query);

        commit($conn);
        endConnection($conn);
        header('Location: \DBP1/adminTools_addOrder.php');
    }

    function createSuborder(){
        $conn = startConnection();

        $query = oci_parse($conn, 'INSERT INTO ANML_SUBORDER(SUBORDER_ID, SUBORDER_NAME, ORDER_ID) VALUES(SEQ_SUBORDER.NEXTVAL, :cname, :cid)');
        OCIBindByName($query,":cname",$_POST['name']);
        OCIBindByName($query,":cid",$_POST['motherid']);
        oci_execute($query);

        commit($conn);
        endConnection($conn);
        header('Location: \DBP1/adminTools_addSuborder.php');
    }

    function createFamily(){
        $conn = startConnection();

        $query = oci_parse($conn, 'INSERT INTO ANML_FAMILY(FAMILY_ID, FAMILY_NAME, SUBORDER_ID) VALUES(SEQ_FAMILY.NEXTVAL, :cname, :cid)');
        OCIBindByName($query,":cname",$_POST['name']);
        OCIBindByName($query,":cid",$_POST['motherid']);
        oci_execute($query);

        commit($conn);
        endConnection($conn);
        header('Location: \DBP1/adminTools_addFamily.php');
    }

    function createGender(){
        $conn = startConnection();

        $query = oci_parse($conn, 'INSERT INTO ANML_GENDER(GENDER_ID, GENDER_NAME, FAMILY_ID) VALUES(SEQ_GENDER.NEXTVAL, :cname, :cid)');
        OCIBindByName($query,":cname",$_POST['name']);
        OCIBindByName($query,":cid",$_POST['motherid']);
        oci_execute($query);

        commit($conn);
        endConnection($conn);
        header('Location: \DBP1/adminTools_addGender.php');
    }

    function createSpecie(){
        $conn = startConnection();

        $query = oci_parse($conn, 'INSERT INTO ANML_SPECIE(SPECIE_ID, SCIENTIFIC_NAME, SPECIE_NAME,SPANISH_NAME,ENGLISH_NAME, SPECIE_SIZE, SPECIE_COLOR, DANGER_OF_EXTINCTION, GENDER_ID)
          VALUES(SEQ_SPECIE.NEXTVAL, :scname, :sname, :spname, :enname, :tam, :color, :extdanger, :gender)');
        OCIBindByName($query,":scname",$_POST['scientific']);
        OCIBindByName($query,":sname",$_POST['specie']);
        OCIBindByName($query,":spname",$_POST['spanish']);
        OCIBindByName($query,":enname",$_POST['english']);
        OCIBindByName($query,":tam",$_POST['size']);
        OCIBindByName($query,":color",$_POST['color']);
        OCIBindByName($query,":extdanger",$_POST['danger']);
        OCIBindByName($query,":gender",$_POST['gender']);
        oci_execute($query);

        commit($conn);
        endConnection($conn);
        header('Location: \DBP1/adminTools_addSpecie.php');
    }
?>