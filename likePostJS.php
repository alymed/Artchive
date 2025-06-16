<?php


    require_once( 'lib/lib.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $idUser= $_GET['query1'];
    $idPost= $_GET['query2'];


    $likeOk = likePost($idUser,$idPost);

    if($likeOk){

        $postData = getPostData($idPost);
        $sendTo = $postData['idUser'];

        if(addActivity($idUser, 'like', $idPost, $sendTo)) {
            echo 'Activity Add Success!';
        }else{
            echo "Error at adding activity";
        }
        
    }else{
        echo "Error at liking";

    }

    $likeOk_encoded =  json_encode( $likeOk );

    echo $likeOk_encoded;


?>
