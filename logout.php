<?php

session_start();

if (!isset($_SESSION['id'])) {
    
    header('Location: index.php');
    exit();
}
?>


<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php" );

    logout();

?>