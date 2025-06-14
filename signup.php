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

// Get data from previous step (either from POST or GET parameters)
$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL, $flags) ?? 
         filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL, $flags);
$name = filter_input(INPUT_GET, 'name', FILTER_UNSAFE_RAW, $flags) ?? 
        filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW, $flags);
$birthdate = filter_input(INPUT_GET, 'birthdate', FILTER_UNSAFE_RAW, $flags) ?? 
             filter_input(INPUT_POST, 'birthdate', FILTER_UNSAFE_RAW, $flags);

// Get current step data
$username = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW, $flags);
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW, $flags);

$serverName = filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_UNSAFE_RAW, $flags);
$serverPort = filter_input(INPUT_SERVER, 'SERVER_PORT', FILTER_VALIDATE_INT, $flags) ?? 80;
$appname = webAppName();
$baseUrl = "http://" . $serverName . ":" . $serverPort;
$baseNextUrl = $baseUrl . $appname;

// Validate all required fields are present
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || 
    empty($password) || 
    empty($name) || 
    empty($username) || 
    empty($birthdate)) {
    
    header("Location: " . $baseNextUrl . "index.php?signupError=MissingFields&signupStep=2&email=" . 
           urlencode($email) . "&name=" . urlencode($name) . "&birthdate=" . urlencode($birthdate));
    exit();
}

// Validate username format
if (!preg_match('/^[a-zA-Z0-9_]+$/', $username) || strlen($username) < 3 || strlen($username) > 30) {
    header("Location: " . $baseNextUrl . "index.php?signupError=InvalidUsername&signupStep=2&email=" . 
           urlencode($email) . "&name=" . urlencode($name) . "&birthdate=" . urlencode($birthdate));
    exit();
}

// Validate password strength (minimum 8 characters, at least one letter and one number)
if (strlen($password) < 8 || !preg_match('/^(?=.*[A-Za-z])(?=.*\d)/', $password)) {
    header("Location: " . $baseNextUrl . "index.php?signupError=WeakPassword&signupStep=2&email=" . 
           urlencode($email) . "&name=" . urlencode($name) . "&birthdate=" . urlencode($birthdate));
    exit();
}

// ** Check if username is already in use **
$userExists = existUserField("username", $username, "users-profile");
if (!$userExists) {
    $redirectUrl = "index.php?signupStep=3&name=" . urlencode($name) . 
               "&email=" . urlencode($email) . 
               "&birthdate=" . urlencode($birthdate) . 
               "&username=" . urlencode($username) . 
               "&password=" . urlencode($password);
    header("Location: $redirectUrl");
    exit();
} else {
        header("Location: " . $baseNextUrl. "index.php?signupError=UsernameInUse");
}

?>
