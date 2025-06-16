<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php");
    require_once( "lib/HtmlMimeMail.php" );
    
    $redirectURL = $_POST['redirect'] ?? 'index.php'; // Se não existir, volta para index.php

    $id = $_GET['id'] ?? '';

    // Pega dados do formulário pelo método POST
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $mainSubject = $_POST['subject'] ?? '';
    $Message = $_POST['message'] ?? '';

    $Subject = "Artchive: New Email From $email: $mainSubject";


    //email remetende: artchive
    $emailAccount = getEmailAccount(1);
    $smtpServer = $emailAccount[ 'smtpServer' ];
    $port = intval( $emailAccount[ 'port' ] );
    $useSSL = boolval( $emailAccount[ 'useSSL' ] );
    $timeout = intval( $emailAccount[ 'timeout' ] );
    $loginName = $emailAccount[ 'loginName' ];
    $password = $emailAccount[ 'password' ];
    $fromEmail = $emailAccount[ 'email' ];
    $fromName = $emailAccount[ 'displayName' ];

    //Dados do destinatário
    $ToName = getUserData( $id )['name'];
    $ToEmail = getUserAuthData( $id )["email"];

    //URL de verificação
    $flags[] = FILTER_NULL_ON_FAILURE;
    $serverName = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_UNSAFE_RAW, $flags);
    $serverPort = 80;
    $appname = webAppName();
    $token = getTokenFromUser($id)['token'];

    $VerificationURL = "http://" . $serverName . ":" . $serverPort . $appname . "accountVerification.php?token=" . urlencode($token);
    $mail = new HtmlMimeMail();
    $MessageHTML = <<<EOD
            <html>
                <body style="background: url('background.gif') repeat; padding: 20px; font-family: Verdana, Arial;">
                    <h3 style="color:rgb(0, 0, 0); margin-bottom: 20px;">
                    $Message
                    </h3>
                </body>
            </html>
EOD;

    $mail->add_html($MessageHTML, $Message);
    $mail->build_message();
    $result = $mail->send(
        $smtpServer,
        $useSSL,
        $port,
        $loginName,
        $password,
        $ToName,
        $ToEmail,
        $fromName,
        $fromEmail,
        $Subject,
        "X-Mailer: Html Mime Mail Class"
    );


    if ($result == true) {
        $userMessage = "was";
    } else {
        $userMessage = "could not be";
    }

    header("Location: $redirectURL");
    exit();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
        <title>Send an e-mail using PHP SMTP library</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <link REL="stylesheet" TYPE="text/css" href="../Styles/GlobalStyle.css">
    </head>
    <body>
        <p>E-mail <?php echo $userMessage;?> delivered to e-mail server.</p>
        
        <hr>
        <br>
        <a href="<?php echo 'index.php'?>">Back</a>
    </body>
</html>

