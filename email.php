<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php");
    require_once( "lib/HtmlMimeMail.php" );




    $emailAccount = getEmailAccount(1);
        
    $smtpServer = $emailAccount[ 'smtpServer' ];
    $port = intval( $emailAccount[ 'port' ] );
    $useSSL = boolval( $emailAccount[ 'useSSL' ] );
    $timeout = intval( $emailAccount[ 'timeout' ] );
    $loginName = $emailAccount[ 'loginName' ];
    $password = $emailAccount[ 'password' ];
    $fromEmail = $emailAccount[ 'email' ];
    $fromName = $emailAccount[ 'displayName' ];

    $Subject = "Email Verification";

    $id = $_GET['id'];
    $ToName = getUserData( $id )['name'];
    $ToEmail = getUserAuthData( $id )["email"];

        
    $Message = "Artchive!";

    $flags[] = FILTER_NULL_ON_FAILURE;
    $serverName = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_UNSAFE_RAW, $flags);
    $serverPort = 80;
    $appname = webAppName();
    $token = getTokenFromUser($id)['token'];

    $VerificationURL = "http://" . $serverName . ":" . $serverPort . $appname . "accountVerification.php?token=" . urlencode($token);

    /*
    * Create the mail object.
    */
    $mail = new HtmlMimeMail();

    /*
    * HTML component of the e-mail
    */
    $MessageHTML = <<<EOD
            <html>
                <body style="background: url('background.gif') repeat; padding: 20px; font-family: Verdana, Arial;">
                    <h1 style="color:rgb(0, 0, 0); margin-bottom: 20px;">
                    $Message
                    </h1>

                    <a href="$VerificationURL" style="text-decoration: none;">
                    <button style="
                        background-color:rgb(33, 83, 170);
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        font-size: 16px;
                        cursor: pointer;
                        border-radius: 5px;
                    ">
                        Verify Your Email
                    </button>
                    </a>
                </body>
            </html>
EOD;
    /*
    * Add the text, html and embedded images.
    */
    $mail->add_html($MessageHTML, $Message);



    /*
    * Builds the message.
    */
    $mail->build_message();

    /*
    * Sends the message.
    */
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

