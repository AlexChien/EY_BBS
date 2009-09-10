<?php
function get_ip() {
	if ($_SERVER) {
		if ($_SERVER[HTTP_X_FORWARDED_FOR]) {
			$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		elseif ($_SERVER["HTTP_CLIENT_ip"]) {
			$realip = $_SERVER["HTTP_CLIENT_ip"];
		} else {
			$realip = $_SERVER["REMOTE_ADDR"];
		}

	} else {
		if (getenv('HTTP_X_FORWARDED_FOR')) {
			$realip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_CLIENT_ip')) {
			$realip = getenv('HTTP_CLIENT_ip');
		} else {
			$realip = getenv('REMOTE_ADDR');
		}
	}
	return $realip;
}

function getUrl() {
	$url = "http://" . $_SERVER["HTTP_HOST"];

	if (isset ($_SERVER["REQUEST_URI"])) {
		$url .= $_SERVER["REQUEST_URI"];
	} else {
		$url .= $_SERVER["PHP_SELF"];
		if (!empty ($_SERVER["QUERY_STRING"])) {
			$url .= "?" . $_SERVER["QUERY_STRING"];
		}
	}

	return $url;
}

 //----------------------------------------------------
// Function GetSID()
//
// Parameters : $nSize number of caracters, default 24
// Return value : 24 caracters string
//
// Description : This function returns a random string
// of 24 caracters that can be used to identify users
// on your web site in a more secure way. You can also
// use this function to generate passwords.
//----------------------------------------------------
function GetSID ($nSize=24) {
	// Randomize
	mt_srand ((double) microtime() * 1000000);
	for ($i=1; $i<=$nSize; $i++) {
	
		// if you wish to add numbers in your string,
		// uncomment the two lines that are commented
		// in the if statement
		$nRandom = mt_rand(1,30);
		if ($nRandom <= 10) {
			// Uppercase letters
			$sessionID .= chr(mt_rand(65,90));
			// } elseif ($nRandom <= 20) {
			// $sessionID .= mt_rand(0,9);
		} else {
			// Lowercase letters
			$sessionID .= chr(mt_rand(97,122));
		}
	}
	return $sessionID;
}
?>