<?php

require_once( "db.php" );

function getBrowser() {
    $userBrowser = '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    if (preg_match('/Trident/i', $userAgent)) {
        $userBrowser = "Internet Explorer";
    } elseif (preg_match('/MSIE/i', $userAgent)) {
        $userBrowser = "Internet Explorer";
    } elseif (preg_match('/Edg/i', $userAgent)) {
        $userBrowser = "Microsoft Edge";
    } elseif (preg_match('/Firefox/i', $userAgent)) {
        $userBrowser = "Mozilla Firefox";
    } elseif (preg_match('/Chrome/i', $userAgent)) {
        $userBrowser = "Google Chrome";
    } elseif (preg_match('/Safari/i', $userAgent)) {
        $userBrowser = "Apple Safari";
    } elseif (preg_match('/Flock/i', $userAgent)) {
        $userBrowser = "Flock";
    } elseif (preg_match('/Opera/i', $userAgent)) {
        $userBrowser = "Opera";
    } elseif (preg_match('/Netscape/i', $userAgent)) {
        $userBrowser = "Netscape";
    }

    if (preg_match('/Mobile/i', $userAgent)) {
        $userBrowser = "Mobile Device";
    }
    return $userBrowser;
}

function redirectToPage($url, $title, $message, $refreshTime = 5) {
    echo "<html>\n";
    echo "  <head>\n";
    echo "    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>\n";
    echo "    <meta http-equiv=\"REFRESH\" content=\"$refreshTime;url=$url\">\n";
    echo "    <title>$title</title>\n";
    echo "  </head>\n";
    echo "  <body>\n";
    echo "    <p>$message</p>";
    echo "    <p>You will be redirect in $refreshTime seconds.</p>";
    echo "  </body>\n";
    echo "</html>";
    die();
}

$DefaultRedirectMessage = <<<EOD
    <p>Invalid data!</p>
    <p>Please fill all the requiered fields (marked with *).</p>
EOD;

function redirectToLastPage($title, $message = NULL, $refreshTime = 5) {
    $referer = filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);

    echo "<html>\n";
    echo "  <head>\n";
    echo "    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>\n";
    echo "    <meta http-equiv=\"REFRESH\" content=\"$refreshTime;url=$referer\">\n";
    echo "    <title>$title</title>\n";
    echo "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
    echo "    <link REL=\"stylesheet\" TYPE=\"text/css\" href=\"../Styles/GlobalStyle.css\">\n";
    echo "  </head>\n";
    echo "  <body>\n";
    if ( $message != NULL ) {
        echo $message;
    }
    else {
        echo $GLOBALS['DefaultRedirectMessage'];
    }
    echo "    <p>You will be redirect to the last page in $refreshTime seconds.\n";
    echo "  </body>\n";
    echo "</html>";
    die();
}

$find;
$replace;

function convertToEntities($str) {
    global $find;
    global $replace;

    if (($find == NULL) || ($replace == NULL)) {
        $find = array();
        $replace = array();

        foreach (get_html_translation_table(HTML_ENTITIES, ENT_QUOTES) as $key => $value) {
            $find[] = $key;
            $replace[] = $value;
        }
    }

    return str_replace($find, $replace, $str);
}

function webAppName() {
    $uri = explode("/", $_SERVER['REQUEST_URI']);
    $n = count($uri);
    $webApp = "";
    for ($idx = 0; $idx < $n - 1; $idx++) {
        $webApp .= ($uri[$idx] . "/" );
    }

    return $webApp;
}

function prepareHeaders() {
    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
}

function ensureAuth($redirectPage) {
    prepareHeaders();

    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
        header("Location: $redirectPage");
        exit;
    }
}

function showAuth($authType, $realm, $message) {
    header("WWW-Authenticate: $authType realm=\"$realm\"");
    header("HTTP/1.0 401 Unauthorized");

    echo $message;
}

function isValid($email, $password) {

    $userOk = -1;

    dbConnect( ConfigFile );
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $query = 
            "SELECT * FROM `$dataBaseName`.`users-auth` " .
            "WHERE `email`='$email' AND `password`='$password'";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    if ( $result!=false ) {
        $userData = mysqli_fetch_array($result);
        if($userData != null){
            $userOk = $userData['id'];
        }
       
    }
    mysqli_free_result($result);

    dbDisconnect();

    return $userOk;
}


function register($name, $username, $password, $email, $birthdate) {

    $userOk = -1;

    dbConnect( ConfigFile);
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $name = mysqli_real_escape_string($GLOBALS['ligacao'], $name);
    $username    = mysqli_real_escape_string($GLOBALS['ligacao'], $username);
    $password = mysqli_real_escape_string($GLOBALS['ligacao'], $password);
    $email    = mysqli_real_escape_string($GLOBALS['ligacao'], $email);
    $birthdate = mysqli_real_escape_string($GLOBALS['ligacao'], $birthdate);
    $createdAt = date("Y-m-d H:i:s");


    $query = 
            "INSERT INTO  `$dataBaseName`.`users-auth` (`email`, `password`, `created_at`,`status`) ".
            "VALUES ('$email', '$password', '$createdAt', '1')";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    if ($result !== false) {

        $userOk = mysqli_insert_id($GLOBALS['ligacao']);
      
        if (createProfile($userOk, $name, $username, $birthdate)) {

            createToken($userOk);

        } else {
            $query = "DELETE FROM `users-auth` WHERE `id` = '$userOk'";
            mysqli_query($GLOBALS['ligacao'], $query);
        }
    } 

    dbDisconnect();

    return $userOk;
}

function createToken($idUser){
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $token = bin2hex(random_bytes(16));
    $createdAt = date('Y-m-d H:i:s');

    $query = 
            "INSERT INTO  `$dataBaseName`.`tokens-verification` (`token`, `createdAt`, `usedAt`,`idUser`) ".
            "VALUES ('$token', '$createdAt', NULL, '$idUser')";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    if ($result !== false) {
        echo "Token created with success!";
    }else{
        echo "Error creating token: " . dbGetLastError();
    }
}


function createProfile($idUser, $name, $username, $birthdate) {
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );


    $query = 
            "INSERT INTO  `$dataBaseName`.`users-profile` (`id`,`name`, `username`, `birthdate`, `biography`) ".
            "VALUES ('$idUser', '$name', '$username', '$birthdate', NULL)";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    if ($result === false) {
        
        echo "Error inserting profile: " . mysqli_error($GLOBALS['ligacao']);
        return false;
    }

    return true;
}


function existUserField($field, $value, $table) {

    $exists = true;

    dbConnect( ConfigFile );
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $query = "SELECT * FROM `$dataBaseName`.`$table` " .
            "WHERE `$field`='$value'";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    if ( $result==false || mysqli_num_rows($result)==0 ) {
        $exists = false;
    }

    mysqli_free_result($result);

    dbDisconnect();

    return $exists;
}

function getUserData($idUser) {

    dbConnect( ConfigFile );
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $query = "SELECT * FROM `$dataBaseName`.`users-profile` " .
            "WHERE `id`='$idUser'";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    $userData = mysqli_fetch_array($result);

    mysqli_free_result($result);

    dbDisconnect();

    return $userData;
}

function getUserAuthData($idUser) {

    dbConnect( ConfigFile );
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $query = "SELECT * FROM `$dataBaseName`.`users-auth` " .
            "WHERE `id`='$idUser'";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    $userData = mysqli_fetch_array($result);

    mysqli_free_result($result);

    dbDisconnect();

    return $userData;
}



function getTokenDataFromToken($token){
    
    dbConnect( ConfigFile );
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $query = "SELECT * FROM `$dataBaseName`.`tokens-verification` " .
            "WHERE `token`='$token'";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    $tokenData = mysqli_fetch_array($result);

    mysqli_free_result($result);

    dbDisconnect();

    return $tokenData;
}

function getTokenFromUser($idUser){
    
    dbConnect( ConfigFile );
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $query = "SELECT * FROM `$dataBaseName`.`tokens-verification` " .
            "WHERE `id`='$idUser'";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    $userData = mysqli_fetch_array($result);

    mysqli_free_result($result);

    dbDisconnect();

    return $userData;
}

function accountVerifyDB($idUser){

    dbConnect(ConfigFile);

    $dataBaseName = $GLOBALS['configDataBase']->db;
    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);


    $query = "UPDATE `$dataBaseName`.`users-auth` SET `status`='2' WHERE `id` = '$idUser'";

    $result = mysqli_query($GLOBALS['ligacao'], $query);


    if ($result !== false) {
        echo 'Account is now verified!';
    }   else {
        echo "Error verifying profile: " . dbGetLastError();
    }

    dbDisconnect();

}

function editProfile($idUser, $name, $username, $bio) {
    dbConnect(ConfigFile);
    $dataBaseName = $GLOBALS['configDataBase']->db;
    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);

    $fieldsToUpdate = [];

    if (!empty($name)) {
        $name = mysqli_real_escape_string($GLOBALS['ligacao'], $name);
        $fieldsToUpdate[] = "`name` = '$name'";
    }

    if (!empty($username)) {
        $username = mysqli_real_escape_string($GLOBALS['ligacao'], $username);
        $fieldsToUpdate[] = "`username` = '$username'";
    }

    if (!empty($bio)) {
        $bio = mysqli_real_escape_string($GLOBALS['ligacao'], $bio);
        $fieldsToUpdate[] = "`biography` = '$bio'";
    }

    if (!empty($fieldsToUpdate)) {
        $setClause = implode(", ", $fieldsToUpdate);
        $query = "UPDATE `$dataBaseName`.`users-profile` SET $setClause WHERE `id` = '$idUser'";

        $result = mysqli_query($GLOBALS['ligacao'], $query);
        dbDisconnect();

        if (!$result) {
            echo "Error updating profile: " . dbGetLastError();
            return -1;
        }

        return 1;
    }

    dbDisconnect();
    return 0;
}



function getEmail($idUser, $authType) {
    $userEmail = -1;

    dbConnect(ConfigFile);
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $query = "SELECT `email` FROM `$dataBaseName`.`auth-$authType` WHERE `id`='$idUser'";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    if ( $result!=false ) {
        $userData = mysqli_fetch_array($result);
        $userEmail = $userData['email'];
    }
    mysqli_free_result($result);

    dbDisconnect();

    return $userEmail;
}

function logout() {
    
    session_start();
    session_unset();
    session_destroy();

    header("Location: index.php");
    exit;
}

function uploadFile(
    $filename, $mimeFilename, $typeFilename, 
    $imageFilename, $imageMimeFilename, $imageTypeFilename, 
    $thumbFilename, $thumbMimeFilename, $thumbTypeFilename
){
    $fileOk = -1;

    dbConnect( ConfigFile );
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db( $GLOBALS['ligacao'], $dataBaseName );
    
    $query = 
            "INSERT INTO `$dataBaseName`.`images-details`" .
            "(`filename`, `mimeFilename`, `typeFilename`, `imageFilename`, `imageMimeFilename`, `imageTypeFilename`, `thumbFilename`, `thumbMimeFilename`, `thumbTypeFilename`) values " .
            "('$filename', '$mimeFilename', '$typeFilename', '$imageFilename', '$imageMimeFilename', '$imageTypeFilename', '$thumbFilename', '$thumbMimeFilename', '$thumbTypeFilename')";

    $result =  mysqli_query( $GLOBALS['ligacao'], $query );

    if ( $result !== false ) {
      $fileOk = mysqli_insert_id($GLOBALS['ligacao']);
    } 
   
    dbDisconnect();

    return $fileOk;
}

function uploadPost($title, $description, $idUser, $idImage){

    $postOk = -1;

    dbConnect( ConfigFile );
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db( $GLOBALS['ligacao'], $dataBaseName );

    $createdAt = date("Y-m-d H:i:s");
    
    $query = 
            "INSERT INTO `$dataBaseName`.`users-posts`" .
            "(`title`, `description`, `createdAt`, `idUser`, `idImage`) values " .
            "('$title', '$description', '$createdAt', '$idUser', '$idImage')";

    $result =  mysqli_query( $GLOBALS['ligacao'], $query );

    if ( $result !== false ) {

        $postOk = mysqli_insert_id($GLOBALS['ligacao']);
    }
   
    dbDisconnect();

    return $postOk;
}

function getFileDetails($idUser) {
 

    dbConnect(ConfigFile);
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $query = "SELECT * FROM `$dataBaseName`.`images-details` WHERE `id`='$idUser'";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    $fileData = array();
    while (($fileDataRecord = mysqli_fetch_array($result)) != false) {
        $fileData[] = $fileDataRecord;
    }

    mysqli_free_result($result);
    dbDisconnect();

    if ( !is_array($idUser)) {
        return $fileData[0];
    } else {
        return $fileData;
    }
}

function getFilesID($idUser) {


    dbConnect(ConfigFile);
    
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $query = "SELECT `id` FROM `$dataBaseName`.`users-posts` WHERE `idUser`='$idUser'";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    $filesID = array();

    while (($fileDataRecord = mysqli_fetch_array($result)) != false) {
        $filesID[] = $fileDataRecord['idImage'];
    }

    mysqli_free_result($result);
    dbDisconnect();

    return $filesID;

}

function getConfiguration() {
    dbConnect( ConfigFile );

    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    $query = "SELECT * FROM `$dataBaseName`.`images-config`";

    $result = mysqli_query($GLOBALS['ligacao'], $query);

    $configuration = mysqli_fetch_array($result);

    mysqli_free_result($result);

    dbDisconnect();


    return $configuration;
}

function getStats() {
    dbConnect(ConfigFile);

    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName );

    "SELECT COUNT(DISTINCT `mimeFileName`) FROM `$dataBaseName`.`images-details`;";
    "SELECT DISTINCT `mimeFileName` FROM `$dataBaseName`.`images-details`;";

    $queryTotal = "SELECT count(*) AS totalFiles FROM `$dataBaseName`.`images-details`";
    $queryImages = "SELECT count(*) AS totalImages FROM `$dataBaseName`.`images-details` WHERE `mimeFileName`='image'";
    $queryVideos = "SELECT count(*) AS totalVideos FROM `$dataBaseName`.`images-details` WHERE `mimeFileName`='video'";
    $queryAudios = "SELECT count(*) AS totalAudios FROM `$dataBaseName`.`images-details` WHERE `mimeFileName`='audio'";

    // Total files
    $resultTotal = mysqli_query($GLOBALS['ligacao'], $queryTotal);
    $totalData = mysqli_fetch_array($resultTotal);
    $stats['numFiles'] = $totalData['totalFiles'];
    mysqli_free_result($resultTotal);
  
    if ( $stats['numFiles']==0 ) {
        $stats['numImages'] = 0;
        $stats['numVideos'] = 0;
        $stats['numAudios'] = 0;

        dbDisconnect();

        return $stats;
    }

    // Image files
    $resultImages = mysqli_query($GLOBALS['ligacao'], $queryImages);
    $totalImages = mysqli_fetch_array($resultImages);
    $stats['numImages'] = $totalImages['totalImages'];
    mysqli_free_result($resultImages);

    // Video files
    $resultVideos = mysqli_query($GLOBALS['ligacao'], $queryVideos);
    $totalVideos = mysqli_fetch_array($resultVideos);
    $stats['numVideos'] = $totalVideos['totalVideos'];
    mysqli_free_result($resultVideos);

    // Audio files
    $resultAudios = mysqli_query($GLOBALS['ligacao'], $queryAudios);
    $totaltAudios = mysqli_fetch_array($resultAudios);
    $stats['numAudios'] = $totaltAudios['totalAudios'];
    mysqli_free_result($resultAudios);

    dbDisconnect();

    return $stats;
}

function showUploadFileError($errorCode) {
    switch ($errorCode) {
        case UPLOAD_ERR_OK:
            $errorMessage = "($errorCode) There is no error, the file uploaded with success.";
            break;

        case UPLOAD_ERR_INI_SIZE:
            $errorMessage = "($errorCode) The uploaded file exceeds the upload_max_filesize directive in php.ini file.";
            break;

        case UPLOAD_ERR_FORM_SIZE:
            $errorMessage = "($errorCode) The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
            break;

        case UPLOAD_ERR_PARTIAL:
            $errorMessage = "($errorCode) The uploaded file was only partially uploaded.";
            break;

        case UPLOAD_ERR_NO_FILE:
            $errorMessage = "($errorCode) No file was uploaded.";
            break;

        case UPLOAD_ERR_NO_TMP_DIR:
            $errorMessage = "($errorCode) Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.";
            break;

        case UPLOAD_ERR_CANT_WRITE:
            $errorMessage = "($errorCode) Failed to write file to disk. Introduced in PHP 5.1.0.";
            break;

        case UPLOAD_ERR_EXTENSION:
            $errorMessage = "($errorCode) A PHP extension stopped the file upload.";
            break;

        default:
            $errorMessage = "($errorCode) No description available.";
            break;
    }

    return $errorMessage;
}

function getEmailAccount($idAccount){

    dbConnect( ConfigFile);
            
    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db( $GLOBALS['ligacao'], $dataBaseName );

    $queryString = "SELECT * FROM `$dataBaseName`.`email-accounts` WHERE `id`='$idAccount'";

    $result = mysqli_query( $GLOBALS['ligacao'], $queryString );

    $account = mysqli_fetch_array( $result );

    mysqli_free_result($result);

    dbDisconnect();

    return $account;
}

function getXdebugArg() {
  $method = $_SERVER['REQUEST_METHOD'];
  
  if ($method == 'POST') {
    $args = $_POST;
  } elseif ($method == 'GET') {
    $args = $_GET;
  }

 foreach ($args as $key => $value) {
    if ( $key==="XDEBUG_SESSION_START" ) {
      return "XDEBUG_SESSION_START=$value";
    }
  }
  
  return null;
}

function getXdebugArgAsArray() {
  $method = $_SERVER['REQUEST_METHOD'];
  
  if ($method == 'POST') {
    $args = $_POST;
  } elseif ($method == 'GET') {
    $args = $_GET;
  }

 foreach ($args as $key => $value) {
    if ( $key==="XDEBUG_SESSION_START" ) {
      return array( "key" => $key, "value" => $value);
    }
  }
  
  return null;
}

?>