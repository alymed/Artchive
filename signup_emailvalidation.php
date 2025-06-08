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

    $email = filter_input( $_INPUT_METHOD, 'email', FILTER_SANITIZE_EMAIL, $flags);
    $name = filter_input( $_INPUT_METHOD, 'name', FILTER_UNSAFE_RAW, $flags);
    $birthdate = filter_input( $_INPUT_METHOD, 'birthdate', FILTER_UNSAFE_RAW, $flags);

    if(filter_var( $email, FILTER_VALIDATE_EMAIL)  && $name != null && $name != "" && 
        $birthdate != null && $birthdate != ""
    ){


        
        $serverName = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_UNSAFE_RAW, $flags);

        $serverPort = 80;

        $appname = webAppName();

        $baseUrl = "http://" . $serverName . ":" . $serverPort;

        $baseNextUrl = $baseUrl . $appname;
        

        $emailExists = existUserField("email", $email, "users-auth");

        $a = 3;

        if(!$emailExists){

           header("Location: " . $baseNextUrl . "index.php?signupStep=2&email=" . urlencode($email) . 
            "&name=" . urlencode($name) . 
            "&birthdate=" . urlencode($birthdate));

        }else {

            header("Location: ". $baseNextUrl . "index.php?signupError=EmailInUse");
        }

    }


  
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1><?php echo $a ?></h1>
</body>
</html>
