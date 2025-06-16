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

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("Lib/lib.php");
    require_once("Lib/ImageResize.php");

    $ffmpegBinary = "/usr/local/bin/ffmpeg";

    if ($_FILES['userFile']['error'] != 0) {
        $msg = showUploadFileError($_FILES['userFile']['error']);
        echo "\t\t<p>$msg</p>\n";
        echo "\t\t<p><a href='javascript:history.back()'>Back</a></p>\n";
        echo "\t</body>\n";
        echo "</html>\n";
        die();
    }

    $srcName = $_FILES['userFile']['name'];
    $configuration = getConfiguration();
    $dstDir = trim($configuration['destination']);

    if (!is_dir($dstDir)) {
        mkdir($dstDir, 0777, true);
    }

    // Destination for the uploaded file
    $src = $_FILES['userFile']['tmp_name'];
    $idUser = $_SESSION['id'];
    $dstUser = $dstDir . DIRECTORY_SEPARATOR . $idUser;
    $dst = $dstUser . DIRECTORY_SEPARATOR . $srcName;

    if (!is_dir($dstUser)) {
        mkdir($dstUser, 0777, true);
    }

    $copyResult = copy($src, $dst);

    if ($copyResult === false) {
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
    echo "fileInfo: " . $fileInfoData;

    echo "<pre>\n";
    print_r($fileInfoData);
    echo "</pre>\n<br>";

    $fileTypeComponents = explode(";", $fileInfoData);

    $mimeTypeFileUploaded = explode("/", $fileTypeComponents[0]);
    $mimeFilename = $mimeTypeFileUploaded[0];
    $typeFilename = $mimeTypeFileUploaded[1];

    if ($mimeFilename == 'video') {
        $typeFilename = strtolower(pathinfo($dst, PATHINFO_EXTENSION));
        if ($typeFilename == 'm4a') {
            $mimeFilename = 'audio';
        }
    }

    echo "realFileInfo: $mimeFilename/$typeFilename";

    $thumbsDir = $dstUser . DIRECTORY_SEPARATOR . "thumbs";

    if (!is_dir($thumbsDir)) {
        mkdir($thumbsDir, 0777, true);
    }

    $pathParts = pathinfo($dst);

    ?>
    <p>File uploaded with success.</p>
    <?php


    if ($_POST['description'] != NULL) {
        $description = addslashes($_POST['description']);
    } else {
        $description = "No description available";
    }

    if ($_POST['title'] != NULL) {
        $title = addslashes($_POST['title']);
    } else {
        $pathParts = pathinfo($srcName);
        $title = $pathParts['filename'];
    }

    if ($_POST['privacy'] != NULL) {
        $privacy = addslashes($_POST['privacy']);
    } else {
        $privacy = 'public';
    }

    if (isset($_POST['category']) && is_numeric($_POST['category'])) {
        $category = intval($_POST['category']);
    } else {
        $category = null;
    }


    $width = $configuration['thumbWidth'];
    $heightS = $configuration['thumbHeightS'];
    $heightM = $configuration['thumbHeightM'];
    $heightL = $configuration['thumbHeightL'];


    ?>
    <p>File is of type <?php echo $mimeFilename; ?>.</p>
    <?php

    $imageFilenameAux = $imageMimeFilename = $imageTypeFilename = null;
    $thumbFilenameSAux = $thumbFilenameMAux = $thumbFilenameLAux = $thumbMimeFilename = $thumbTypeFilename = null;

    switch ($mimeFilename) {
        case "image":

            $imageFilenameAux = $dst;
            $imageMimeFilename = "image";
            $imageTypeFilename = $typeFilename;

            $thumbFilenameSAux = $thumbsDir . DIRECTORY_SEPARATOR . $pathParts['filename'] . 'S' . "." . $typeFilename;
            $thumbFilenameMAux = $thumbsDir . DIRECTORY_SEPARATOR . $pathParts['filename'] . 'M' . "." . $typeFilename;
            $thumbFilenameLAux = $thumbsDir . DIRECTORY_SEPARATOR . $pathParts['filename'] . 'L' . "." . $typeFilename;
            $thumbMimeFilename = "image";
            $thumbTypeFilename = $typeFilename;

            $sizes = [
                $thumbFilenameSAux => $heightS,
                $thumbFilenameMAux => $heightM,
                $thumbFilenameLAux => $heightL
            ];

            foreach ($sizes as $savepath => $height) {

                $resizeObj = new ImageResize($dst);
                $resizeObj->resizeImage($width, $height, 'crop');
                $resizeObj->saveImage($savepath, $typeFilename, 100);
                $resizeObj->close();

            }
            break;


        case "video":

            $size = "$width" . "x" . "$heightL";

            $imageFilenameAux = $thumbsDir . DIRECTORY_SEPARATOR . $pathParts['filename'] . "-Large.jpg";
            $imageMimeFilename = "image";
            $imageTypeFilename = "jpeg";
            echo "\t\t<p>Generating video 1st image...</p>\n";


            echo "<p>FFmpeg path: $ffmpegBinary</p>";
            echo "<p>Video source path: $dst</p>";
            echo "<p>Image output path (1st frame): $imageFilenameAux</p>";
            echo "<p>Image output path (thumb): $thumbFilenameLAux</p>";

            // -itsoffset -1 -> "moves" the film one second forward
            // -i $dst -> input file
            // -vcodec mjpeg -> codec do tipo mjpeg
            // -vframes 1 -> obter uma frame
            // -s 640x480 -> dimensão do output
            $cmdFirstImage = "$ffmpegBinary -itsoffset -1 -i " . escapeshellarg($dst) . " -frames:v 1 -q:v 2 -s 640x480 " . escapeshellarg($imageFilenameAux);

            echo "\t\t<p><code>$cmdFirstImage</code></p>\n";
            system($cmdFirstImage, $status);
            echo "\t\t<p>Status from the generation of video 1st image: $status.</p>\n";

            $thumbFilenameSAux = "";
            $thumbFilenameMAux = "";
            $thumbFilenameLAux = $thumbsDir . DIRECTORY_SEPARATOR . $pathParts['filename'] . ".jpg";
            $thumbMimeFilename = "image";
            $thumbTypeFilename = "jpeg";
            echo "\t\t<p>Generating video thumb...</p>\n";

            $cmdVideoThumb = "$ffmpegBinary -itsoffset -1 -i " . escapeshellarg($dst) . " -frames:v 1 -q:v 2 -s $size " . escapeshellarg($thumbFilenameLAux);
            echo "\t\t<p><code>$cmdVideoThumb</code></p>\n";
            system($cmdVideoThumb, $status);
            echo "\t\t<p>Status from the generation of video thumb: $status.</p>\n";
            break;

        case "audio":

            $defaultDir = $dstDir . DIRECTORY_SEPARATOR . "Default";

            if (!is_dir($defaultDir)) {
                mkdir($defaultDir, 0777, true);
            }

            $destinationPath = $defaultDir . DIRECTORY_SEPARATOR . "default-audio-thumbnail.jpg";
            $sourcePath = __DIR__ . '/images/default-audio-thumbnail.jpg';
            $copyResult = copy($sourcePath, $destinationPath);

            if ($copyResult === false) {
                $msg = "Could not write '$sourcePath' to '$destinationPath'";
                echo "\t\t<p>$msg</p>\n";
                echo "\t\t<p><a href='javascript:history.back()'>Back</a></p>";
                echo "\t</bobdy>\n";
                echo "\t</html>\n";
                die();
            }

            $imageFilenameAux = $defaultDir . DIRECTORY_SEPARATOR . "default-audio-thumbnail-Large.jpg";
            $imageMimeFilename = "image";
            $imageTypeFilename = "jpeg";

            $resizeObj = new ImageResize($destinationPath);
            $resizeObj->resizeImage(640, 480, 'crop');
            $resizeObj->saveImage($imageFilenameAux, $imageTypeFilename, 100);
            $resizeObj->close();



            $thumbFilenameSAux = $defaultDir . DIRECTORY_SEPARATOR . "default-audio-thumbnail.jpg";
            $thumbFilenameMAux = "";
            $thumbFilenameLAux = "";
            $thumbMimeFilename = "image";
            $thumbTypeFilename = "jpeg";

            $resizeObj = new ImageResize($destinationPath);
            $resizeObj->resizeImage($width, $heightS, 'crop');
            $resizeObj->saveImage($thumbFilenameSAux, $thumbTypeFilename, 100);
            $resizeObj->close();

            break;

    }




    $filename = addslashes($dst);
    $imageFilename = addslashes($imageFilenameAux);
    $thumbFilenameS = addslashes($thumbFilenameSAux);
    $thumbFilenameM = addslashes($thumbFilenameMAux);
    $thumbFilenameL = addslashes($thumbFilenameLAux);

    $idFile = uploadFile(
        $filename,
        $mimeFilename,
        $typeFilename,
        $imageFilename,
        $imageMimeFilename,
        $imageTypeFilename,
        $thumbFilenameS,
        $thumbFilenameM,
        $thumbFilenameL,
        $thumbMimeFilename,
        $thumbTypeFilename
    );


    if ($idFile > 0) {

        $idPost = uploadPost($title, $description, $privacy, $idUser, $idFile, $category);
        if ($idPost > 0) {
            echo "Success!";
            header("Location: app.php");
    
        } else {
            echo "Information about file could not be inserted into the data base. Details : " . dbGetLastError();
            //header("Location: index.php");
        }
    } else {
        echo "Information about file could not be inserted into the data base. Details : " . dbGetLastError();
        //header("Location: index.php");
    }
    ?>

</body>

</html>