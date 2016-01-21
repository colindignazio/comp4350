<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('mysql_now')) {
    function mysql_now() {
        return date("Y-m-d H:i:s");
    }
}

if(!function_exists('startsWith')) {
    function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }
}

if(!function_exists('endsWith')) {
    function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }
}

if(!function_exists('http_get')) {
    // Description: Returns the HTTP response or FALSE if there is an error
    function http_get($url) {
        // Start cURL session and build request
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

        // Send request and close session
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
}

if(!function_exists('http_post')) {
	// Description: Returns the HTTP response or FALSE if there is an error
	function http_post($url, $body, $sslVerify = TRUE) {
		
		// Start cURL session and build request
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true );
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($body));		
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $sslVerify);
	
		// Send request and close session
		$result = curl_exec($curl);
		curl_close($curl);
	
		return $result;
	}
}

if(!function_exists('getClientIP')) {
    function getClientIP() {
        if (isset($_SERVER)) {

            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                return $_SERVER["HTTP_X_FORWARDED_FOR"];

            if (isset($_SERVER["HTTP_CLIENT_IP"]))
                return $_SERVER["HTTP_CLIENT_IP"];

            return $_SERVER["REMOTE_ADDR"];
        }

        if (getenv('HTTP_X_FORWARDED_FOR'))
            return getenv('HTTP_X_FORWARDED_FOR');

        if (getenv('HTTP_CLIENT_IP'))
            return getenv('HTTP_CLIENT_IP');

        return getenv('REMOTE_ADDR');
    }
}
?>