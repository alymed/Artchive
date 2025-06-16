<?php


    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php" );


    $flags[] = FILTER_NULL_ON_FAILURE;

    $idFollower = filter_input( INPUT_GET, 'idFollower', FILTER_UNSAFE_RAW, $flags);
    $idFollowed = filter_input( INPUT_GET, 'idFollowed', FILTER_UNSAFE_RAW, $flags);

    if (unfollow($idFollower, $idFollowed)) {
        echo 'Success at following!';

        if(removeActivity($idFollower, 'follow', $idFollowed)) {
            echo 'Activity Remove Success!';
        }else{
            echo "Error at removing activity";
        }
        redirectToLastPage("","",0);
    }else{
        echo "Error at following";
        redirectToLastPage("","",5);
    }

?>