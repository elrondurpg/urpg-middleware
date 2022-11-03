<?php
    header('Access-Control-Allow-Origin: ' . URPG_WEBAPPS_URL_FULL); 
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

    include_once 'serverParams.php';
    include_once 'startSecureSession.php';
    include_once 'sendRequest.php';

    $input = json_decode(file_get_contents('php://input'), true);
	
	$id = null;
	$accessToken = null;
	if ($input != null) {
		sec_session_start();
		if (isset($_SESSION)) {
			if (array_key_exists('accessToken', $_SESSION)) {
				$accessToken = $_SESSION['accessToken'];
			}
	
			if (array_key_exists('id', $_SESSION)) {
				$id = $_SESSION['id'];
			}
		}
	
		$firstUrlResourceChar = substr($input['url'], 0, 1);
		if ($firstUrlResourceChar != '/') {
			$input['url'] = '/' . $input['url'];
		}

		$fullUrl = URPG_SERVER_URL_FULL . $input['url'];

		$response = sendRequest($input['method'], $fullUrl, $id, $accessToken, $input['payload']);
	
		echo $response;
	}
?>