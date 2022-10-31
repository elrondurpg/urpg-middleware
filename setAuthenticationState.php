<?php
    header('Access-Control-Allow-Origin: ' . $_SERVER['FRONTEND_ORIGIN']); 
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
    include_once 'startSecureSession.php';

    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input) && array_key_exists('state', $input)) {
		$rand = rand(100000000, 999999999);
		error_log($rand);
        sec_session_start($rand, false);
        $_SESSION['state'] = $input['state'];
        if (array_key_exists('returnUrl', $input)) {
                $_SESSION['returnUrl'] = $input['returnUrl'];
        }
        http_response_code(200);
        //print_r(json_encode(array('message' => "Login successful")));
    }
?>