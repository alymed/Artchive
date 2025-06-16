<?php 

 
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("lib/lib.php");

$idUser = $_SESSION["id"];


$flags[] = FILTER_NULL_ON_FAILURE;
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_UNSAFE_RAW, $flags);

// Redireciona se não for POST
if ($method !== 'POST') {
    header('Location: index.php');
    exit();
}

$_INPUT_METHOD = INPUT_POST;

$strIdCategories = filter_input($_INPUT_METHOD, 'selected_categories', FILTER_UNSAFE_RAW);

$idCategories = array_map('trim', explode(',', $strIdCategories));

foreach ($idCategories as $idCategory) {
    $userTagId = addUserTag($idUser, $idCategory);

    if($userTagId > 0){
        setNotNew($idUser);
        header('Location: app.php');
    }else{
        header('Location: app.php');
    }
}


?>