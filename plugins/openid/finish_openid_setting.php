<?php
/**
 * Finish openid setting.
 * @author sutra
 * @copyright Copyright &copy; 2001-2007, Redv Soft
 * @license http://openid4discuz.redv.com/LICENSE.txt BSD
 */
require_once "common.php";
include_once language('openid_setting');
session_start();

function openid_setting($openid) {
	global $discuz_uid, $tablepre, $db, $query;

	$query = $db->query("SELECT openid_url FROM {$tablepre}openid WHERE uid <> "
		.$discuz_uid." AND openid_url='".$openid."'");
	$member_openid = $db->fetch_array($query);
	echo $member_openid;
	if ($member_openid['openid_url']) {
		showmessage($GLOBALS['language']['openid_bind_failed'],
			"plugin.php?identifier=openid4discuz&module=openid_setting");
	}

	$query = $db->query("SELECT openid_url FROM {$tablepre}openid WHERE uid=".$discuz_uid);
	$old_openid = $db->fetch_array($query);
	if (!$old_openid['openid_url']) {
		$db->query("INSERT {$tablepre}openid(uid, openid_url) VALUES(".$discuz_uid.", '".$openid."')");
		showmessage($GLOBALS['language']['openid_insert_success_before'].'<a href="'
			.$openid.'">'.$openid.'</a>'.$GLOBALS['language']['openid_insert_success_after'],
			"plugin.php?identifier=openid4discuz&module=openid_setting");
	}
	elseif ($old_openid['openid_url'] != $openid) {
		$query = $db->query("UPDATE {$tablepre}openid SET openid_url = '".$openid."' WHERE uid = ".$discuz_uid);
		showmessage($GLOBALS['language']['openid_update_success_before'].'<a href="'
			.$openid.'">'.$openid.'</a>'.$GLOBALS['language']['openid_update_success_after'],
			"plugin.php?identifier=openid4discuz&module=openid_setting");
	} else {
		showmessage($GLOBALS['language']['openid_update_noupdate_before']."<a href='"
			.$old_openid['openid_url']."'>".$old_openid['openid_url']."</a>"
			.$GLOBALS['language']['openid_update_noupdate_after'],
			"plugin.php?identifier=openid4discuz&module=openid_setting");
	}
}

function run() {
    $consumer = getConsumer();
    // Complete the authentication process using the server's
    // response.
    $return_to = getReturnTo('finish_auth_openid_setting');
    $response = $consumer->complete($return_to);

    // Check the response status.
    if ($response->status == Auth_OpenID_CANCEL) {
        // This means the authentication was cancelled.
        $msg = 'Verification cancelled.';
    } else if ($response->status == Auth_OpenID_FAILURE) {
        // Authentication failed; display the error message.
        $msg = "OpenID authentication failed: " . $response->message;
    } else if ($response->status == Auth_OpenID_SUCCESS) {
        // This means the authentication succeeded; extract the
        // identity URL and Simple Registration data (if it was
        // returned).
        $openid = $response->getDisplayIdentifier();
        openid_setting($openid);
    }

    include 'message.php';
}

run();
?>