<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("lib/lib.php");
require_once("lib/db.php");

$flags[] = FILTER_NULL_ON_FAILURE;
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_UNSAFE_RAW, $flags);

// Redireciona se não for POST
if ($method !== 'POST') {
    header('Location: index.php');
    exit();
}

$_INPUT_METHOD = INPUT_POST;

$email = filter_input($_INPUT_METHOD, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input($_INPUT_METHOD, 'password', FILTER_UNSAFE_RAW);
$captchaResponse = $_POST['g-recaptcha-response'] ?? '';

// Validar dados do formulário
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = "Email inválido.";
    header('Location: index.php#loginForm');
    exit();
}

if (empty($password) || strlen($password) < 8) {
    $_SESSION['login_error'] = "A senha deve ter pelo menos 8 caracteres.";
    header('Location: index.php#loginForm');
    exit();
}

if (empty($captchaResponse)) {
    $_SESSION['login_error'] = "Por favor, confirme que você não é um robô.";
    header('Location: index.php#loginForm');
    exit();
}

// Verificar reCAPTCHA com Google
$secretKey = '6LcQV10rAAAAAPHRfN1r_AIwv0PJR69kA5NPNpIk';
$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret' => $secretKey,
    'response' => $captchaResponse,
    'remoteip' => $_SERVER['REMOTE_ADDR']
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $verifyUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$apiResponse = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($apiResponse);

if (!$responseData || !$responseData->success) {
    $_SESSION['login_error'] = "Falha na verificação do reCAPTCHA. Tente novamente.";
    header('Location: index.php#loginForm');
    exit();
}

// Validar credenciais
$idUser = isValid($email, $password);

$serverName = filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_UNSAFE_RAW, $flags);
$serverPort = 80;
$name = webAppName();
$baseNextUrl = "http://" . $serverName . ":" . $serverPort . $name;

if ($idUser > 0) {
    $isVerified = intval(getUserAuthData($idUser)['status']);

    if ($isVerified === 2 || $isVerified === 3) {
        $_SESSION['id'] = $idUser;
        $nextUrl = $_SESSION['locationAfterAuth'] ?? "app.php";
        header("Location: " . $baseNextUrl . $nextUrl);
        exit();
    } else {
        $_SESSION['login_error'] = "Sua conta ainda não foi verificada.";
        header("Location: " . $baseNextUrl . "index.php#loginForm");
        exit();
    }
} else {
    $_SESSION['login_error'] = "Credenciais inválidas.";
    header("Location: " . $baseNextUrl . "index.php#loginForm");
    exit();
}
?>
