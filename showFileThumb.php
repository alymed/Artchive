<?php



    require_once( "Lib/lib.php" );
    require_once( "Lib/db.php" );

    // TODO validate input data
    $id = $_GET['id'];

    // Read from the data base details about the file
    $fileDetails = getFileDetails( $id );

    $size = $_GET['size'];

    switch ($fileDetails['mimeFilename']) {

        case 'image':  

            switch ($size) {
                case 'small':
                    $thumbFilename = $fileDetails[ 'thumbFilenameS' ];
                    break;
                case 'medium':
                    $thumbFilename = $fileDetails[ 'thumbFilenameM' ];
                    break;
                case 'large':
                    $thumbFilename = $fileDetails[ 'thumbFilenameL' ];
                    break;
            }
            break;

        case 'video':

            $thumbFilename = $fileDetails[ 'thumbFilenameL' ];
            break;
        
        case 'audio':
            
            $thumbFilename = $fileDetails[ 'thumbFilenameS' ];
            break;


    }

    $thumbMimeFilename = $fileDetails[ 'thumbMimeFilename' ];
    $thumbTypeFilename = $fileDetails[ 'thumbTypeFilename' ];



    header( "Content-type: $thumbMimeFilename/$thumbTypeFilename");
    header( "Content-Length: " . filesize($thumbFilename) );

    $thumbFileHandler = fopen( $thumbFilename, 'rb' );
    fpassthru( $thumbFileHandler );

    fclose( $thumbFileHandler );
?>