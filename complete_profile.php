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

// Recebe os dados do formulário do passo anterior
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

// Chama função register para criar usuário e obter ID
$idUser = register($name, $username, $password, $email, $birthdate, $user_type);

if ($idUser <= 0) {
    header("Location: signupForm2.php?error=RegisterError");
    exit();
}

// Faz upload da foto de perfil (se houver)
$profilePicturePath = null;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/profile_pictures/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $fileInfo = pathinfo($_FILES['profile_picture']['name']);
    $extension = strtolower($fileInfo['extension']);
    
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($extension, $allowedTypes)) {
        $filename = 'profile_' . $idUser . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;
        
        if (strlen($uploadPath) <= 255 && move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
            $profilePicturePath = $uploadPath;
        } else {
            header("Location: signupForm3.php?error=FileUploadError");
            exit();
        }
    } else {
        header("Location: signupForm3.php?error=InvalidFileType");
        exit();
    }
}

// Atualiza perfil com foto e biografia
try {
    updateUserProfile($idUser, $profilePicturePath, $biography);
} catch (Exception $e) {
    if ($profilePicturePath && file_exists($profilePicturePath)) {
        unlink($profilePicturePath);
    }
    header("Location: signupForm3.php?error=ProfileUpdateError");
    exit();
}

// Redireciona para página de verificação de email
header("Location: email.php?id=" . urlencode($idUser));
exit();
?>

