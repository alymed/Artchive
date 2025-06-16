<?php
    require_once( 'lib/lib.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $idUser = $_GET['query1'];
    $idPost = $_GET['query2'];

    $isLiked = checkIfLiked($idUser, $idPost);
    $isLiked_encoded =  json_encode( $isLiked );

    echo $isLiked_encoded;
?>
