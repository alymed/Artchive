<?php

    session_start();

    require_once( 'lib/lib.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    $search = $_GET['query'];
    $idUser = $_SESSION['id'];

    $users = searchUsers($search, $idUser);

    $users_encoded =  json_encode( $users );

    echo $users_encoded;
?>
