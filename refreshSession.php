<?php
    header('Access-Control-Allow-Origin: ' . $_SERVER['FRONTEND_ORIGIN']); 
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
	
    include_once 'startSecureSession.php';

    $rand = rand(100000000, 999999999);
    error_log($rand);
    sec_session_start($rand . ", refresh", true);
    if (isset($_SESSION)) {
        if (array_key_exists('accessToken', $_SESSION) &&
            array_key_exists('id', $_SESSION) &&
            array_key_exists('username', $_SESSION)) 
        {
            http_response_code(200);
            print_r(json_encode(array('username' => $_SESSION['username'])));
        }
        else {
            http_response_code(200);
            print_r(json_encode(array('username' => null)));
        }
    }
    else {
        http_response_code(200);
        print_r(json_encode(array('username' => null)));
    }
?>