<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php" );

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
    $name = filter_input( $_INPUT_METHOD, 'name', FILTER_UNSAFE_RAW, $flags);
    $username = filter_input( $_INPUT_METHOD, 'username', FILTER_UNSAFE_RAW, $flags);
    $birthdate = filter_input( $_INPUT_METHOD, 'birthdate', FILTER_UNSAFE_RAW, $flags);

    if(filter_var( $email, FILTER_VALIDATE_EMAIL ) && $password != null && $password != "" &&
        $name != null && $name != "" && $username != null && $username != "" && $birthdate != null && $birthdate != ""
       
    ){
        $a = 3;
        $serverName = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_UNSAFE_RAW, $flags);
        $serverPort = 80;
        $appname = webAppName();
        $baseUrl = "http://" . $serverName . ":" . $serverPort;
        $baseNextUrl = $baseUrl . $appname;
        $userExists = existUserField("username", $username, "users-profile");

        if ( !$userExists ) {
            $idUser = register($name, $username, $password, $email, $birthdate);
     
            if ($idUser > 0) {
                $nextUrl = "email.php?id=" . urlencode($idUser);
            } else {
                header("Location: " . $baseNextUrl. "index.php?signupError=RegisterError");
            }

        } else {
            header("Location: " . $baseNextUrl. "index.php?signupError=UsernameInUse");
        }

    } else {
        
        header("Location: " . $baseNextUrl. "index.php?signupError=InvalidInputs");
    }

    redirectToPage($nextUrl, "Signup Success", "Verification email sent! Verify your email. ", 1);
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