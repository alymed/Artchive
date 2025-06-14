<?php


    require_once( 'lib/lib.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $idUser = $_GET['query'];

    $userData = getUserData($idUser);

    $userData_encoded =  json_encode( $userData );

    echo $userData_encoded;


?>
