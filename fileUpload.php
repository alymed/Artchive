<?php

session_start();

if (!isset($_SESSION['id'])) {
    
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
        <title>Image Processing</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>

    <body>
<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    require_once( "Lib/lib.php" );
    require_once( "Lib/ImageResize.php" );

    
    if ( $_FILES['userFile']['error']!=0 ) {
        $msg = showUploadFileError( $_FILES['userFile']['error'] );
        echo "\t\t<p>$msg</p>\n";
        echo "\t\t<p><a href='javascript:history.back()'>Back</a></p>\n";
        echo "\t</body>\n";
        echo "</html>\n";
        die();
    }



    $srcName = $_FILES['userFile']['name'];

    // Read configurations from data base
    $configuration = getConfiguration();
    $dstDir = trim($configuration['destination']);

    // Destination for the uploaded file
    $src = $_FILES['userFile']['tmp_name'];


    $idUser = $_SESSION['id'];

    $dstUser = $dstDir. DIRECTORY_SEPARATOR . $idUser;
    $dst = $dstUser. DIRECTORY_SEPARATOR . $srcName;

    if (!is_dir($dstUser)) {
        mkdir($dstUser, 0777, true);
    }

    $copyResult = copy($src, $dst);

    if ( $copyResult === false ) {
        $msg = "Could not write '$src' to '$dst'";
        echo "\t\t<p>$msg</p>\n";
        echo "\t\t<p><a href='javascript:history.back()'>Back</a></p>";
        echo "\t</bobdy>\n";
        echo "\t</html>\n";
        die();
    }

    unlink($src);

?>
        <p>File uploaded with success.</p>
<?php


$fileInfo = finfo_open(FILEINFO_MIME);

    $fileInfoData = finfo_file($fileInfo, $dst);
    

        echo "<pre>\n";
        print_r( $fileInfoData );
        echo "</pre>\n<br>";

    
    $fileTypeComponents = explode( ";", $fileInfoData);

    $mimeTypeFileUploaded = explode("/", $fileTypeComponents[0]);
    $mimeFilename = $mimeTypeFileUploaded[0];
    $typeFilename = $mimeTypeFileUploaded[1];

    $thumbsDir = $dstUser. DIRECTORY_SEPARATOR .  "thumbs";

    if (!is_dir($thumbsDir)) {
        mkdir($thumbsDir, 0777, true);
    }

    $pathParts = pathinfo($dst);

?>
        <p>File uploaded with success.</p>
<?php

    
    if ( $_POST['description']!=NULL ) {
        $description = addslashes($_POST['description']);
    }
    else {
        $description = "No description available";
    }

    if ( $_POST['title']!=NULL ) {
        $title = addslashes($_POST['title']);
    }
    else {
        $pathParts = pathinfo($srcName);
        $title = $pathParts['filename'];
    }


    $width = $configuration['thumbWidth'];
    $height = $configuration['thumbHeightS'];


    ?>
        <p>File is of type <?php echo $mimeFilename;?>.</p>
<?php

    $imageFilenameAux = $imageMimeFilename = $imageTypeFilename = null;

    $thumbFilenameAux = $thumbMimeFilename = $thumbTypeFilename = null;

    switch ($mimeFilename) {
        case "image":
           
              
            $imageFilenameAux = $dst;
            $imageMimeFilename = "image";
            $imageTypeFilename = $typeFilename;

            $thumbFilenameAux = $thumbsDir . DIRECTORY_SEPARATOR . $pathParts['filename'] . "." . $typeFilename;
            $thumbMimeFilename = "image";
            $thumbTypeFilename = $typeFilename;

            $resizeObj = new ImageResize( $dst );
            $resizeObj->resizeImage($width, $height, 'crop');
            $resizeObj->saveImage($thumbFilenameAux, $typeFilename, 100);
            $resizeObj->close();
    
            break;

    }


    $filename = addslashes($dst);
    $imageFilename = addslashes($imageFilenameAux);
    $thumbFilename = addslashes($thumbFilenameAux);

    $idFile = uploadFile($filename, $mimeFilename, $typeFilename, $imageFilename, $imageMimeFilename, $imageTypeFilename, $thumbFilename, $thumbMimeFilename, $thumbTypeFilename);


    if ( $idFile > 0 ) {

        $idPost = uploadPost($title, $description, $idUser, $idFile);
        if($idPost > 0){
                 echo "Success!";
        }else{
            echo "Information about file could not be inserted into the data base. Details : " . dbGetLastError() ;
        }
    }
    else {
        echo "Information about file could not be inserted into the data base. Details : " . dbGetLastError() ;
    }


?>


    </body>
</html>
