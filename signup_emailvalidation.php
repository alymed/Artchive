<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php" );
    
    $flags[] = FILTER_NULL_ON_FAILURE;
    $method = filter_input( INPUT_SERVER, 'REQUEST_METHOD', FILTER_UNSAFE_RAW, $flags);
    
    if ($method != 'POST') {
    header('Location: index.php');
    exit();
    }
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL, $flags);
    $name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW, $flags);
    $birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_UNSAFE_RAW, $flags);

    $serverName = filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_UNSAFE_RAW, $flags);
    $serverPort = 80;
    $appname = webAppName();
    $baseUrl = "http://" . $serverName . ":" . $serverPort;
    $baseNextUrl = $baseUrl . $appname;

    if(filter_var( $email, FILTER_VALIDATE_EMAIL)  && $name != null && $name != "" && 
        $birthdate != null && $birthdate != ""
    ){
    
        if (strlen($name) < 2 || strlen($name) > 50 || !preg_match('/^[\p{L}\s\-\'\.]+$/u', $name)) {
            header("Location: " . $baseNextUrl . "index.php?signupError=InvalidName");
            exit();
        }

        $birthDateObj = DateTime::createFromFormat('Y-m-d', $birthdate);
        if (!$birthDateObj || $birthDateObj->format('Y-m-d') !== $birthdate) {
            header("Location: " . $baseNextUrl . "index.php?signupError=InvalidBirthdate");
            exit();
        }

        $today = new DateTime();
        if ($birthDateObj > $today) {
            header("Location: " . $baseNextUrl . "index.php?signupError=FutureBirthdate");
            exit();
        }

        $age = $today->diff($birthDateObj)->y;
        if ($age < 13) {
            header("Location: " . $baseNextUrl . "index.php?signupError=AgeTooYoung");
            exit();
        }

        $emailExists = existUserField("email", $email, "users-auth");

        if (!$emailExists) {
            header("Location: " . $baseNextUrl . "index.php?signupStep=2&email=" . urlencode($email) . 
                "&name=" . urlencode($name) . "&birthdate=" . urlencode($birthdate));
            exit();
        } else {
            header("Location: " . $baseNextUrl . "index.php?signupError=EmailInUse");
            exit();
        }

    } else {
       
    header("Location: " . $baseNextUrl . "index.php?signupError=MissingFields");
    exit();
}
?>