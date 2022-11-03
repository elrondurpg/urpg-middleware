<?php
    include_once 'serverParams.php';
    include_once 'startSecureSession.php';
    
    header('Access-Control-Allow-Origin: ' . URPG_WEBAPPS_URL_FULL); 
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input) && array_key_exists('state', $input)) {
        sec_session_start(false);
        $_SESSION['state'] = $input['state'];
        if (array_key_exists('returnUrl', $input)) {
                $_SESSION['returnUrl'] = $input['returnUrl'];
        }

        $loginRequest = array(
            'oauth2Url' => OAUTH2_URL,
            'oauth2ResponseType' => OAUTH2_RESPONSE_TYPE,
            'oauth2ClientId' => OAUTH2_CLIENT_ID,
            'oauth2Scope' => OAUTH2_SCOPE,
            'oauth2RedirectUri' => OAUTH2_REDIRECT_URI
        );

        echo json_encode($loginRequest);

        http_response_code(200);
    }
?>