<?php


    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php" );
    require_once( "lib/db.php" );

    
    $flags[] = FILTER_NULL_ON_FAILURE;
    $method = filter_input( INPUT_SERVER, 'REQUEST_METHOD', FILTER_UNSAFE_RAW, $flags);
    
    if ( $method=='POST') {
        $_INPUT_METHOD = INPUT_POST;
    } elseif ( $method=='GET' ) {
        header('Location: index.php');
        exit();
    }
    else {
        header('Location: index.php');
        exit();
    }
    
    $flags[] = FILTER_NULL_ON_FAILURE;

    $email = filter_input( $_INPUT_METHOD, 'email', FILTER_UNSAFE_RAW, $flags);
    $password = filter_input( $_INPUT_METHOD, 'password', FILTER_UNSAFE_RAW, $flags);

    $serverName = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_UNSAFE_RAW, $flags);
    $serverPort = 80;
    $name = webAppName();

    $baseNextUrl = "http://" . $serverName . ":" . $serverPort . $name;

    $idUser = isValid($email, $password);

      
    if ( $idUser>0 ) {
        
       session_start();
        $_SESSION['id'] = $idUser;

        if (isset($_SESSION['locationAfterAuth'])) {
            $nextUrl = $_SESSION['locationAfterAuth'];
        } else {
            $nextUrl = "app.php";
        }

    } else {
        header("Location: " . $baseNextUrl . "index.php?loginError=WrongCredentials");
        exit;
    }

    header( "Location: " . $baseNextUrl . $nextUrl );
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1> <?php echo $a ?></h1>
</body>
</html>

