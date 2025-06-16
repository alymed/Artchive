<?php

    session_start();

    if (!isset($_SESSION['id'])) {
        
        header('Location: index.php');
        exit();
    }

    require_once( "Lib/lib.php" );
    require_once( "Lib/db.php" );

    $id = $_GET['id'];

    $fileDetails = getFileDetails( $id );

    $imageFilename = $fileDetails[ 'imageFilename' ];
    $imageMimeFilename = $fileDetails[ 'imageMimeFilename' ];
    $imageTypeFilename = $fileDetails[ 'imageTypeFilename' ];

    header( "Content-type: $imageMimeFilename/$imageTypeFilename");
    header( "Content-Length: " . filesize($imageFilename) );

    $thumbFileHandler = fopen( $imageFilename, 'rb' );
    fpassthru( $thumbFileHandler );

    fclose( $thumbFileHandler );
?>