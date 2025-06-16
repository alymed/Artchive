<?php
    require_once( "Lib/lib.php" );
    require_once( "Lib/db.php" );

    $id = $_GET['id'];

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