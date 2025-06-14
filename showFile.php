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

    $filename = $fileDetails[ 'filename' ];
    $mimeFilename = $fileDetails[ 'mimeFilename' ];
    $typeFilename = $fileDetails[ 'typeFilename' ];



    header( "Content-type: $mimeFilename/$typeFilename");
    header( "Content-Length: " . filesize($filename) );

    $thumbFileHandler = fopen( $filename, 'rb' );
    fpassthru( $thumbFileHandler );

    fclose( $thumbFileHandler );
?>