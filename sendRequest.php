<?php
    function sendRequest($method, $url, $id = false, $access_token = false, $data = false)
    {
		$url = str_replace(" ", "%20", $url);
        $curl = curl_init();
        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
                }				
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
                }
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

		if ($id && $access_token) {
			curl_setopt($curl, CURLOPT_USERPWD, $id . ":" . $access_token);
		}

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);

        $result = curl_exec($curl);
		http_response_code(curl_getinfo($curl, CURLINFO_HTTP_CODE));
        curl_close($curl);

        return $result;
    }

?>