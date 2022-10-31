<?php
    header('Access-Control-Allow-Origin: ' . $_SERVER['FRONTEND_ORIGIN']); 
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
    include_once 'startSecureSession.php';

    sec_session_start(null);
	
	$id = null;
	$accessToken = null;
	
	if (isset($_SESSION)) {
		if (array_key_exists('accessToken', $_SESSION)) {
			$accessToken = $_SESSION['accessToken'];
		}

		if (array_key_exists('id', $_SESSION)) {
			$id = $_SESSION['id'];
		}
	}
	
    $response = sendRequest("POST", SERVICE_HOST . "/session/logout", $id, $accessToken, null);

    session_unset();
    session_destroy();
    session_write_close();
?>