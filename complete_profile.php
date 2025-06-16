<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("lib/lib.php");

$flags[] = FILTER_NULL_ON_FAILURE;
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_UNSAFE_RAW, $flags);

if ($method != 'POST') {
    header('Location: index.php');
    exit();
}

$name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW, $flags);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL, $flags);
$birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_UNSAFE_RAW, $flags);
$user_type = filter_input(INPUT_POST, 'user_type', FILTER_UNSAFE_RAW, $flags);
$username = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW, $flags);
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW, $flags);
$biography = filter_input(INPUT_POST, 'biography', FILTER_UNSAFE_RAW, $flags);
$biography = $biography ? trim($biography) : '';

$serverName = filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_UNSAFE_RAW, $flags);
$serverPort = 80;
$appname = webAppName();
$baseUrl = "http://" . $serverName . ":" . $serverPort;
$baseNextUrl = $baseUrl . $appname;

if (!$name || !$email || !$birthdate || !$user_type || !$username || !$password) {
    header("Location: index.php?error=MissingDataname");
    exit();
}

if (strlen($biography) > 90) {
    header("Location: signupForm3.php?error=BiographyTooLong");
    exit();
}

$idUser = register($name, $username, $password, $email, $birthdate, $user_type);

if ($idUser <= 0) {
    header("Location: signupForm2.php?error=RegisterError");
    exit();
}

try {
    updateUserProfile($idUser, 1, $biography);
} catch (Exception $e) {
    echo $e->getMessage();
}

header("Location: email.php?id=" . urlencode($idUser));
exit();
?>