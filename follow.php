<?php


    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php" );


    $flags[] = FILTER_NULL_ON_FAILURE;

    $idFollower = filter_input( INPUT_GET, 'idFollower', FILTER_UNSAFE_RAW, $flags);
    $idFollowed = filter_input( INPUT_GET, 'idFollowed', FILTER_UNSAFE_RAW, $flags);

    if (follow($idFollower, $idFollowed)) {
        echo 'Follow Success!';
        if(addActivity($idFollower, 'follow', $idFollowed, $idFollowed)) {
            echo 'Activity Add Success!';
        }else{
            echo "Error at adding activity";
        }
        redirectToLastPage("","",0);
    }else{
        echo "Error at following";
        redirectToLastPage("","",5);
    }

?>