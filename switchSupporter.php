<?php

    session_start();
    $idUser = $_SESSION['user_id'] ?? null;

    require_once( "lib/lib.php" );
    
    dbConnect(ConfigFile);

    $dataBaseName = $GLOBALS['configDataBase']->db;

    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);

    $idUser = mysqli_real_escape_string($GLOBALS['ligacao'], $idUser);
    $query = "SELECT `user_type` FROM `$dataBaseName`.`users-profile` WHERE `id` = '$idUser'";
    $result = mysqli_query($GLOBALS['ligacao'], $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        dbDisconnect();
        die("User not found.");
    }

    $row = mysqli_fetch_assoc($result);
    $currentType = $row['user_type'];

    mysqli_free_result($result);

    if ($currentType === 'supporter') {
        dbDisconnect();
        header("Location: profile.php?message=Already a supporter");
        exit;
    } else if ($currentType !== 'supporter'){

    $updateQuery = "UPDATE `$dataBaseName`.`users-profile` SET `user_type` = 'supporter' WHERE `id` = '$idUser'";
    $success = mysqli_query($GLOBALS['ligacao'], $updateQuery);
    }
    dbDisconnect();

    if ($success) {
        header("Location: profile.php?message=Now a supporter!");
    } else {
        die("Failed to update user type.");
    }
?>