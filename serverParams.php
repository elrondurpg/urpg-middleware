<?php
    define("PROTOCOL", $_SERVER['USE_SECURE_MODE'] == true ? 'https://' : 'http://');
    define("DOMAIN", $_SERVER['DOMAIN']);
    define("PORT",  $_SERVER['USE_SECURE_MODE'] == true ? 443 : 80);

    define("URPG_SERVER_PORT", $_SERVER['URPG_SERVER_PORT']);
    define("URPG_SERVER_URL_FULL", PROTOCOL . DOMAIN . ":" . URPG_SERVER_PORT);

    define("URPG_WEBAPPS_PORT", determineUrpgWebappsPort());
    define("URPG_WEBAPPS_URL_FULL", PROTOCOL . DOMAIN . ":" . URPG_WEBAPPS_PORT);

    define("OAUTH2_URL", $_SERVER['OAUTH2_URL']);
    define("OAUTH2_RESPONSE_TYPE", $_SERVER['OAUTH2_RESPONSE_TYPE']);
    define("OAUTH2_CLIENT_ID", $_SERVER['OAUTH2_CLIENT_ID']);
    define("OAUTH2_SCOPE", $_SERVER['OAUTH2_SCOPE']);
    define("OAUTH2_REDIRECT_URI", PROTOCOL . DOMAIN . "/urpg-middleware/loginRedirect.php");

    function determineUrpgWebappsPort() {
        $paramName = 'URPG_WEBAPPS_ALTERNATE_PORT';
        if (isset($_SERVER) && array_key_exists($paramName, $_SERVER)) {
            return $_SERVER[$paramName];
        }
        else {
            return PORT;
        }
    }
?>