<?php
/**
 * @author sutra
 * @copyright Copyright &copy; 2001-2007, Redv Soft
 * @license http://openid4discuz.redv.com/LICENSE.txt BSD
 */
$path_extra = dirname(__FILE__).'/php-openid-2.0.0';
$path = ini_get('include_path');
$path = $path_extra . PATH_SEPARATOR . $path;
ini_set('include_path', $path);

function displayError($message) {
    $error = $message;
    include 'message.php';
    exit(0);
}

function doIncludes() {
    /**
     * Require the OpenID consumer code.
     */
    require_once "Auth/OpenID/Consumer.php";

    /**
     * Require the "file store" module, which we'll need to store
     * OpenID information.
     */
    require_once "Auth/OpenID/FileStore.php";

    /**
     * Require the Simple Registration extension API.
     */
    require_once "Auth/OpenID/SReg.php";

    /**
     * Require the PAPE extension module.
     */
    require_once "Auth/OpenID/PAPE.php";
}

doIncludes();

global $pape_policy_uris;
$pape_policy_uris = array(
			  PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
			  PAPE_AUTH_MULTI_FACTOR,
			  PAPE_AUTH_PHISHING_RESISTANT
			  );

function &getStore() {
    /**
     * This is where the example will store its OpenID information.
     * You should change this path if you want the example store to be
     * created elsewhere.  After you're done playing with the example
     * script, you'll have to remove this directory manually.
     */
    $store_path = "/tmp/_php_consumer_test";

    if (!file_exists($store_path) &&
        !mkdir($store_path)) {
        print "Could not create the FileStore directory '$store_path'. ".
            " Please check the effective permissions.";
        exit(0);
    }

    return new Auth_OpenID_FileStore($store_path);
}

function &getConsumer() {
    /**
     * Create a consumer object using the store object created
     * earlier.
     */
    $store = getStore();
    return new Auth_OpenID_Consumer($store);
}

function getScheme() {
    $scheme = 'http';
    if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
        $scheme .= 's';
    }
    return $scheme;
}

function getReturnTo($action) {
    // return sprintf("%s://%s:%s%s/openid.php?action=".$action,
    return sprintf("%s://%s%sopenid.php?action=".$action,
                   getScheme(), $_SERVER['SERVER_NAME'],
                   // $_SERVER['SERVER_PORT'],
                   dirname($_SERVER['PHP_SELF']));
}

function getTrustRoot() {
    // return sprintf("%s://%s:%s%s/",
    return sprintf("%s://%s%s",
                   getScheme(), $_SERVER['SERVER_NAME'],
                   // $_SERVER['SERVER_PORT'],
                   dirname($_SERVER['PHP_SELF']));
}

/**
 * Update openid session.
 */
function updateOpenIDSession($sid, $openid_identifier) {
	global $tablepre, $db;
	$db->query("DELETE FROM {$tablepre}openid_sessions WHERE sid = '".$sid."'");
	$db->query("INSERT INTO {$tablepre}openid_sessions(sid, openid_url) VALUES('".$sid."', '".$openid_identifier."')");
}

/**
 * Get openid identifier from the session.
 */
function getOpenIDFromSession($sid) {
	global $tablepre, $db;
	$query = $db->query("SELECT openid_url as openid_identifier FROM {$tablepre}openid_sessions WHERE sid='".$sid."'");
	$arr = $db->fetch_array($query);
	return $arr['openid_identifier'];
}

/**
 * Delete openid session.
 */
function deleteOpenIDSession($sid) {
	global $tablepre, $db;
	$db->query("DELETE FROM {$tablepre}openid_sessions WHERE sid='".$sid."'");
}

/**
 * Bind openid to a discuz account.
 */
function bindOpenID($uid, $openid_identifier) {
	global $tablepre, $db;
	$db->query("INSERT {$tablepre}openid(uid, openid_url) VALUES('".$uid."', '".$openid_identifier."')");
}

/**
 * Register.
 */
function registerOpenID($sid, $uid) {
	$openid_identifier = getOpenIDFromSession($sid);
	deleteOpenIDSession($sid);
	if (!empty($openid_identifier)) {
		bindOpenID($uid, $openid_identifier);
	}	
}

function generateUsername($nickname) {
	global $tablepre, $db, $query;

	$ret = null;

	$username = $nickname;
	$last_number = 0;
	$number = 0;
	$hasCached = false;

	// Find the last number in the cache.
	$query = $db->query("SELECT last_number FROM {$tablepre}openid_username_cache WHERE username = '$username'");
	if ($db->num_rows($query)) {
		$hasCached = true;
		$cache = $db->fetch_array($query);
		$last_number = $cache['last_number'];
		$number = $last_number;
	}

	if ($number == 0) {
		$query = $db->query("SELECT username FROM {$tablepre}members WHERE username = '$username'");
		if(!$db->num_rows($query)) {
			$ret = $username;
		}
	}

	if ($ret == null) {
		$number = findNextNumber($username, $number);
		$ret = $username.$number;
	}

	// Update username cache.	
	if ($hasCached) {
		$db->query("UPDATE {$tablepre}openid_username_cache SET last_number = $number WHERE username = '$username'");
	} else {
		$db->query("INSERT INTO {$tablepre}openid_username_cache(`username`, `last_number`) VALUES('$username', $number)");
	}

	return $ret;
}

/**
 * Find next number from the member table.
 */
function findNextNumber($username, $number) {
	global $tablepre, $db, $query;

	do {
		$number += 1;
		$query = $db->query("SELECT username FROM {$tablepre}members WHERE username = '$username$number'");
	} while ($db->num_rows($query));
	return $number;
}

function obtainNickname($openid_identifier, $sreg) {
	$ret = null;
	if (!empty($sreg['nickname'])) {
		$ret = $sreg['nickname'];
	} elseif (!empty($sreg['email'])) {
		$a = preg_split("/[@]/", $sreg['email']);
		$ret = $a[0];
	} else {
		$ret = parseNicknameFromUrl($openid_identifier);
	}
	return strtolower($ret);
}

function parseNicknameFromUrl($openid_identifier) {
	$m = preg_match("/^(http[s]?:\/\/)?([^\/]+)/i", $openid_identifier, $matches);
	if (!$m) {
		return $openid_identifier;
	}

	$host = $matches[2];
	// xxx.openid.org.cn, xxx.myopenid.com, xxx.openid.cn, xxx.openid.35.com, xxx.openid.org, xxx.mysecond.name, xxx.pip.verisignlabs.com
	if (preg_match("/([^\.\/]+)\.((openid\.org\.cn)|(myopenid\.com)|(openid\.cn)|(openid\.35\.com)|(openid\.org)|(mysecond\.name)|(pip\.verisignlabs\.com))/", $host, $matches)) {
		$ret = $matches[1];
	} else {
		// www.ican.com.cn/xxx
		if(preg_match("/^(http[s]?:\/\/)?[^\/]+[\/]+([^\/]+)/i", $openid_identifier, $matches)) {
			$ret = $matches[2];
		} else {
			$ret = $host;
		}
	}
	return $ret;
}

function tryAuth($openid_identifier, $returnTo) {
    $openid = $openid_identifier;
    $consumer = getConsumer();

    // Begin the OpenID authentication process.
    $auth_request = $consumer->begin($openid);

    // No auth request means we can't begin OpenID.
    if (!$auth_request) {
        displayError("Authentication error; not a valid OpenID.");
    }

    $sreg_request = Auth_OpenID_SRegRequest::build(
                                     // Required
                                     array('nickname'),
                                     // Optional
                                     array('fullname', 'email', 'dob'));

    if ($sreg_request) {
        $auth_request->addExtension($sreg_request);
    }

    $policy_uris = $_GET['policies'];

    $pape_request = new Auth_OpenID_PAPE_Request($policy_uris);
    if ($pape_request) {
        $auth_request->addExtension($pape_request);
    }

    // Redirect the user to the OpenID server for authentication.
    // Store the token for this authentication so we can verify the
    // response.

    // For OpenID 1, send a redirect.  For OpenID 2, use a Javascript
    // form to send a POST request to the server.
    if ($auth_request->shouldSendRedirect()) {
        $redirect_url = $auth_request->redirectURL(getTrustRoot(),
                                                   $returnTo);

        // If the redirect URL can't be built, display an error
        // message.
        if (Auth_OpenID::isFailure($redirect_url)) {
            displayError("Could not redirect to server: " . $redirect_url->message);
        } else {
            // Send redirect.
            header("Location: ".$redirect_url);
        }
    } else {
        // Generate form markup and render it.
        $form_id = 'openid_message';
        $form_html = $auth_request->formMarkup(getTrustRoot(), $returnTo,
                                               false, array('id' => $form_id));

        // Display an error if the form markup couldn't be generated;
        // otherwise, render the HTML.
        if (Auth_OpenID::isFailure($form_html)) {
            displayError("Could not redirect to server: " . $form_html->message);
        } else {
            $page_contents = array(
               "<html><head><title>",
               "OpenID transaction in progress",
				"redirect_url: ".getTrustRoot(),
               "</title></head>",
               "<body onload='document.getElementById(\"".$form_id."\").submit()'>",
               $form_html,
               "</body></html>");

            print implode("\n", $page_contents);
        }
    }
}
?>