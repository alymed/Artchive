<?php


    require_once( 'lib/lib.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $idImage = $_GET['query'];

    $imageData = getFileDetails($idImage);

    $imageData_encoded =  json_encode( $imageData );

    echo $imageData_encoded;


?>
