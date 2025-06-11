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

    $size = $_GET['size'];

    switch ($size) {
        case 'small':
            $thumbFilenameAux = $fileDetails[ 'thumbFilenameS' ];
            break;
        case 'medium':
            $thumbFilenameAux = $fileDetails[ 'thumbFilenameM' ];
            break;
        case 'large':
            $thumbFilenameAux = $fileDetails[ 'thumbFilenameL' ];
            break;
    }

    $thumbMimeFilename = $fileDetails[ 'thumbMimeFilename' ];
    $thumbTypeFilename = $fileDetails[ 'thumbTypeFilename' ];





    header( "Content-type: $thumbMimeFilename/$thumbTypeFilename");
    header( "Content-Length: " . filesize($thumbFilenameAux) );

    $thumbFileHandler = fopen( $thumbFilenameAux, 'rb' );
    fpassthru( $thumbFileHandler );

    fclose( $thumbFileHandler );
?>