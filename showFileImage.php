<?php

    session_start();

    if (!isset($_SESSION['id'])) {
        
        header('Location: index.php');
        exit();
    }

    require_once( "Lib/lib.php" );
    require_once( "Lib/db.php" );

    // TODO validate input data
    $id = $_GET['id'];

    // Read from the data base details about the file
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