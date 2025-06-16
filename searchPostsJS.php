<?php

    require_once( 'lib/lib.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    $search = $_GET['query'];


    $posts = searchPosts($search);

    $posts_encoded =  json_encode( $posts );

    echo $posts_encoded;
?>
