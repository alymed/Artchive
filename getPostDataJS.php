<?php


    require_once( 'lib/lib.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $idPost = $_GET['query'];

    $postData = getPostData($idPost);

    $postData_encoded =  json_encode( $postData );

    echo $postData_encoded;


?>
