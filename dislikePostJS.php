<?php
    require_once( 'lib/lib.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $idUser= $_GET['query1'];
    $idPost= $_GET['query2'];

    $dislikeOk = dislikePost($idUser,$idPost);
    
    if($dislikeOk){
        if(!removeActivity($idUser, 'like', $idPost)) {
            $dislikeOk = false;
        }   
    }

    $dislikeOk_encoded =  json_encode( $dislikeOk );

    echo $dislikeOk_encoded;
?>
