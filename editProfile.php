<?php

session_start();

if (!isset($_SESSION['id'])) {

    header('Location: index.php');
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("lib/lib.php");


$flags[] = FILTER_NULL_ON_FAILURE;
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_UNSAFE_RAW, $flags);

if ($method == 'POST') {
    $_INPUT_METHOD = INPUT_POST;
} elseif ($method == 'GET') {
    $_INPUT_METHOD = INPUT_GET;
} else {
    echo "Invalid HTTP method (" . $method . ")";
    exit();
}

$flags[] = FILTER_NULL_ON_FAILURE;

$name = trim(strip_tags(filter_input($_INPUT_METHOD, 'name', FILTER_UNSAFE_RAW)));
$username = trim(strip_tags(filter_input($_INPUT_METHOD, 'username', FILTER_UNSAFE_RAW)));
$bio = trim(strip_tags(filter_input($_INPUT_METHOD, 'bio', FILTER_UNSAFE_RAW)));


if ($name != null && $name != "" && $username != null && $username != "" && $bio != null) {

    $bio = empty($bio) ? null : $bio;

    $idUser = $_SESSION['id'];

    $userData = getUserData($idUser);

    $name = ($name === $userData['name']) ? "" : $name;
    $username = ($username === $userData['username']) ? "" : $username;
    $bio = ($bio === $userData['biography']) ? "" : $bio;

    $usernameExists = existUserField("username", $username, "users-profile");

    if (!empty($username) && $usernameExists) {
        redirectToLastPage("Failed", "Username already in use!", 3);
        exit;
    } else {
        if (editProfile($idUser, $name, $username, $bio) >= 0) {
            redirectToLastPage("", "", 0);
            exit;
        } else {
            redirectToLastPage("Failed", "Profile Edit Failed", 3);
            exit;
        }
    }


} else {
    redirectToLastPage("Failed", "Invalid Inputs", 3);
    exit;
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
    <h1> <?php echo $bio ?> </h1>
</body>

</html>