<?php 

/************ CHECK IF USER IS AUTHORIZED ************/

$isAuthorized = false;
    
if (defined('MAINTENANCE_IP_AUTHORIZATION'))
{
    $authorizations = explode(',', MAINTENANCE_IP_AUTHORIZATION);

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else if (isset($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $isAuthorized = in_array($ip, $authorizations);
}

if ($isAuthorized)
{
    // User is authorized
    return;
}

/************ APPLY MAINTENANCE MODE ************/

$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
$status = $protocol == 'HTTP/1.1' ? '307 Temporary Redirect' : '302 Found';

header($protocol . ' ' . $status);
header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

ob_start();
?>

<!DOCTYPE html>
    <head>
        <title>Maintenance</title>
        <style type="text/css">
            body
            {
                font-family: Arial, Helvetica, sans-serif;
            }
            
            .container
            {
                max-width: 800px;
                margin: 50px auto 25px auto;
            }
			
            .container h1
            {
                color: #333;
                text-align:center;
            }
            
            .bloc
            {
                margin: 100px 15px 25px 15px;
                padding: 15px;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
			
            .bloc .title
            {
                color: #333;
                text-align:center;
                margin-bottom: 50px;
            }
            
            .bloc p
            {
                padding: 15px;
                border: 1px solid transparent;
                border-radius: 4px;
                text-align:center;
            }
            
            .bloc p.infos
            {
                background-color: #d9edf7;
                border-color: #bce8f1;
                color: #31708f;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Maintenance mode</h1>

            <div class="bloc">
                <h3 class="title">The server is temporarily down for maintenance</h3>
                <p class="infos">
                    The service needed to be locked to ensure data integrity during the update procedure.
                    <br />
                    <br />
                    We will resume normal operations as soon as possible.
                </p>
            </div>
        <div>
    </body>
</html>

<?php

$content = ob_get_clean();
echo $content;
exit();
