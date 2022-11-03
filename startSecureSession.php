<?php
    include_once 'serverParams.php';
    include_once 'sendRequest.php';

    function setSessionParams($session) {
        if (isset($session)) {
            if (array_key_exists('accessToken', $session)) {
                $_SESSION['accessToken'] = $session['accessToken'];
            }

            if (array_key_exists('id', $session)) {
                $_SESSION['id'] = $session['id'];
            }

            if (array_key_exists('username', $session)) {
                $_SESSION['username'] = $session['username'];
            }

            $_SESSION['lock'] = false;
        }
    }

    function sec_session_start($shouldRefreshAPISession = false) {
        session_name("cookie_" . str_replace(".", "_", DOMAIN));
        exit_if_cookies_disabled();
        set_cookie_params();
        
        session_start();

        $waitTime = 0;
        while(array_key_exists('lock', $_SESSION) && $_SESSION['lock'] && $waitTime < 5) {
            sleep(1);
            $waitTime++;
        }
        if ($waitTime >= 5) {
            $_SESSION['lock'] = false;
        }

        if ($shouldRefreshAPISession) {
            $_SESSION['lock'] = true;
        }

        if (isset($shouldRefreshAPISession) && $shouldRefreshAPISession == true) {
            refreshAPISession();
        }
    }

    function refreshAPISession() {
        $method = "POST";
        $url = URPG_SERVER_URL_FULL . "/session/refresh";
	
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

        $response = sendRequest($method, $url, $id, $accessToken, null);
        $response = json_decode($response, true);

        if (isset($response) && (!isset($response['status']) || $response['status'] == 200)) {
            setSessionParams($response);
        }
        else {
            if (isset($_SESSION)) {
                unset($_SESSION['username']);
                unset($_SESSION['id']);
                unset($_SESSION['accessToken']);
            }
        }
    }

    function exit_if_cookies_disabled() {
        if (ini_set('session.use_only_cookies', 1) === FALSE) {
            header("Location: /php/error.php?err=Could not initiate a safe session (ini_set)");
            exit();
        }
    }

    function set_cookie_params() {
        $cookieParams = session_get_cookie_params();
        $lifetime = $cookieParams["lifetime"];
        $path = $cookieParams["path"];
        $secure = $_SERVER['USE_SECURE_MODE'];
        $httponly = false;
        session_set_cookie_params($lifetime, $path, DOMAIN, $secure, $httponly);
    }
?>