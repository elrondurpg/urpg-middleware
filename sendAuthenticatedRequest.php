<?php
    header('Access-Control-Allow-Origin: ' . $_SERVER['FRONTEND_ORIGIN']); 
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

    include_once 'startSecureSession.php';
    include_once 'sendRequest.php';

    $input = json_decode(file_get_contents('php://input'), true);
	
	$id = null;
	$accessToken = null;
	if ($input != null) {
		$rand = rand(100000000, 999999999);
		error_log($rand);
		sec_session_start($rand . ", " . $input['url']);
		if (isset($_SESSION)) {
			if (array_key_exists('accessToken', $_SESSION)) {
				$accessToken = $_SESSION['accessToken'];
			}
	
			if (array_key_exists('id', $_SESSION)) {
				$id = $_SESSION['id'];
			}
		}
	
		$response = sendRequest($input['method'], $input['url'], $id, $accessToken, $input['payload']);
	
		echo $response;
	}
?>