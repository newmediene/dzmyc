<?php

    // -- SCRIPT NAME DZMYC ------------ /|
    // -- DZ MY YOUTUBE CHANNEL -------- /|
    // -- DESIGN BY NEWMEDIENE --------- /|
    // -- 06-2021 | ALGERIA SBA -------- /|
    // -- NEWMEDIENE@GMAIL.COM --------- /|
    // -- FB.COM/NEWMEDIENE ------------ /|
    // -- WWW.NEWMEDIENE.COM ----------- /|
    // -- V1.0 FREE COPY NOT FOR SALE -- /|

    /* HERE USERNAME ----- */$UsernameData = ""; 
    /* HERE PASSWORD ----- */$PasswordData = "";
    /* HERE DATABASE NAME  */$DatabaseName = "";
    /* HERE SERVER NAME -- */$ServerName   = "";

    $database = new PDO("mysql:host=".$ServerName."; dbname=".$DatabaseName.";charset=utf8;",$UsernameData,$PasswordData);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); ?>