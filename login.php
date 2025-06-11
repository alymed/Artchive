<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $secretKey = '6LcQV10rAAAAAPHRfN1r_AIwv0PJR69kA5NPNpIk';
    $captchaResponse = $_POST['g-recaptcha-response'];

    // Verify the CAPTCHA response with Google
    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $captchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    // Use cURL to send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $verifyUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $apiResponse = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($apiResponse);

    if ($responseData->success) {
        // CAPTCHA was successful – proceed with user registration
        echo "User validated and can be registered.";
    } else {
        // CAPTCHA failed
        echo "CAPTCHA verification failed. Please try again.";
    }
}
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
        
        $isVerified = intval(getUserAuthData($idUser)['status']);

        if ( $isVerified == 2) {

            session_start();
            $_SESSION['id'] = $idUser;

            if (isset($_SESSION['locationAfterAuth'])) {
                $nextUrl = $_SESSION['locationAfterAuth'];
            } else {
                $nextUrl = "app.php";
            }
        } else {
            header("Location: " . $baseNextUrl . "index.php?loginError=AccountNotVerified");
            exit;
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

