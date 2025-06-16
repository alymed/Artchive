<?php
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    require_once( "Lib/lib.php" );
    require_once( "Lib/ImageResize.php" );


    $srcName = $_FILES['userFile']['name'];

    // Read configurations from data base
    $configuration = getConfiguration();
    $dstDir = trim($configuration['destination']);

    // Destination for the uploaded file
    $src = $_FILES['userFile']['tmp_name'];


    $idUser = $_SESSION['id'];

    $dstUser = $dstDir. DIRECTORY_SEPARATOR . $idUser;
    $profileDst = $dstUser. DIRECTORY_SEPARATOR . "profile";
    $dst = $profileDst. DIRECTORY_SEPARATOR . $srcName;

    if (!is_dir($dstUser)) {
        mkdir($dstUser, 0777, true);
    }

    if (!is_dir($profileDst)) {
        mkdir($profileDst, 0777, true);
    }

    

    $copyResult = copy($src, $dst);

    unlink($src);


    $fileInfo = finfo_open(FILEINFO_MIME);

    $fileInfoData = finfo_file($fileInfo, $dst);


    $fileTypeComponents = explode( ";", $fileInfoData);

    $mimeTypeFileUploaded = explode("/", $fileTypeComponents[0]);
    $mimeFilename = $mimeTypeFileUploaded[0];
    $typeFilename = $mimeTypeFileUploaded[1];


    $pathParts = pathinfo($dst);


    $profileWidth = $configuration['profileWidth'];
    $profileHeight = $configuration['profileHeight'];




    $imageFilenameAux = $imageMimeFilename = $imageTypeFilename = null;
    $thumbFilenameSAux = $thumbFilenameMAux = $thumbFilenameLAux = $thumbMimeFilename = $thumbTypeFilename = null;

    if ($mimeFilename == 'image') {

        $imageFilenameAux = "";
        $imageMimeFilename = "";
        $imageTypeFilename = "";

        $thumbFilenameSAux = "";
        $thumbFilenameMAux = "";
        $thumbFilenameLAux = "";
        $thumbMimeFilename = "";
        $thumbTypeFilename = "";


        $resizeObj = new ImageResize($dst);
        $resizeObj->resizeImage($profileWidth, $profileHeight, 'crop');
        $resizeObj->saveImage($dst, $typeFilename, 100);
        $resizeObj->close();



    }




    $filename = addslashes($dst);
    $imageFilename = addslashes($imageFilenameAux);
    $thumbFilenameS= addslashes($thumbFilenameSAux);
    $thumbFilenameM= addslashes($thumbFilenameMAux);
    $thumbFilenameL= addslashes($thumbFilenameLAux);

    $idFile = uploadFile($filename, $mimeFilename, $typeFilename,
     $imageFilename, $imageMimeFilename, $imageTypeFilename,
      $thumbFilenameS,$thumbFilenameM,$thumbFilenameL,
       $thumbMimeFilename, $thumbTypeFilename);

    $fileDetails = getFileDetails( $idFile );

    $fileDetails_encoded =  json_encode( $fileDetails );

    echo $fileDetails_encoded;

?>