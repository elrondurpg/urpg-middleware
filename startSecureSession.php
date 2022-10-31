<?php
    include_once 'sendRequest.php';
    define("PROTOCOL", $_SERVER['WEB_PROTOCOL']);
    define("HOST", $_SERVER['WEB_HOST']);
    define("PORT", $_SERVER['FRONTEND_PORT']);
    define("SERVICE_HOST", PROTOCOL . HOST . ":" . $_SERVER['WEB_PORT']);
    define("SESSION_NAME", "urpg-session" . $_SERVER['SESSION_TAG']);

    function setSessionParams($session, $message) {
        if (isset($session)) {
            if (array_key_exists('accessToken', $session)) {
                //error_log($message . ": " . session_id() . ": " . microtime(true) . ": about to set access token param for session; access token = " . $session['accessToken']);
                $_SESSION['accessToken'] = $session['accessToken'];
                //error_log($message . ": " . session_id() . ": " . microtime(true) . ": finished setting access token param for session; access token = " . $_SESSION['accessToken']);
            }

            if (array_key_exists('id', $session)) {
                $_SESSION['id'] = $session['id'];
            }

            if (array_key_exists('username', $session)) {
                $_SESSION['username'] = $session['username'];
            }

            //error_log($message . ": " . session_id() . ": " . microtime(true) . ": unlocking the session.");
            $_SESSION['lock'] = false;
        }
    }

    function sec_session_start($message, $shouldRefreshAPISession = false) {
        session_name(SESSION_NAME);
        exit_if_cookies_disabled();
        set_cookie_params($message);
        //error_log($message . ": " . session_id() . ": " . microtime(true) . ": about to session_start");

        session_start();

        $waitTime = 0;
        while(array_key_exists('lock', $_SESSION) && $_SESSION['lock'] && $waitTime < 5) {
            //error_log($message . ": " . session_id() . ": " . microtime(true) . ": session is locked. Waiting...");
            sleep(1);
            $waitTime++;
        }
        //error_log($message . ": " . session_id() . ": " . microtime(true) . ": session is unlocked.");
        if ($waitTime >= 5) {
            $_SESSION['lock'] = false;
        }

        if ($shouldRefreshAPISession) {
            //error_log($message . ": " . session_id() . ": " . microtime(true) . ": locking the session.");
            $_SESSION['lock'] = true;
        }

        if (isset($shouldRefreshAPISession) && $shouldRefreshAPISession == true) {
            //error_log($message . ": " . session_id() . ": " . microtime(true) . ": about to session_regenerate_id");
            //session_regenerate_id();
            refreshAPISession($message);
            /* // if (array_key_exists("accessToken", $_SESSION)) {
                error_log($message . ": " . session_id() . ": " . microtime(true) . ": refreshed a session with access token " . $_SESSION['accessToken']);
            } //*/
        }
        /* //else if (array_key_exists("accessToken", $_SESSION)) {
            error_log($message . ": " . session_id() . ": " . microtime(true) . ": started a session with access token " . $_SESSION['accessToken']);
        }
        else {
            error_log($message . ": " . session_id() . ": " . microtime(true) . ": found a blank access token");
        } // */
    }

    function refreshAPISession($message) {
        $method = "POST";
        $url = SERVICE_HOST . "/session/refresh";
	
		$id = null;
		$accessToken = null;
		if (isset($_SESSION)) {
			if (array_key_exists('accessToken', $_SESSION)) {
				$accessToken = $_SESSION['accessToken'];
                error_log($message . ": " . session_id() . ": " . microtime(true) . ": about to refresh a session with access token " . $_SESSION['accessToken']);
			}
            /* //else {
                error_log($message . ": " . session_id() . ": " . microtime(true) . ": about to refresh a session with blank access token");
            }// */

			if (array_key_exists('id', $_SESSION)) {
				$id = $_SESSION['id'];
			}
		}
        /* //else {
            error_log($message . ": " . session_id() . ": " . microtime(true) . ": about to refresh a session with blank session");
        } // */

        $response = sendRequest($method, $url, $id, $accessToken, null);
        $response = json_decode($response, true);

        if (isset($response) && (!isset($response['status']) || $response['status'] == 200)) {
            //error_log($message . ": " . session_id() . ": " . microtime(true) . ": about to set params for session");
            setSessionParams($response, $message);
        }
        else {
            //error_log($message . ": " . session_id() . ": " . microtime(true) . ": about to unset a session");
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

    function set_cookie_params($message) {
        $cookieParams = session_get_cookie_params();
        $lifetime = $cookieParams["lifetime"];
        $path = $cookieParams["path"];
        $secure = $_SERVER['WEB_SECURE'];
        $httponly = false;
        //error_log($message . ": " . session_id() . ": " . microtime(true) . ": about to set cookie params: " . $lifetime . ", " . $path . ", " . HOST . ", " . $secure . ", " . $httponly);
        session_set_cookie_params($lifetime, $path, HOST, $secure, $httponly);
    }
?>