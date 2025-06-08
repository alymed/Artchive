<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php");

    $token = $_GET['token'];

    $tokenData = getTokenDataFromToken($token);

    $id = $tokenData['id'];

    accountVerifyDB($id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account verified!</title>
</head>
<body>
    <h1> Account is now verified! </h1>
</body>
</html>