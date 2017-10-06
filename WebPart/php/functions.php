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
        $format = 'YYYY-MM-DD';

        $query = OCIParse($conn, '
        CALL ADDSYSTEMUSER(:userName,:userLastname,:userNick,:userPassword,:userMail,TO_DATE(:userBirth,:birthFormat))');



        OCIBindByName($query,":userName",$var1);
        OCIBindByName($query,":userLastname",$var2);
        OCIBindByName($query,":userNick",$var3);
        OCIBindByName($query,":userPassword",$var4);
        OCIBindByName($query,":userMail",$var5);
        OCIBindByName($query,":userBirth",$var6);
        OCIBindByName($query,":birthFormat",$format);

        oci_execute($query);

        commit($conn);
        endConnection($conn);
    }
?>